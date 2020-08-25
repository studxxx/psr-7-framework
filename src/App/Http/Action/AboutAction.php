<?php declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

class AboutAction
{
    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse('I am a simple site.');
    }
}
