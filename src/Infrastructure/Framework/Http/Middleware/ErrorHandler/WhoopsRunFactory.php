<?php declare(strict_types=1);

namespace Infrastructure\Framework\Http\Middleware\ErrorHandler;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsRunFactory
{
    public function __invoke(): Run
    {
        $whoops = new Run();
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
        return $whoops;
    }
}
