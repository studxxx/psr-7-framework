<?php declare(strict_types=1);

namespace Framework\Container;

class ServiceNotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{

}