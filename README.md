**Kecik DIC (Dependency Injection Container)**
================

## Cara Installasi
file composer.json
```json
{
	"require": {
		"kecik/dic": "1.0.*@dev""
	}
}
```

Jalankan perintah
```shell
composer install
```

Pustaka/Library ini merupakan pustaka pendukung untuk mendukung masalah Dependency Injection Container jadi dengan pustaka ini, maka sebuah object, controller, model atau bisa juga Pustaka/Library dapat hanya akan di load satu kali saja, sehingga tidak menimbulkan pemborosan. Pustaka ini juga akan langsung di implementasi oleh Kecik Framework ketika Pustaka/Library ini terdeteksi/dikenali oleh Kecik Framework

## Contoh penggunaan
```php
<?php
require "vendor/autoload.php";

$app = new Kecik\Kecik();

class Test1 {
	public function __construct() {
		echo "Hello is Test1 <br />";
	}
}

class Test2 {
	public function __construct(Test1 $test) {
		echo "Hello is Test2 <br />";
	}

	public function index() {
		echo 'Is Index';
	}
}

$app->container['Test1'] = function($c) {
	return new Test1();
};

$app->container['Test2'] = function($c) {
	return new Test2($c['Test1']);
};

$app->container['Test3'] = $app->container->factory(function($c) {
	return new Test2($c['Test1']);
});

$app->get('/', function() use ($app){
	$app->container['Test1'];
	$app->container['Test2']->index();
});

$app->run();
?>
```
atau untuk Controller

> WelcomeController
> 
> file:  **welcome.php**

```php
<?php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller {
	public function __construct() {
		echo 'Hello is Welcome Controller';
	}
}
```

> HelloController
> 
> file: **hello**

```php
<?php
namespace Controller;

use Kecik\Controller;

class Hello extends Controller {
	public function __construct(Welcome $welcome) {
		echo 'Hello is Hello Controller';
	}
	public function say($name) {
		echo "Hello, $name";
	}
}
```

> Index
> 
> file: **index.php**

```php
<?php
require "vendor/autoload.php";

$app = new Kecik\Kecik();

$app->container['WelcomeController'] = function($c) {
	return new Controller\Welcome();
};

$app->container['HelloController'] = function($c) {
	return new Controller\Hello($c['WelcomeController']);
};

$app->get('/', function() use ($app){
	$app->container['WelcomeController'];
});

$app->get('hell/:name', function($name) use ($app) {
	$app->container['HelloController']->say($name);
});
$app->run();
?>
```
