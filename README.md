# Simple-Bank

Simple-Bank is a PHP software for bank branches and customers.

Requirements:

1. It should be possible to add new branches.
2. It should be possible to add new customers with a starting balance.
3. It should be possible to transfer a sum of money between any two customers.
4. It should be possible to run the following two reports:
   a) Show all branches along with the highest balance at each branch. A branch with no customers should show 0 as the highest balance.
   b) List just those branches that have more than two customers with a balance over 50,000.
   
Analysis Implementation:

The project try to follow the DDD principle.


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

Future Improvements:

```
[] Add OAuth 
[] Add/Improve API response
[] Add Funcional tests
[] Add Controller tests
[] Wire transfers should be asynchronous
[] Add Bus
[] Using Fixtures for infrastructure tests
[] Improve UI/UX
[] Improve form validations
[] Improve server validations
[] Improve exceptions handler
[] Wire transfers should logged into a history table
[] More...
```

## License
Head Horse SL
