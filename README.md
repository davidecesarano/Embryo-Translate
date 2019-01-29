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

## Example
You may quickly test this using the built-in PHP server going to http://localhost:8000.
```
$ cd example
$ php -S localhost:8000
```

## Usage
### Create and set messages from files
You may store language strings in files within, for example, lang directory. Within this directory there should be a subdirectory for each language supported by the application:
```
/lang
    /en
        messages.php
    /it
        messages.php
```
All language files return an array of keyed strings. For example:
```php
return [
    'hello' => 'Hello World!'
];
```
Create a `Translate` object and pass it the language directory path and the default language. The `setMessages` method creates an array with all messages.
```php
$translate = new Translate('/path/lang', 'en');
$translate->setMessages();
```

### Set the locale
Store the default language in `Embryo\Translate\Middleware\SetLocaleMiddleware`. You may also change the active language at runtime using the query parameter in the uri:
```php
//...
$middleware = new RequestHandler;

// session
$middleware->add(
    (new SessionMiddleware)
        ->setSession(new Session)
        ->setOptions([
            'use_cookies'      => false,
            'use_only_cookies' => true
        ])
);

// set locale
$middleware->add(
    (new SetLocaleMiddleware)
        ->setLanguage('en')
        ->setSessionRequestAttrbiute('session')
        ->setLanguageQueryParam('language')
        ->setSessionKey('language')
);
//...
```
The middleware stored the language value in session. For to change it, you may use `language` query parameter in uri:
```
example.com/hello-world?language=it
```

### Determining the current locale
You may use the session to determine the current locale:
```php
$session = $request->getAttribute('session');
return $session->get('language');
```

### Retriving translation strings
First, you must use the `getMessages` method to determine the current language messages. Later, you may retrieve lines from language files using the `get` method:
```php
$session = $request->getAttribute('session');
$language = $session->get('language');
$messages = $translate->getMessages($language);
echo $messages->get('hello'); // Hello World!
```

### Replacing parameters in translation strings
You may define placeholders in your translation strings. All placeholders are prefixed with `{` and `}`. For example:
```php
'hello' => 'Hello {name}'
```
You may replace `{name}` with an array of replacements as the second argument to the `get` method:
```php
echo $messages->get('hello', ['name' => 'David']); // Hello David
```