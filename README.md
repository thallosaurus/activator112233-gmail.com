# liesmich-alpha
## A simple, geo-based social network

### Intro
The idea behind this network is, that if you post something it only gets linked with your position.
The posted contents can only be seen by people inside a predefined radius(standart is 25 kilometers)

### Installation
To develop locally without a cloud, you need the following components installed on your workstation:
 - [Composer](https://getcomposer.org/)
 - [PHP and a Webserver](https://www.php.net/)
 - [MySQL/MariaDB](https://mariadb.org/)
 - [heroku CLI](https://devcenter.heroku.com/articles/heroku-cli)

#### Install Dependencies
To pull the latest changes of dependencies, download composer from [here](https://getcomposer.org/download/), move it to the folder and run ```php composer.phar install```. This downloads all dependencies and sets up the environment.

To setup the database, create a ".env" file and give it the following content:
```DB_HOST=<MySQL Database>
DB_PORT=<MySQL Port, default: 3306>
DB_USER=<MySQL Username>
DB_PW=<MySQL Username>
DB_TABLE=<MySQL Tablename>
PAGECOLOR=<Set color tint for page>
PINNED_CATEGORIES=<Legacy, unused>
FAB_USE_SUBBUTTONS=<true, if the FAB should show subbuttons and false, if the FAB should extend into a actionbar>
ENVIRONMENT=<When set to dev, the UI will unlock development links>
```
See [Materialize](https://materializecss.com/color.html) for more information on color

To run it locally, use

```heroku local web```

if you use windows, use
```heroku local dev```

#### Local Demo
Switch to [https://liesmich.herokuapp.com](https://liesmich.herokuapp.com) to see the app live in action!

For the latest dev release, switch over to [https://liesmichdev.herokuapp.com](https://liesmichdev.herokuapp.com). To view the dev source, switch branch to "development"