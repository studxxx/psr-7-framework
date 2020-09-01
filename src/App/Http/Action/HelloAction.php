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

        return new HtmlResponse($this->render('hello', [
            'name' => $name
        ]));
    }

    public function render($view, array $params = []): string
    {
        $templateFile = 'templates/' . $view . '.php';
        extract($params, EXTR_OVERWRITE);

        ob_start();
        require $templateFile;

        return ob_get_clean();
    }
}
