resource "aws_security_group" "sg_mongo" {
  name = "${var.env}-sg-mongo"
  description = "Security group for ${var.env} environment to access mongo"
  vpc_id = var.default_vpc_id

  ingress {
    from_port = 27017
    to_port = 27017
    protocol = "tcp"
    security_groups = [var.bastion_sg_id]
    description = "Bastion"
  }

  ingress {
    from_port = 22
    to_port = 22
    protocol = "tcp"
    security_groups = [var.bastion_sg_id]
    description = "SSH From bastion"
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
    Name = "${var.env}-sg-mongo"
  }
}

resource "aws_instance" "mongo" {
  depends_on = [
    aws_security_group.sg_mongo
  ]

  ami = var.mongo_image_ami
  instance_type = var.mongo_instance_type
  key_name = var.mongo_instance_keypair_name

  vpc_security_group_ids = [
    aws_security_group.sg_mongo.id
  ]

  # Forcing subnet because of the data volume
  subnet_id = var.private_subnet_a

  user_data = <<-EOF
#!/bin/bash
yum install docker -y
service docker restart
usermod -aG docker ec2-user
curl -L "https://github.com/docker/compose/releases/download/1.25.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
  EOF

  tags = {
    Name = "${var.env}-mongo"
  }
}

resource "aws_volume_attachment" "mongo_data_volume" {
  depends_on = [
    aws_instance.mongo
  ]

  device_name = "/dev/sdh"
  volume_id = var.mongo_volume_id
  instance_id = aws_instance.mongo.id
}

