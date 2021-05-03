# Simple-Bank

Simple-Bank is a PHP software for bank branches and customers

## Installation

Requirements:

php >=7.4

```bash
$  sudo apt -y install php7.4   
```

npm

```bash
$ sudo apt -y install npm
```

Download the repository:

```bash
$ git clone git@github.com:mrpeterz/simple-bank.git
```
Install the php dependencies:

```bash
$ cd simple-bank/
$ composer install
```

Install the npm dependencies:

```bash
$ npm install
```

Compile npm 

```bash
$ npm run dev
```

Start MySQL instance:

```bash
$ cd docker\
$ docker-compose -f docker-compose.dev.yml up --build
```
Run php migration:

```bash
$ php bin/console doctrine:migrations:migrate
```
Run symfony server:

```bash
$ cd simple-bank/
$ symfony server:start
```

Go to:

```bash
http://localhost:8000/
```

## License
Head Horse SL
