# Symfony Template

## Installation

### Dependencies

* __PHP >=5.4__ _(you can downgrade to PHP 5.3.3 as Symfony2's requirement, but seriously do you want it?)_
* __MySQL__ or __PostgreSQL__ or any other which is supported by Doctrine
* __NodeJS__

__Other tools:__

For handling and bulding assets 

### Basic configuration

__composer.json__

* change name node to your project name
* modify ```"SymfonyTemplate": "app/"``` to your application namespace eg.: ```"MyCoolApp": "app/"```

__bower.json__

* change name node to yout project name

__package.json__

* change name node to yout project name
* change license

### Database

    # Postgres users
    CREATE USER symfony_template WITH PASSWORD 'secret';
    CREATE DATABASE symfony_template_dev TEMPLATE=template0 LC_COLLATE='en_US.UTF-8' OWNER=symfony_template;
    CREATE DATABASE symfony_template_tes TEMPLATE=template0 LC_COLLATE='en_US.UTF-8' OWNER=symfony_template;

__!!! You might need to change locale to onw supported by your OS. List installed locales on UNIX like systems with ```locale -a``` !!!__

After that you need to create initial DB schema.

	# with migrations (recommended)
	app/console doctrine:migrations:diff
	app/console doctrine:migrations:migrate
	
	# without
	app/console doctrine:schema:create
	
And fill database with initial data.

	app/console doctrine:fixtures:load
	
## Testing

__Running tests:__

	bin/phpunit -c app/