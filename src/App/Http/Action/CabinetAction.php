<?php declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;

class CabinetAction
{
    private array $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if (empty($username) || empty($password)) {
            return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
        }

        foreach ($this->users as $name => $pass) {
            if ($username === $name && $password === $pass) {
                return new HtmlResponse('I am logged in as ' . $username);
            }
        }

        return new EmptyResponse(401, ['WWW-Authenticate' => 'Basic realm=Restricted area']);
    }
}
