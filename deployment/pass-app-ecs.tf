provider "aws" {
  access_key = var.aws_access_key
  secret_key = var.aws_secret_key
  region = var.region
  version = "~> 2.51"
}

data "aws_iam_role" "iam_role_ecs_task_execution" {
  name = var.ecs_task_execution_role
}

data "aws_iam_instance_profile" "iam_role_ecs_instance" {
  name = var.ecs_instance_role
}

resource "aws_security_group" "sg_pass_app_lb" {
  name = "${var.env}-sg-pass-app-lb"
  description = "Security group for ${var.env} environment to access to pass app LB from anywhere"
  vpc_id = var.default_vpc_id

  ingress {
    from_port = 443
    to_port = 443
    protocol = "tcp"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
    description = "Anywhere (https)"
  }

  egress {
    from_port = 0
    to_port = 0
    protocol = "-1"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
  }

  tags = {
    Name = "${var.env}-sg-pass-app-lb"
  }
}

resource "aws_lb" "lb_pass_app" {
  depends_on = [
    aws_security_group.sg_pass_app_lb
  ]

  name = "${var.env}-lb-pass-app"
  load_balancer_type = "application"
  security_groups = [
    aws_security_group.sg_pass_app_lb.id
  ]
  subnets = [
    var.private_subnet_a,
    var.private_subnet_b
  ]

  internal = false
}

resource "aws_lb_target_group" "lb_tg_pass_app" {
  name = "${var.env}-lb-tg-pass-app"
  port = 1215
  protocol = "HTTP"
  vpc_id = var.default_vpc_id

  health_check {
    path = "/"
    protocol = "HTTP"
    healthy_threshold = 2
    unhealthy_threshold = 2
    interval = 30
    matcher = "200"

    enabled = true
  }
}

resource "aws_lb_listener" "lb_listener_pass_app" {
  depends_on = [
    aws_lb.lb_pass_app,
    aws_lb_target_group.lb_tg_pass_app
  ]

  load_balancer_arn = aws_lb.lb_pass_app.arn
  #port = "443"
  #protocol = "HTTPS"
  #ssl_policy = "ELBSecurityPolicy-TLS-1-1-2017-01"
  #certificate_arn = var.ws_lb_ssl_certificate_arn

  port = "80"
  protocol = "HTTPS"

  default_action {
    type = "forward"
    target_group_arn = aws_lb_target_group.lb_tg_pass_app.arn
  }
}

resource "aws_security_group" "sg_ecs_instance" {
  depends_on = [
    aws_security_group.sg_pass_app_lb
  ]

  name = "${var.env}-sg-pass-app-ecs-instance"
  description = "Security group for ${var.env} environment to access to pass ecs instance from LB"
  vpc_id = var.default_vpc_id

  ingress {
    from_port = 1215
    to_port = 1215
    protocol = "tcp"
    description = "LB"
    security_groups = [aws_security_group.sg_pass_app_lb.id]
  }

  egress {
    from_port = 0
    to_port = 0
    protocol = "-1"
    cidr_blocks = [
      "0.0.0.0/0"
    ]
  }

  tags = {
    Name = "${var.env}-sg-pass-app-ecs-instance"
  }
}

resource "aws_launch_configuration" "ecs_launch_config" {
  depends_on = [
    aws_security_group.sg_ecs_instance
  ]

  image_id             = var.ecs_image_ami
  security_groups      = [aws_security_group.sg_ecs_instance.id]
  user_data            = "#!/bin/bash\necho ECS_CLUSTER=${var.cluster_name} >> /etc/ecs/ecs.config"
  instance_type        = var.instance_type
}

resource "aws_autoscaling_group" "pass_app_asg" {
  depends_on = [
    aws_launch_configuration.ecs_launch_config
  ]

  name                      = "${var.env}-pass-app-asg"
  vpc_zone_identifier       = [var.default_vpc_id]
  launch_configuration      = aws_launch_configuration.ecs_launch_config.name

  desired_capacity          = 0
  min_size                  = 0
  max_size                  = 0
  health_check_grace_period = 300
  health_check_type         = "EC2"
}

resource "aws_ecs_cluster" "pass_app" {
  name  = var.cluster_name
}


resource "aws_ecs_task_definition" "ecs_taskdef_pass_api" {
  family = "${var.env}-ecs-taskdef-pass-app"
  container_definitions = <<EOF
  [
    {
      "name": "${var.env}-ecs-container-pass-app",
      "dockerLabels": {
        "Name": "${var.env}-ecs-container-pass-app"
      },
      "image": "WILL_BE_MANAGED_BY_CI_CD",
      "memory": 1024,
      "memoryReservation": 512,
      "portMappings": [
        {
          "hostPort": 1215,
          "protocol": "tcp",
          "containerPort": 1215
        }
      ],
      "environment": [],
      "ulimits": [
        {
          "name": "nofile",
          "softLimit": 65536,
          "hardLimit": 65536
        }
      ]
    }
  ]
  EOF

  network_mode = "bridge"

  execution_role_arn = data.aws_iam_role.iam_role_ecs_task_execution.arn
  task_role_arn = data.aws_iam_role.iam_role_ecs_task_execution.arn

  requires_compatibilities = [
    "EC2"
  ]
}

resource "aws_ecs_service" "ecs_service_pass_app" {
  depends_on = [
    aws_ecs_cluster.pass_app,
    aws_ecs_task_definition.ecs_taskdef_pass_api,
    aws_lb_listener.lb_listener_pass_app
  ]

  name = "${var.env}-ecs-service-pass-app"
  cluster = aws_ecs_cluster.pass_app.id
  task_definition = aws_ecs_task_definition.ecs_taskdef_pass_api.arn
  launch_type = "EC2"
  scheduling_strategy = "DAEMON"
  deployment_minimum_healthy_percent = 99
  deployment_maximum_percent = 100
  health_check_grace_period_seconds = 600

  load_balancer {
    target_group_arn = aws_lb_target_group.lb_tg_pass_app.arn
    container_name = "${var.env}-ecs-container-pass-app"
    container_port = 1215
  }

  lifecycle {
    ignore_changes = [desired_count,task_definition]
  }
}
