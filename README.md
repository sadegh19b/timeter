# Timeter

Timeter is a small utility app to keep track of your time. It has a minimalistic features set required and sleek design. It is an ideal tool for a workplace that does not provide you with an automated way of knowing at which time you arrived and left the office. Stop opening, closing your local text editor for persisting and calculating the timesheet and hours manually. Start using Timeter now.

## Getting Started

### Clone the project

Clone the repository and switch to the repo folder.

```shell
git clone https://github.com/sadegh19b/timeter.git
cd timeter
```

### Install and Run the project

Install php and node dependencies, and after that you must create the database for run migrations and configure `.env` file.

```shell
composer install
npm install
npm run build 
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

Now, switch to the project directory and run `sail up` command.

```shell
cd timeter
./vendor/bin/sail up
```

## Testing

Run tests of the project by following the below command:

```shell
php artisan test
```

Run tests in docker:

```shell
./vendor/bin/sail artisan test
```
