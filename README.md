#Heap PHP Client
Library for sending data through Heap API.

##Installation
You can install it using Composer:
```
composer install iagomelanias/heap-php
```

##Track Events
```
$heap = new \Heap\Client('APP_ID');
$heap->track('Paid Order', 'example@example.com');
```

##Add User Properties
```
$heap = new \Heap\Client('APP_ID');
$heap->addUserProperties('example@example.com', array(
    'profession' => 'Scientist',
));
```