# Docker: Practical Use in a Microservices Scenario

![Docker](https://img.shields.io/badge/Docker-Containerization-2496ED?logo=docker&logoColor=white)
![NGINX](https://img.shields.io/badge/NGINX-Reverse%20Proxy-009639?logo=nginx&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)

## Overview

This repository contains a practical implementation of a microservices-oriented
architecture using Docker. The project was developed as part of a DevOps challenge
focused on containerization, service isolation, and load balancing.

The solution demonstrates how Docker enables reproducible environments,
independent service scaling, and simplified infrastructure management.


## Architecture

The stack is composed of the following services:

- **NGINX** → Reverse Proxy / Load Balancer  
- **PHP-FPM** → Application Layer  
- **MySQL** → Database Layer  
- **Adminer** → Database Management Interface  

Each component runs in its own container, ensuring decoupling and modularity.

## Technologies

- Docker  
- Docker Compose  
- NGINX  
- PHP 8.3 (FPM)  
- MySQL 8  
- Bootstrap 5  

## Execution

Start the environment:

```bash
docker compose up -d

# Application endpoint:
http://localhost:4500


# Adminer interface:
http://localhost:8081
```

## Load Balancing Test
Scale the application service:

```bash
docker compose up -d --scale app=3
```

Reload the application endpoint and observe different container hostnames,
confirming request distribution via NGINX.

## Objective

- This project aims to illustrate:
- Containerized microservices structure
- Reverse proxy configuration
- Horizontal scaling
- Database integration
- Infrastructure reproducibility

## Author
Ezequiel Miguel Cavalcante do Nascimento
