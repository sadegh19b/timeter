# Timeter

Timeter is a small utility app to keep track of your time. It has a minimalistic features set required and sleek design. It is an ideal tool for a workplace that does not provide you with an automated way of knowing at which time you arrived and left the office. Stop opening, closing your local text editor for persisting and calculating the timesheet and hours manually. Start using Timeter now.

## Requirements

- [PHP](https://www.php.net/downloads) 8.1 or higher
- Database (eg: MySQL, PostgreSQL, SQLite)
- Web Server (eg: Apache, Nginx, IIS or use [Laragon](https://laragon.org/download/index.html))
- [Composer](https://getcomposer.org/download)
- [Node.js](http://nodejs.org)

## Features

- You can create multiple projects
- You can set the hourly wage of the project
- You can add the time spent manually (start at datetime and end at datetime)
- You can use the timer to track the time spent on the project
- Display the times calculated in the project (today, week, month, all)
- Display the earned wages calculated in the project (today, week, month, all)

## Getting Started

### Clone the project

Clone the repository and switch to the repo folder.

```shell
git clone https://github.com/sadegh19b/timeter.git
cd timeter
```

### Install and Run the project

Install the node and php dependencies and after that, you need to create the database to run the migrations and configure the `.env` file.

```shell
npm install
npm run build
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Run the project with docker

This project use laravel sail for run the project in docker. ([more information](https://laravel.com/docs/9.x/sail))

*Note: if created laravel sail image before in docker app, just run `./vendor/bin/sail up`*

```shell
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/var/www/html \
-w /var/www/html \
laravelsail/php81-composer:latest \
composer install --ignore-platform-reqs
```

Now, switch to the project directory and run the command below:

```shell
cp .env.example .env
```

Edit the `.env` file and change `DB_HOST=mysql` and then following the commands.

```shell
./vendor/bin/sail up -d
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

## Configurations

### Language

The default language is English `en`, and it currently supports English `en` and Persian `fa`. You can change it in the `.env` file.

```shell
APP_LANG=fa
```

### Timezone

The default timezone is `UTC`. You can change it in the `.env` file.

```shell
APP_TIMEZONE=Asia/Tehran
```

## Testing

Run the project tests by following the command below:

```shell
php artisan test
```

Run tests in docker:

```shell
./vendor/bin/sail artisan test
```

## License

Timeter is open source software released under the MIT license. See [LICENSE](LICENSE) for more information.
