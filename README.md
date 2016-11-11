#Heap PHP Client
PHP library for sending data through Heap API.

##Installation
You can install it using Composer:
```
composer install iagomelanias/heap-php
```

##Track Events
```php
$heap = new \Heap\Client('APP_ID');
$heap->track('Paid Order', 'example@example.com');
```

##Add User Properties
```php
$heap = new \Heap\Client('APP_ID');
$heap->addUserProperties('example@example.com', array(
    'profession' => 'Scientist',
));
```