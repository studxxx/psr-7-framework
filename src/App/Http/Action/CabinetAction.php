<?php declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse('I am logged in as ' . $request->getAttribute('username'));
    }
}
