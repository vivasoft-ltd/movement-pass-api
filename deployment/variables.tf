variable "region" {
  default = "ap-southeast-1"
}

variable "aws_access_key" {}
variable "aws_secret_key" {}

variable "ecs_task_execution_role" {}
variable "ecs_instance_role" {}
variable "env" {}

variable "default_vpc_id" {}
variable "private_subnet_a" {}
variable "private_subnet_b" {}

variable "ecs_image_ami" {}

variable "cluster_name" {}

variable "instance_type" {}

variable "bastion_sg_id" {}

variable "mongo_instance_type" {}
variable "mongo_image_ami" {}
variable "mongo_instance_keypair_name" {}
variable "mongo_volume_id" {}

variable "cache_name" {
  default = "gps-server-cache"
}

variable "cache_engine" {
  default = "redis"
}

variable "cache_node_type" {
  default = "cache.t3.small"
}

variable "cache_port" {
  default = 6379
}

variable "rabbitmq_port" {
  default = 5672
}

variable "num_cache_nodes" {
  default = 1
}

variable "parameter_group_name" {
  default = "default.redis3.2"
}

variable "cache_engine_version" {
  default = "3.2.10"
}