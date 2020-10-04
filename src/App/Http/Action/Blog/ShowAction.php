<?php

declare(strict_types=1);

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;

class ShowAction implements RequestHandlerInterface
{
    private PostReadRepository $posts;
    private TemplateRenderer $template;

    public function __construct(PostReadRepository $posts, TemplateRenderer $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$post = $this->posts->find((int)$request->getAttribute('id') ?? null)) {
            return new EmptyResponse(404);
        }
        return new HtmlResponse($this->template->render('app/blog/show', [
            'post' => $post,
        ]));
    }
}
