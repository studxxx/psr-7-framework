<?php

declare(strict_types=1);

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class IndexAction
{
    private PostReadRepository $posts;
    private TemplateRenderer $template;

    public function __construct(PostReadRepository $posts, TemplateRenderer $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $page = (int)$request->getAttribute('page', 1);

        $perPage = 3;
        $offset = ($page - 1) * $perPage;
        $limit = $perPage;
        $total = $this->posts->countAll();
        $count = ceil($total/$perPage);

        return new HtmlResponse($this->template->render('app/blog/index', [
            'posts' => $this->posts->getAll($offset, $limit),
            'page' => $page,
            'count' => $count,
        ]));
    }
}
