[![Packagist License](https://img.shields.io/packagist/l/f9webltd/laravel-api-response-helpers?style=flat-square)](https://packagist.org/packages/f9webltd/laravel-api-response-helpers)

Laravel Apiful is a fully customizable package for building API responses easily with many benefits.

# Table of Contents

- [Introduction](#introduction)
- [Installation](#installation)
- [Usage](#usage)
    - [Creating Success Responses](#creating-success-responses)
    - [Creating Error And Exception Responses](#creating-error-and-exception-responses)
    - [Creating Entity Responses](#creating-entity-responses)
    - [Creating Custom Responses](#creating-custom-responses)
    - [Pagination](#pagination)
    - [Adding Meta](#adding-meta)
    - [Ways To Use](#ways-to-use)
- [Contributing](#contributing)
- [License](#license)

# Introduction

Sometimes you need to send your API responses legally.

```php
public function index(Request $request)
{

    $orders = Order::all();

    return apiful()
        ->withMessage("Orders List")
        ->withData($orders)
        ->withStatusCode(200)
        ->withDecorator(...)
        ->withHeader([...]);

}
```

And the response always is like this:

```json
{
  "status": "success",
  "status_code": 200,
  "message": "Orders List",
  "data": [
    ...
  ],
  "meta": []
}
```

# Installation

```composer require prodemmi/laravel-apiful```

# Usage

Types of responses are:

- Success
- Error And Exception
- Entity
- Custom response uses [Decorators](#decorators)

# Creating Success Responses

### success( )

```php
  $orders = Order::all();

  return apiful($orders)->success();
  // Or
  return apiful()->withData($orders)->success();
```

### noContent( )

```php
  apiful()->noContent();
```

# Creating Error And Exception Responses

### error( mixed $errors = null )

```php
  apiful()->error($validationErrors);
```

### exception( Throwable $exception )

You could pass the exception to the apiful and will return a response based on ExceptionDecorator

```php
public function index(Request $request)
{

    try {
    
      return apiful()->success();
    
    }catch (\Exception $e){
    
      return apiful()->exception($e);
      //    Or
      return apiful($e);
    
    }

}
```
Response is :

```json
{
  "status": "error",
  "status_code": 500,
  "meta": [],
  "message": "This is exception message",
  "errors": {
    "trace": [...],
    "file": "ApiController.php",
    "line": 21
  }
}
```

You could set `trace => false` to disable exception tracing in `apiful.php` config file for security reasons.
Also, there are options for `line` and `file` reporting.

```php
'errors'     => [
    'trace' => false,
    'file'  => true,
    'line'  => true
]
```

### badRequest( )

```php
  apiful()->badRequest();
```

### unauthorized( )

```php
  apiful()->unauthorized();
```

### forbidden( )

```php
  apiful()->forbidden();
```

### internal( )

```php
  apiful()->internal();
```

### notAcceptable( )

```php
  apiful()->notAcceptable();
```

# Creating Entity Responses

Laravel apiful provide to send entity responses like created/deleted/updated models easily.

### created( )

```php
  apiful()->created();
```
Response is :

```json
  {
  "status": "success",
  "status_code": 201,
  "message": "Entity successfully created.",
  "data": {},
  "meta": []
}
```

### notCreated( )

```php
  apiful()->notCreated();
```

### updated( )

```php
  apiful()->updated();
```

### notUpdated( )

```php
  apiful()->notUpdated();
```

### deleted( )

```php
  apiful()->deleted();
```

### notDeleted( )

```php
  apiful()->notDeleted();
```

### notFound( )

```php
  apiful()->notFound();
```

### invalidQuery( )

```php
  apiful()->invalidQuery();
```

By default apiful uses entity messages in lang files. To change these messages you could publish apiful lang by this command:

```shell
php artisan vendor:publish --tag="apiful-lang"
```

# Creating Custom Responses

Creating your responses with apiful is so easy with decorators. First of all, run the artisan command to create a decorator

```shell
php artisan make:decorator MySuccessDecorator
```

The decorator will be created in `app/Http/Decorators/MySuccessDecorator.php`.You could return everything like:

```php

<?php

namespace App\Http\Decorators;


use Prodemmi\Apiful\ApifulDecorator;

class MySuccessDecorator implements ApifulDecorator
{

    public function toArray(array $response) : array
    {

        return [
          'success' => true,
          'message' => data_get($response, 'message', ''),
          'data'    => data_get($response, 'data', [])
        ];

    }

}
```

And after that, you should change the success decoration in the config file:

```php
'decorators' => [
    'success'            => \Prodemmi\Apiful\Decorators\MySuccessDecorator::class, // Changed
    'error'              => \Prodemmi\Apiful\Decorators\ErrorDecorator::class,
    'exception'          => \Prodemmi\Apiful\Decorators\ExceptionDecorator::class,
    'pagination'         => \Prodemmi\Apiful\Decorators\PaginationDecorator::class
]
```

You could change decorations for error, exception, pagination, or add new decorations:

```php
'decorators' => [
    'success'            => \Prodemmi\Apiful\Decorators\SuccessDecorator::class,
    'error'              => \Prodemmi\Apiful\Decorators\ErrorDecorator::class,
    'exception'          => \Prodemmi\Apiful\Decorators\ExceptionDecorator::class,
    'pagination'         => \Prodemmi\Apiful\Decorators\PaginationDecorator::class,
    'new_decorator'      => \Prodemmi\Apiful\Decorators\NewDecorator::class //  New decoration
]
```

And use it like this:

```php
  return apiful()->withDecorator('new_decorator')->withData($data);
  // Or
  return apiful()->withDecorator(NewDecorator::class)->withData($data);
```

# Pagination

Maybe you want to respond a paginated data and like to have entity data and pagination data separated.`withPagination( )` method is for this:

```php
  return apiful($users)->withPagination();
```

```json
{
  "status": "success",
  "status_code": 200,
  "message": "OK",
  "data": [...],
  "pagination": ... // Pagination links, current_page, ...
}
```

# Adding Meta

Sometimes you want to send additional information along with the data. For example, in dynamic forms. Meta comes into play here:

### withMeta( array|Closure $meta )
```php
  return apiful($user)->withMeta(['labels' => $labels, 'default_values' => $defaultValues]);
```

```json
{
  "status": "success",
  "status_code": 200,
  "message": "OK",
  "data": [...],
  "meta": {
    "labels": {
      "username": "Username",
      "first_name": "First Name",
      "last_name": "Last Name",
      ...
    },
    "default_values": {
      "first_name": "Dear"
    }
  }
}
```

# Ways To Use

### Use with `apiful()` helper
```php
public function index(Request $request)
{

    return apiful($data);

}
```

### Use with `Apiful` facade
```php
public function index(Request $request)
{

    return Apiful::withData($data)->success();

}
```

### Use with `$request->apiful()`
```php
public function index(Request $request)
{

    return $request->apiful($data)->success();

}
```

You could use apiful In conditional statements:

```php
public function index(Request $request)
{

    $apiful = apiful();

    $orders = Order::all();
    
    if(...){
        //  ...
        $apiful->withData([...]);
    }elseif(...){
        //  ...
        $apiful->withData([...]);
    }else{

        $apiful->clearData();
        
    }

    return $apiful;

}
```

# Contributing

Feel free to share your ideas or submit an issue just please create a pull request on Github.

# License

The MIT License (MIT). Please see License File for more information. See [license.md](license.md) for more details.
