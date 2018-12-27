# Embryo Translate
PSR compatible PHP library that provides a simple way to retrieve strings in various languages.

## Requirement
* PHP >= 7.1
* A [PSR-7](https://www.php-fig.org/psr/psr-7/) http message implementation and [PSR-17](https://www.php-fig.org/psr/psr-17/) http factory implementation (ex. [Embryo-Http](https://github.com/davidecesarano/Embryo-Http))
* A [PSR-15](https://www.php-fig.org/psr/psr-15/) http server request handlers implementation (ex. [Embryo-Middleware](https://github.com/davidecesarano/Embryo-Middleware))
* A PSR-15 session middleware (Ex. [Embryo-Session](https://github.com/davidecesarano/Embryo-Session))
* A PSR response emitter (ex. [Embryo-Emitter](https://github.com/davidecesarano/Embryo-Emitter))

## Installation
Using Composer:
```
$ composer require davidecesarano/embryo-translate
```
## Usage