# Maestro

A light client built on [Guzzle](http://docs.guzzlephp.org/en/latest/) that simplifies the way you work with micro-services. It is based on method definitions and parameters for your URLs.

# Installation

Composer way

```
composer require marcus-campos/maestro
```

Or add manually to your composer.json:

```
"marcus-campos/maestro": "dev-master"
```

# Basic Usage

```php

<?php

namespace App\Platform\V1\Domains\App;



use Maestro\Rest;

class Products extends Rest
{
    protected $url = 'https://mydomain.com/api/v1/'; // http://mydomain.com:9000/api/v1

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this
            ->get()
            ->setEndPoint('products')
            ->send([
                'Content-Type' => 'application/json'
            ], [
                'ids' => [1,2,3,4,5]
            ])
            ->parse();
    }

    /**
     * @return array
     */
    public function getProduct()
    {
        return $this
            ->get()
            ->setEndPoint('product')
            ->send()
            ->parse();
    }

    /**
     * @return array
     */
    public function postNotification()
    {
        return $this
            ->get()
            ->setEndPoint('package/')
            ->sendAsync([
                'Content-Type' => 'application/json'
            ], [
                'message' => 'Tanks for all.',
                'id' => [1]
            ])
            ->wait()
            ->parse()
            ->name;
    }
}
```

# Response data

The master returns a `StdClass`, which gives you the freedom to treat the data the way you want. See the examples:


The way of Laravel

```php
 public function getProducts()
 {
     return collect($this
         ->get()
         ->setEndPoint('products')
         ->send([
             'Content-Type' => 'application/json'
         ], [
             'ids' => [1,2,3,4,5]
         ])
         ->parse());
 }
```

Other way
```php
 public function postNotification()
 {
     return $this
         ->get()
         ->setEndPoint('package/')
         ->sendAsync([
             'Content-Type' => 'application/json'
         ], [
             'message' => 'Tanks for all.',
             'id' => [1]
         ])
         ->wait()
         ->parse()
         ->name;
 }
```

## Senders
You can send in 2 ways: synchronous or asynchronous. See the examples:


Synchronous: `->send(array headers, array body)`
```php
 public function getProducts()
 {
     return collect($this
         ->get()
         ->setEndPoint('products')
         ->send([
             'Content-Type' => 'application/json'
         ], [
             'ids' => [1,2,3,4,5]
         ])
         ->parse());
 }
```

Asynchronous: `->sendAsync(array headers, array body)`
```php
 public function postNotification()
 {
     return $this
         ->get()
         ->setEndPoint('package/')
         ->sendAsync([
             'Content-Type' => 'application/json'
         ], [
             'message' => 'Tanks for all.',
             'id' => [1]
         ])
         ->wait()
         ->parse()
         ->name;
 }
```



## Supported Methods

- [x] GET    `->get()`
- [x] POST   `->post()`
- [x] PUT    `->put()`
- [x] PATCH  `->patch()`
- [x] DELETE `->delete()`


