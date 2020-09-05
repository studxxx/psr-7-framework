<?php declare(strict_types=1);

namespace App\Http\Action\Blog;

use App\ReadModel\PostReadRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class ShowAction
{
    private PostReadRepository $posts;
    private TemplateRenderer $template;

    public function __construct(PostReadRepository $posts, TemplateRenderer $template)
    {
        $this->posts = $posts;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        if (!$post = $this->posts->find($request->getAttribute('id'))) {
            return $next($request);
        }
        return new HtmlResponse($this->template->render('app/blog/show', [
            'post' => $post,
        ]));
    }
}
