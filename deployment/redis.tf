resource "aws_security_group" "cache_sg" {
  depends_on = [
    aws_security_group.sg_ecs_instance
  ]

  vpc_id            = var.default_vpc_id
  name              = "cache-SG"

  ingress {
    protocol        = "tcp"
    from_port       = var.cache_port
    to_port         = var.cache_port
    security_groups = [
      aws_security_group.sg_ecs_instance.id,
      var.bastion_sg_id,
    ]
  }

  egress {
    from_port       = 0
    to_port         = 65535
    protocol        = "tcp"
    cidr_blocks     = ["0.0.0.0/0"]
  }
}

resource "aws_elasticache_cluster" "pass_cache" {
  cluster_id           = var.cache_name
  engine               = var.cache_engine
  node_type            = var.cache_node_type
  num_cache_nodes      = var.num_cache_nodes
  parameter_group_name = var.parameter_group_name
  engine_version       = var.cache_engine_version
  port                 = var.cache_port
  security_group_ids = [
    aws_security_group.cache_sg.id,
  ]
}
