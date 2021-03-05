# Symfony - GraphQL - DataLoader

## Introduction

This is a POC repository to use the Dataloader from [Overblog](https://github.com/overblog/dataloader-bundle).

## Requirements

* Docker & docker-compose

## Installation

To install this project, clone this project on your device. Rename the `docker-compose.override.yml.dist` file to `docker-compose.override.yml`.
In the override file, you are able to change the port you want to use to run this project.

You can now install the containers thanks to **docker-compose**. The first time, it will install your environment on your containers.

    docker-compose up -d

You can now access your containers with `docker-compose exec <container_name> bash` or use `ahoy tty` to access your _app_ container. Once you are in this container, you have to install Symfony and all its dependencies (that are listed in the `package.json` file). To do this you only have to do :
    
    composer install

You need to set up the environment variables in the `.env` file (you can create a `.env.local` to override the `.env` configuration only on your device) :

`.env` variable | Default Value | Details
--------------- | ------------- | -------
`MONGODB_URL` | `mongodb://root:example@mongo:27017/?authSource=admin` | This is the url of your database. Thanks to your Mongodb container, you can use the default value as a local database url
`MONGODB_DB` | `Symfony` | This is the name of the collection you want to use on MongoDB

Once you have everything set up, you are able to access your projet locally, on the port you have set up in `docker-compose.yaml`
