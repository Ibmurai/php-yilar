<?php
namespace Yilar\Test;

require_once __DIR__ . '/src/Yilar/Autoloader.php';
\Yilar\Autoloader::register();

require_once __DIR__ . '/test/fixture/User.php';

$u = new User(44242);

$u->name = 42.0;
$u->age = 42;
$u->height = 2;
$u->fingerLengths = ['11', 16, 42.0];

var_dump($u->toArray());

$hello = '1.32';
var_dump((float)$hello);

var_dump(gettype($u));
