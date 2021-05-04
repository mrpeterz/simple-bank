# Simple-Bank 
:credit_card: :moneybag: :money_with_wings: :dollar:

Simple-Bank is a PHP software for bank branches and customers.

:point_down: Requirements:

1. It should be possible to add new branches.
2. It should be possible to add new customers with a starting balance.
3. It should be possible to transfer a sum of money between any two customers.
4. It should be possible to run the following two reports:
   * a) Show all branches along with the highest balance at each branch. A branch with no customers should show 0 as the highest balance.
   * b) List just those branches that have more than two customers with a balance over 50,000.

:male_detective: Analysis implementation:

The project follows `Domain Driven Design` / `Hexagonal Architecture` (port/adapter).

:hammer_and_wrench: The domain entities are:

* `User`
* `User Balance`
* `Bank Branch`

Entities have the port to comunicate with the infrastructure and application layers:

* `UserRepositoryInterface`
* `UserBalanceRepositoryInterface`
* `BankBranchRepositoryInterface`
* `StatsRepositoryInterface`

The repositories implement the interfaces to ensure that contract between domain and business logic should be
respected.

There are the following entry points of the application:

```
index:
path: /
controller: SimpleBank\Controller\MainController::index

bank_branches_list:
path: /bank_branches
controller: SimpleBank\Controller\BankBranchController::list

bank_branches_add:
path: /bank_branches/add
controller: SimpleBank\Controller\BankBranchController::add

users_list:
path: /users
controller: SimpleBank\Controller\UserController::list

users_bank_branches_add:
path: /users/bank_branches/{bankBranchId}
controller: SimpleBank\Controller\UserController::add

users_show:
path: /users/{userId}
controller: SimpleBank\Controller\UserController::show

stats:
path: /stats
controller: SimpleBank\Controller\StatsController::stats

wire_transfer:
path: /users/wire_transfer/{userId}
controller: SimpleBank\Controller\UserController::wire
```

For every use case, each controller communicates with the domain through
the Application services. The information collected from the HTTP request
is passed to a DTO and then to a Services.

Every service was resolved by dependency injection.

The persistence implementation was created without the ORM but using
a database abstraction layer (`DBAL`).

The tests cover the application services use cases.


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

Docker-compose (https://docs.docker.com/compose/install/)

```
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
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
$ cd simple-bank/docker/
$ docker-compose -f docker-compose.dev.yml up --build
```
Run php migration:

```bash
$ cd simple-bank/
$ php bin/console doctrine:migrations:migrate
```
Note: if PDO drivers are missed execute:
```
sudo apt-get install php-mysql
```

Install Symfony client:

```
wget https://get.symfony.com/cli/installer -O - | bash
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

Testing:

```
cd simple-bank/
$ php ./vendor/bin/phpunit
```

Future Improvements:

```
[] Increase security layer with OAuth/JWT  
[] Use CQRS to split read/write operations
[] Using Command/Events
[] Move Wire transfers to be asynchronous
[] Implement a Wire transfer history for every wire transfer operations
[] Add Bus in order to manage asynchronous processes
[] Define real API contract
[] Add/Improve API response to use JSON
[] Improve UI/UX
[] Improve form validations
[] Improve server validations
[] Improve exceptions handler
[] Add monitoring
[] Use Value Object for Balance
[] Add Funcional tests
[] Add Controller tests
[] Add Integration tests
[] Add Aceptation tests
[] Using Fixtures for infrastructure tests
[] Use real web server like NGINX/Apache
[] Use docker to create PHP, web-server containers
[] More..
```

## License
Head Horse SL
