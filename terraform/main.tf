

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

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
 