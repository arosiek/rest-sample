# Task Management System

## Introduction

## Installation
* [x] ```wget https://get.symfony.com/cli/installer -O - | bash```
* [x] ```mv ~/.symfony5/bin/symfony /usr/local/bin/symfony```
* [x] ```symfony composer install && symfony serve -d```
* [x] ``````
* [x] ```symfony console doctrine:database:create```
* [x] ```symfony console doctrine:schema:create```
* [x] ``````
* [x] ``````

### GitHooks / Static test in pre-commit
You may use `.githooks` directory to have pre-commit actions like validate schema directory and php coding standards (PHP-CodeSniffer and phpstan).
It will automatically trigger checking on committed files and it will use `php-cbf` to fix it if necessary.

To include `.githooks` directory in the repository use command:

```bash
git config core.hooksPath .githooks
```


## Static or unit tests:

```bash
symfony composer phpcs-check      #to run phpcbf fix + phpcs test
```
```bash
symfony composer phpstan-check    #to run phpstan analyse
```
```bash
symfony composer phpunit-check    #to run unit/functional tests
```
```bash
symfony composer analyse          #to run all above
```

## RESTful API Documentation

## Comment

