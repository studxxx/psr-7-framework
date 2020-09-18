<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

class UnknownMiddlewareTypeException extends \InvalidArgumentException
{
    /** @var mixed */
    private $type;

    public function __construct($type)
    {
        parent::__construct('Unknown middleware type.');
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
