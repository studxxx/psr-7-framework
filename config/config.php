<?php declare(strict_types=1);

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/autoload/{{,*.}global,{,*.}local}.php'),
]);

return $aggregator->getMergedConfig();
