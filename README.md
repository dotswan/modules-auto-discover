# Modules Auto-Discover

Automatically discover and register configurations, translations, and more from your [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules) modules.

## Table of Contents

*   [Introduction](#introduction)
*   [Installation](#installation)
*   [Usage](#usage)
    *   [Auto-Discovery](#auto-discovery)
    *   [Disabling Auto-Discovery per Module](#disabling-auto-discovery-per-module)
*   [Contributing](#contributing)
*   [License](#license)
*   [Support](#support)

## Introduction

When using the `nWidart/laravel-modules` package for modular Laravel applications, you might have noticed that configurations, translations, and other resources within your modules are not automatically discovered and registered by Laravel and you need to register/discover them in the `ModuleServiceProvider` for every module. This package bridges that gap by automatically discovering and registering these resources, simplifying your module development process.

With **Modules Auto-Discover**, you no longer need to manually call methods like `registerConfigs()` or `registerTranslationss()` in your module service providers. The package handles the discovery and registration automatically during the application's boot process.

## Installation

You can install the package via Composer:

```bash
composer require dotswan/modules-auto-discover
```

The package uses Laravel's auto-discovery feature, so no additional steps are required to register the service provider.

## Usage

Once installed, the package will automatically discover and register the following resources in your enabled modules:

*   **Configurations** (`Config/` directory)
*   **Translations** (`Lang/` directory)
*   **Views** (`Resources/views/` directory)
*   **Routes** (`Routes/` directory)
*   **Migrations** (`Database/Migrations/` directory)
*   **Factories** (`Database/Factories/` directory)
*   **Seeds** (`Database/Seeders/` directory)

### Auto-Discovery

By default, auto-discovery is enabled for all your modules. This means that any resources placed in the standard directories will be automatically registered without any additional configuration.

For example, placing a configuration file at `Modules/YourModule/Config/permission.php` will make its contents available via Laravel's `config()` helper:

```php
// Accessing a configuration value from your module
$value = config('permission.key');`
```
**Note:** `nWidart Modules` will create a `config.php` file for every module by default (if you enabled to generate), we recommend you to create a separate file for each configuration to avoid conflicts (don't use `config.php` for all modules)


### Disabling Auto-Discovery per Module

If you want to disable auto-discovery for a specific module, you can do so by adding an `"auto-discovery": false` entry to your module's `module.json` file:

```json
{ 
  "name": "YourModule",
  "alias": "yourmodule",
  ...
  "auto-discovery": false,
  ...
}
```
With `auto-discovery` set to `false`, the package will skip the automatic registration of resources for that module. You can then manually register resources in your module's service provider if needed.

## To-Do
- [x] Discover `Config`
- [x] Discover `Translations`
- [ ] Discover `Views`
- [ ] Discover `Routes`
- [ ] Discover `Commands`

## Contributing

Contributions are welcome and encouraged! Please follow these steps to contribute:

1.  Fork the repository on GitHub.
2.  Create a new branch for your feature or bug fix:

    ```bash
    git checkout -b feature/your-feature-name
    ```

3.  Make your changes.
4.  Run `composer lint` to format your code with Pint rules.
5.  Run `composer test` to run tests or `composer test-coverage` to generate a coverage report.
6.  Commit your changes with clear messages.
7.  Push your branch to your forked repository:

    ```bash
    git push origin feature/your-feature-name
    ```

8.  Open a pull request on the main repository.

Please make sure to write tests for your changes and ensure all existing tests pass.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

If you encounter any issues or have questions, please contact us via [tech@dotswan.com](mailto:tech@dotswan.com) or open an issue on the [GitHub repository](https://github.com/dotswan/modules-auto-discover/issues).