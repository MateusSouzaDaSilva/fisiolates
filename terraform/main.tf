provider "aws" {
  region = "us-east-2"
  
}

resource "aws_ecr_repository" "app_repo" {
  name = "fisiolates"
}


# IAM Role para o EC2
resource "aws_iam_role" "ec2_role" {
  name = "ec2_fisiolates_role"

  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Effect = "Allow"
        Principal = {
          Service = "ec2.amazonaws.com"
        }
        Action = "sts:AssumeRole"
      }
    ]
  })
}

# Política para acesso ao ECR
resource "aws_iam_role_policy" "ec2_ecr_policy" {
  name = "ec2_ecr_policy"
  role = aws_iam_role.ec2_role.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Effect = "Allow"
        Action = [
          "ecr:GetDownloadUrlForLayer",
          "ecr:BatchGetImage",
          "ecr:BatchCheckLayerAvailability",
          "ecr:GetAuthorizationToken"
        ]
        Resource = "*"
      }
    ]
  })
}

# Perfil de instância para o EC2
resource "aws_iam_instance_profile" "ec2_instance_profile" {
  name = "ec2_fisiolates_instance_profile"
  role = aws_iam_role.ec2_role.name
}

resource "aws_instance" "app_ec2" {
  ami                         = "ami-0d1b5a8c13042c939"
  instance_type               = "t2.micro"
  key_name                    = var.fisiolates-aws-key
  associate_public_ip_address = true
  iam_instance_profile = aws_iam_instance_profile.ec2_instance_profile.name # Associa o IAM Role


  # Script de user data para instalar Docker e AWS CLI
  user_data = <<-EOF
    #!/bin/bash
    # Configura o debconf para modo não interativo
    echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

    # Atualiza pacotes e instala dependências básicas
    apt-get update
    DEBIAN_FRONTEND=noninteractive apt-get install -y ca-certificates curl gnupg unzip

    # Cria o diretório para a chave GPG do Docker
    mkdir -p /etc/apt/keyrings

    # Instala o Docker
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /tmp/docker.gpg
    gpg --dearmor -o /etc/apt/keyrings/docker.gpg /tmp/docker.gpg
    chmod a+r /etc/apt/keyrings/docker.gpg
    rm /tmp/docker.gpg
    echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | \
      tee /etc/apt/sources.list.d/docker.list > /dev/null
    apt-get update
    DEBIAN_FRONTEND=noninteractive apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    usermod -aG docker ubuntu
    systemctl enable docker
    systemctl start docker

    # Instala a AWS CLI v2
    curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
    unzip -q awscliv2.zip
    ./aws/install
    rm -rf aws awscliv2.zip

    # Faz login no ECR
    aws ecr get-login-password --region us-east-2 | docker login --username AWS --password-stdin ***.dkr.ecr.us-east-2.amazonaws.com
    if [ $? -ne 0 ]; then
      echo "Erro ao fazer login no ECR" >&2
      exit 1
    fi

    # Corrige permissões do config.json
    chown ubuntu:ubuntu /home/ubuntu/.docker/config.json
    chmod 600 /home/ubuntu/.docker/config.json

    # Verifica as instalações
    docker --version
    aws --version
  EOF

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

  ingress {
    from_port   = 8000
    to_port     = 8000
    protocol    = "custom tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 0
    to_port     = 65535
    protocol    = "all tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
 