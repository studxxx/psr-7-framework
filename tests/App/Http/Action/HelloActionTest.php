<?php declare(strict_types=1);

namespace Tests\App\Http\Action;

use App\Http\Action\HelloAction;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Template\Php\PhpRenderer;
use Template\TemplateRenderer;
use Template\Twig\Extension\RouteExtension;
use Template\Twig\TwigRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\ServerRequest;

class HelloActionTest extends TestCase
{
    private TemplateRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $router = $this->createMock(Router::class);
        $loader = new FilesystemLoader();
        $loader->addPath('templates');
        $twig = new Environment($loader);
        $extension = new RouteExtension($router);
        $twig->addExtension($extension);
        $this->renderer = new TwigRenderer($twig, '.html.twig');
    }

    /**
     * @covers
     */
    public function testGuest(): void
    {
        $action = new HelloAction($this->renderer);

        $request = new ServerRequest();
        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsString('Hello!', $response->getBody()->getContents());
    }
}
