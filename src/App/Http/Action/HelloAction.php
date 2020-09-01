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

        return new HtmlResponse($this->render($name));
    }

    public function render($name): string
    {
        ob_start();
        require 'templates/hello.php';

        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
}
