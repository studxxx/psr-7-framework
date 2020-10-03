<?php

declare(strict_types=1);

namespace App\Http\Action\Blog;

use App\ReadModel\Pagination;
use App\ReadModel\PostReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class IndexAction
{
    private const PER_PAGE = 5;
    private PostReadRepository $posts;
    private TemplateRenderer $template;

    public function __construct(PostReadRepository $posts, TemplateRenderer $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $pager = new Pagination(
            $this->posts->countAll(),
            (int)$request->getAttribute('page', 1),
            self::PER_PAGE
        );

        return new HtmlResponse($this->template->render('app/blog/index', [
            'posts' => $this->posts->all($pager->getOffset(), $pager->getLimit()),
            'pager' => $pager,
        ]));
    }
}
