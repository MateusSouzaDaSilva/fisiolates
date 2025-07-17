provider "aws" {
  region = "us-east-2"
}


resource "aws_ecr_repository" "app_repo" {
  name = "fisiolates"
}

resource "aws_instance" "app_ec2" {
  ami                         = "ami-0c55b159cbfafe1f0"
  instance_type               = "t2.micro"
  key_name                    = var.fisiolates-aws-key
  associate_public_ip_address = true

  tags = {
    Name = "fisiolates-ec2"
  }

  vpc_security_group_ids = [aws_security_group.allow_ssh.id]
}

resource "aws_security_group" "allow_ssh" {
  name        = "allow_ssh"
  description = "Allow SSH inbound traffic"

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
 