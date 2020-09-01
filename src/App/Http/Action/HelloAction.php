<?php declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HelloAction
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse($this->render('hello', $name));
    }

    public function render($view, $name): string
    {
        ob_start();
        require 'templates/' . $view . '.php';

        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
}
