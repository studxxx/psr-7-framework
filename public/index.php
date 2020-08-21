<?php declare(strict_types=1);

use Framework\Http\Request;

chdir(dirname(__DIR__));

require 'src/Framework/Http/Request.php';

### Initialization

$request = new Request();

### Action

$name = $request->getQueryParams()['name'] ?? 'Guest';
header('X-Developer: studxxx');
echo 'Hello, ' . $name . '!';