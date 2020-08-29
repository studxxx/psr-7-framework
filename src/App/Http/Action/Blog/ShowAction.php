<?php declare(strict_types=1);

namespace App\Http\Action\Blog;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ShowAction
{
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        $id = $request->getAttribute('id');
        if ($id > 2) {
            return $next($request);
        }
        return new JsonResponse(['id' => $id, 'title' => "Post #$id"]);
    }
}
