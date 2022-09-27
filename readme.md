# CATAPI Browser

Developed by Renzy for IONA.
This is a plugin folder, that can work independently on any Wordpress installation.
But you can read Installation Guide for setting up on Docker.

## Dependencies
* Must have Docker Desktop
* The db image is uploaded in https://hub.docker.com/repository/docker/renzy0113/catapi-db
* A separate sql file is included in this GIT, db/cat.sql.zip

## Installation Guide

* clone this repository in your localhost
```
git clone https://github.com/renzyyyyy/catapi.git
```
* open terminal and navigate to the git directory
* run
```
docker-compose up -d
```
* Docker should download a fresh wordpress:latest, phpmyadmin and existing db from dockerhub.
* You can navigate to http://localhost:8000 ( or change these settings in docker-compose.yml) to view the website.

## Screenshots

Homepage
![homepage.png](https://github.com/renzyyyyy/catAPI/blob/master/images/homepage.png?raw=true)
Single Cat Page
![singlepage.png](https://github.com/renzyyyyy/catAPI/blob/master/images/single-cat.png?raw=true)

