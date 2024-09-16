<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About the Project

This project is a Laravel-based application designed to process and insert URLs into a database. The application is containerized using Docker, manages queue workers with Supervisor. The front-end is enhanced using Alpine.js & Bootstrap.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Building the Docker Image](#building-the-docker-image)
- [Running the Application](#running-the-application)
- [Managing the Queue Worker](#managing-the-queue-worker)
- [Screenshots](#screenshots)
- [Useful Docker Commands](#useful-docker-commands)
- [License](#license)

## Prerequisites

Before getting started, ensure that you have the following tools installed:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/)

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/murad034/link-harvester.git
   cd link-harvester

2. **Copy the `.env` file**

   ```bash
   cp .env.example .env

3. **Update Environment Variables**

    Open the .env file and update the necessary environment variables, including database credentials.

## If the below command is not working for not building the docker image, go to #10 and #11 then again run the below

4. **Install Composer Dependencies**

   ```bash
   docker run --rm -v $(pwd):/var/www/html link_harvester composer install

5. **Generate the Application Key**
    ```bash
    docker run --rm -v $(pwd):/var/www/html link_harvester php artisan key:generate

6. **Run database migration for creating tables**
    ```bash
    docker run --rm -v $(pwd):/var/www/html link_harvester php artisan migrate

7. **Install Front-End Assets**
    ```bash
    docker run --rm -v $(pwd):/var/www/html link_harvester npm install
   
8. **Build Front-End Assets(For Development)**
    ```bash
    docker run --rm -v $(pwd):/var/www/html link_harvester npm run dev

9. **Build Front-End Assets(For Production)**
     ```bash
     docker run --rm -v $(pwd):/var/www/html link_harvester npm run build


## Configuration

<p>Docker Setup</p>
The Docker setup for this project is managed through the `docker-compose.yml` file. It configures two primary services:

app: This service runs the Laravel application using PHP 8.2 with Apache It is responsible for serving the application and includes volume mounts for the code and environment variables for configuration

db: This service runs a MySQL database container, which the Laravel application uses to store and retrieve data. It includes environment variables for database configuration and persists data using Docker volumes.

redis: Redis is used to handle caching, session management, and queue management for the Laravel application. The Redis service runs in its own container using the `redis:alpine` image. It exposes port `6379`, and you can configure it using environment variables such as `REDIS_HOST` and `REDIS_PORT`. The Redis container automatically restarts if it encounters any issues, ensuring that caching and queue management services are always running.

redisadmin: To manage Redis visually, the project uses a Redis Admin interface through the `erikdubbelboer/phpredisadmin` image. This service allows you to view and manage Redis keys and data via a web interface. It exposes port `8082` and connects to the Redis service defined in the same Docker network.

Make sure to adjust the environment variables in the docker-compose.yml file according to your local setup, such as database credentials, host & redis information.

## Supervisor Configuration

The Supervisor configuration is used to manage background processes for the application. The configuration is specified in supervisord.conf, where it defines:

Apache Server: Managed by Supervisor to ensure it runs continuously in the foreground.

Laravel Queue Worker: Configured to process background jobs. Supervisor ensures that the worker process starts automatically and restarts if it crashes.

Redis: Redis is used for caching, session handling, and managing queue jobs. Supervisor ensures that Redis remains available by running it continuously in the background.

## Building the Docker Image

10. **To build the Docker image for the application, run**
    ```bash
    docker-compose build

## Running the Application
11. **To start the application and services, run**
    ```bash
    docker-compose up -d

12. **This command will start the application in detached mode. You can check the logs with:**
    ```bash
    docker-compose logs -f

13. **Go to the browser and hit the below URL for the locally running project:**
    ```bash
    http://localhost:8081

14. **Go to the browser and hit the below URL for the locally checking Redis queue, cache & session:**
    ```bash
    http://localhost:8082
  

## Screenshots
 <p>Below are some screenshots of the application:</p>
 
 <p>Application Overview</p>
 
 <p>URL List</p>
 
 ![URL List](image/URLs-List.png)
 
 <p>Create URL</p>
 
 ![Create URL](image/create-URLs.png)
 
 <p>DB URL Tabel</p>
  
 ![DB URL Table](image/url-table.png)
 
 <p>DB Domain Table</p>

 ![DB Domain Table](image/domain-table.png)
 

## Overview Video

<p>Below is the overview video of the application:</p>
<a href="https://www.awesomescreenshot.com/video/30913586?key=54690a0c2b58df40480cf84e74f96261">Watch the video</a>

<video width="600" controls>
   <source src="images/overview.webm" type="video/webm">
   Your browser does not support the video tag.
</video>
 



## Useful Docker Commands
<p>Here are some useful Docker commands for managing your application:</p>

14. **Useful command to build the Docker Image:**

    ```bash
    docker-compose build
    docker-compose up -d
    docker-compose down
    docker-compose logs -f
    docker system prune -a
    docker ps
    docker images
    docker rmi -f d4d(Image ID)
    docker-compose exec app php artisan optimize:cache

    //Convert Linux terminal from any Windows terminal
    wsl
    sudo su

    // nginx start status
    sudo systemctl status nginx
    sudo systemctl stop nginx

    // manually supervisor status
    docker-compose exec app supervisorctl reread
    docker-compose exec app supervisorctl update
    docker-compose exec app supervisorctl restart laravel-worker:*
    
    // cache clear command in container & build-up
    docker-compose exec app php artisan config:clear
    docker exec -it link_harvester php artisan config:clear
    docker exec -it link_harvester php artisan cache:clear
    docker exec -it link_harvester php artisan optimize:clear
    docker-compose down
    docker-compose build
    docker-compose up -d

    // log for link harvester container
    docker logs link_harvester
    docker-compose up

    // supervisor manually checks status, start, stop & restart
    docker exec -it link_harvester supervisorctl reread
    docker exec -it link_harvester supervisorctl update
    docker exec -it link_harvester supervisorctl restart all
    docker exec -it link_harvester supervisorctl status
    docker exec -it link_harvester supervisorctl stop all
    docker exec -it link_harvester supervisorctl start all
    docker exec -it link_harvester supervisorctl stop laravel-worker:laravel-worker_00
    docker exec -it link_harvester supervisorctl start laravel-worker:laravel-worker_00
    
    // check Redis enable or not, result PONG
    docker exec -it link_harvester_redis redis-cli ping
    
    docker exec -it link_harvester_redis redis-cli FLUSHALL
    docker exec -it link_harvester_redis redis-cli MONITOR

    // Redis shows queue, cache, session data using below command
    docker exec -it link_harvester_redis redis-cli
    keys *
    lrange queues:default 0 -1
    lrange link_harvester_database_queues:default 0 -1
    get link_harvester_database_InArKQBOUfF41MbNJTjRrZqAadTr1x7mxJhnSMhP


    // install predis, telescope, debugger
    docker exec -it link_harvester composer require predis/predis
    docker exec -it link_harvester composer require barryvdh/laravel-debugbar
    docker exec -it link_harvester  composer require laravel/telescope
    docker exec -it link_harvester php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
    docker exec -it link_harvester php artisan telescope:install
    docker exec -it link_harvester php artisan tinker
    

#License
This project is licensed under the [MIT License](https://opensource.org/license/MIT)
