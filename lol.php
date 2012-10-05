<?php
namespace Yilar\Test;

require_once __DIR__ . '/src/Yilar/Autoloader.php';
\Yilar\Autoloader::register();

require_once __DIR__ . '/test/fixture/User.php';

$u = new User(44242);

$u->name = 'Max Lols';

var_dump($u->toArray());


