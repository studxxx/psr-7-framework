<?php declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    private TemplateRenderer $template;

    public function __construct(TemplateRenderer $template)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return new HtmlResponse($this->template->render('app/cabinet', [
            'name' => $request->getAttribute(BasicAuthMiddleware::ATTRIBUTE)
        ]));
    }
}
