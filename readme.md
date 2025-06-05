# Task Management System

## Introduction

_Add a short project description here._

## Installation

1. Install Symfony CLI:
   ```bash
   wget https://get.symfony.com/cli/installer -O - | bash
   mv ~/.symfony5/bin/symfony /usr/local/bin/symfony
   ```

2. Install dependencies and start the development server:
   ```bash
   symfony composer install
   symfony serve -d
   ```

3. Create the database and schema:
   ```bash
   symfony console doctrine:database:create
   symfony console doctrine:schema:create
   ```

## Git Hooks / Static Test in Pre-commit

You can use the `.githooks` directory to enable pre-commit actions such as schema validation and PHP code style checks (PHP_CodeSniffer and PHPStan).  
These checks will be automatically triggered on staged files and `php-cbf` will be used to fix issues if needed.

To activate `.githooks` in the repository, run:

```bash
git config core.hooksPath .githooks
```

## Static and Unit Tests

Run the following commands:

```bash
symfony composer phpcs-check      # Run phpcbf fix + phpcs check
symfony composer phpstan-check    # Run phpstan analysis
symfony composer phpunit-check    # Run unit/functional tests
symfony composer analyse          # Run all of the above
```

## RESTful API Documentation

API documentation is available in the `swagger_docs.json` file or via:

[https://petstore.swagger.io/?url=https://raw.githubusercontent.com/arosiek/rest-sample/main/swagger_docs.json](https://petstore.swagger.io/?url=https://raw.githubusercontent.com/arosiek/rest-sample/main/swagger_docs.json)

## Comment

Requirement 2.4 referred to the PUT method. However, based on the description and RESTful standards, it should be PATCH.  
PUT replaces the entire resource with new data, while PATCH updates only specific fields.
