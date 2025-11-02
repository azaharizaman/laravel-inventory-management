# GitHub Copilot Instructions

This document provides instructions for the GitHub Copilot coding agent to work efficiently with this repository. Please trust these instructions and only resort to exploration if the information here is incomplete or incorrect. 

## High-Level Details

### Repository Summary

This repository, `azaharizaman/laravel-inventory-management`, is a Laravel package designed for inventory management. It is a "headless" and "contract-driven" package, which means it provides the backend logic and API for inventory tracking, stock movements, and valuation without a built-in user interface.

The package relies on two other key packages:
- `azaharizaman/laravel-backoffice`: For managing organizational structures like companies, offices, and staff.
- `azaharizaman/laravel-uom-management`: For handling units of measure and conversions.

The `src` directory is currently empty, so the agent will be responsible for creating the initial source code and tests.

The `PRD.md` file contains the product requirements document, which outlines the features and specifications for the package. The `docs/` directory is intended for further documentation.

### Repository Information

- **Project Type**: Laravel Package
- **Languages**: PHP
- **Frameworks**: Laravel
- **Target Runtimes**: PHP 8.2+, Laravel 10.0+

## Build and Validation Instructions

### Environment Setup and Dependency Installation

1.  **Install PHP Dependencies**: The project uses Composer to manage PHP dependencies. To install them, run the following command in the root of the repository:

    ```bash
    composer install
    ```

    **Note**: Always run `composer install` after pulling new changes to ensure all dependencies are up to date.

### Running Tests

The project uses PHPUnit for testing. To run the test suite, use the following command:

```bash
vendor/bin/phpunit
```

Since the `src` directory is currently empty, there are no tests. The agent is expected to create new tests for any new functionality and place them in a `tests` directory. A `phpunit.xml` configuration file may also need to be created.

A good practice observed in the dependencies is to have a `run-tests.sh` script to automate the test execution.

## Project Layout and Architecture

### Major Architectural Elements

-   **Source Code**: The main PHP source code for this package should be placed in the `src/` directory. The code should follow the PSR-4 autoloading standard, with the namespace `Azaharizaman\\LaravelInventoryManagement\\`.
-   **Dependencies**: PHP dependencies are managed by Composer and are located in the `vendor/` directory. The main dependencies are `azaharizaman/laravel-backoffice` and `azaharizaman/laravel-uom-management`.
-   **Configuration**: Laravel package configuration files should be placed in a `config/` directory.
-   **Tests**: Tests should be placed in a `tests/` directory at the root of the project.
-   **Documentation**: The `PRD.md` file contains the product requirements, and the `docs/` directory is intended for further documentation.

### Key Files

-   `composer.json`: Defines the project's metadata and dependencies.
-   `vendor/azaharizaman/laravel-backoffice/README.md`: Provides documentation for the backoffice package.
-   `vendor/azaharizaman/laravel-uom-management/README.md`: Provides documentation for the unit-of-measure management package.

### Validation and Checks

There are currently no continuous integration (CI) builds or other validation pipelines set up for this repository. The agent should ensure that any new code is accompanied by corresponding tests and that the test suite passes locally before creating a pull request.
