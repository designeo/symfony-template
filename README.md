# Symfony Template

## Installation

### Dependencies

* __PHP >=5.4__
* __PostgreSQL__
* __NodeJS__

### Database

    # Postgres users
    CREATE USER symfony_template WITH PASSWORD 'secret';
    CREATE DATABASE symfony_template_dev TEMPLATE=template0 LC_COLLATE='en_US.UTF-8' OWNER=symfony_template;
    CREATE DATABASE symfony_template_test TEMPLATE=template0 LC_COLLATE='en_US.UTF-8' OWNER=symfony_template;

__!!! You might need to change locale to onw supported by your OS. List installed locales on UNIX like systems with ```locale -a``` !!!__

After that you need to create initial DB schema.

	# with migrations (recommended)
	app/console doctrine:migrations:migrate
	
	# without (But you want to run migration, you want to run migration from your colleagues later)
	app/console doctrine:schema:create
	
And fill database with initial data.

	app/console doctrine:fixtures:load
	
### Usefull commands:

__Build-in server__

* Run local server:

		app/console server:run

* Run local server with debug:

		app/console server:run -vvv

* Run local server in product env with debug:

		app/console server:run --env=prod -vvv

* Run local server for public acces from another device (eg. mobile phone):

		app/console server:run 0.0.0.0:8001
		
* Stop server running on background:

		app/console server:stop 0.0.0.0:8001
		
__Router debug__

		app/console d:r --show-controllers
		app/console debug:router /your-route

__Migrations__

* Change properties in entity
* Run diff for create SQL diff:

		app/console doctrine:migrations:diff
	
* Check migration created in AppBundle/Migrations. You can make changes in SQL.
* Remove SQL which is generated each time - eg. SEQUENCE
* Save migration file
* Run migration to your database:

		app/console doctrine:migrations:migrate


* Append fixures:

		app/console d:f:l --append

* Rollback migration:

		app/console d:m:e --down 20150723104243 # 20150723104243 is migration you want to rollback

__Schema check__

* To see, if you have any changes at entities, which are not in your database:

		app/console d:s:u --dump-sql

## Styles + JS

	npm install
	bower install
	gulp build (gulp for CSS and JS watcher)
	
## Testing

__Running tests:__

	bin/phpunit -c app/

## I18n routes

1) Create a translation file for routes:
    ```app/console translation:extract  -c routing <locale>```

2) Translate newly generated file app/Resources/translations/routing.<locale>.yml
3) Enjoy a happy life.

There is also the update version of translation command: `app/console translation:update`,
however it probably can not fully utilize preconfigured settings from config.yml

[Documentation](https://github.com/schmittjoh/JMSI18nRoutingBundle)

## Designeo framework

[Documentation](src/Designeo/FrameworkBundle/README.md)

## CRUD generator

[Documentation](src/Designeo/GeneratorBundle/README.md)

## Dump

[Documentation](src/Designeo/DumpBundle/README.md)
