# Cakeception
Cakeception is a DDD helper tool in testing CakePHP v2.* projects under Codeception. And yes, the project is still in active development and also can be used in your project.

## Documentation
You just need to put the two files which are `CakeCeption.php` and `AppController.php` inside of Codeception's tests folder, which we can name it with `cake`. You also need to make sure that your bootstrap file includes CakePHP's bootstrap file in order to use its internal core libraries.

```php
define('APP_DIR', 'app');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', getcwd());
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');
}

require CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php';

foreach(glob(ROOT . "/tests/cake/*.php") as $lib) {
    require $lib;
}
```

### Testing
Currently we can only use this tool to test controllers, and normally we would send an HTTP request to test the functionality of the controllers. In CakeCeption, it's simply just calling `$this->cakeception->request('controller/ActionName')`. In the `request` function, we already instantiated the controller along with its components and models. And you can simply use the controller object by calling `$this->cakeception->controller`.

#### Initializing
There are two ways to initialize controllers, you can either use `request` or `init`.

**Request**
```
$this->cakeception->request('Foo@bar');
//...
```

**Init**
```
$this->cakeception->init('FooController')
    ->call('bar')
//...
```
The only difference howsoever is the way their initialized, preferably if you're only calling the controller once you'd use `request`. But if you would initialize the controller to be used by multiple methods, then it `init` would suffice.

#### Headers
Since we're running Codeception in CGI, we simply don't have HTTP environment variables avaialble in the testing suite. In order to emulatean HTTP kind of environment, we simply need to define them using the `request` method. You can refer to PHP's [$_SERVER](http://php.net/manual/en/reserved.variables.server.php) to know what variables you can use.

```php
$controller = $this->cakception->request('Foo@bar')
    ->headers([
        'REQUEST_METHOD' => 'POST',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
    ]);
//...
```

In the example code above, we're trying to access the `foo` controller with `bar` as its action. And by the parameters we've provided in the `request` method. We're simply emulating a AJAX POST request. You can also check if the request being made is POST and AJAX by using the following methods below,

```php
$controller->controller->request->is('post');
$controller->controller->request->is('ajax');
```

#### Params
Params are for the `params` variable of the `CakeRequest` object. It contains system defined parameters.

```php
$controller = $this->cakeception->request('Foo@bar')
    ->headers([
        'REQUEST_METHOD' => 'POST',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
    ])
    ->params([
        'id' => 1
    ]);
//...
```

#### Queries
Queries are for the `query` variable of the `CakeRequest` object. They're the GET variables.

```php
$controller = $this->cakeception->request('Foo@bar')
    ->headers([
        'REQUEST_METHOD' => 'POST',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
    ])
    ->queries([
        'edit' => 'profile'
    ]);
//...
```

#### Data
Data is for the `query[data]` variable of the `CakeRequest` object. They're the POST variables.

```php
$controller = $this->cakeception->request('Foo@bar')
    ->headers([
        'REQUEST_METHOD' => 'POST',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
    ])
    ->queries([
        'edit' => 'profile'
    ])
    ->data([
        'Model' => [
            'someColum' => 'someValue'
        ]
    ]);
//...
```

#### Properties
For the love of OOP who doesn't define properties to be used all over their project. For that we can use the `properties` method.

```php
$controller = $this->cakeception->request('Foo@bar')
    ->headers([
        'REQUEST_METHOD' => 'POST',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
    ])
    ->queries([
        'edit' => 'profile'
    ])
    ->data([
        'Model' => [
            'someColum' => 'someValue'
        ]
    ])
    ->properties([
        'exampleProperty' => 'here be dragons!'
    ]);
//...
```

If you may be asking, what can define in the properties method? One answer, *ANYTHING*! It may vary from project to project, but the power of emulation doesn't stop there!


#### Executing
After you're satisfied in defining the needed variables/prerequisites. You can now execute the action by calling `execute`.

```php
$controller = $this->cakeception->request('Foo@bar')
    ->headers([
        'REQUEST_METHOD' => 'POST',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
    ])
    ->queries([
        'edit' => 'profile'
    ])
    ->data([
        'Model' => [
            'someColum' => 'someValue'
        ]
    ])
    ->properties([
        'exampleProperty' => 'here be dragons!'
    ])
    ->execute();
//...
```

It will return the current state of the controller after it's been processed with the variables you've provided. Afterwhich you can now assert almost anything your heart desires.
