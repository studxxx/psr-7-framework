<?php declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    public function __invoke(ServerRequestInterface $request)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if ($username === 'admin' && $password === 'password') {
            return new HtmlResponse('I am logged in as ' . $username);
        }

        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
    }
}
