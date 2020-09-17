<?php declare(strict_types=1);

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\ShowAction;
use App\ReadModel\PostReadRepository;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Template\TemplateRenderer;
use Template\Twig\Extension\RouteExtension;
use Template\Twig\TwigRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zend\Diactoros\ServerRequest;

class ShowActionTest extends TestCase
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
    public function testSuccess(): void
    {
        $action = new ShowAction(new PostReadRepository(), $this->renderer);

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 2);

        $response = $action($request);

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsString('The Second Post', $response->getBody()->getContents());
//        self::assertJsonStringEqualsJsonString(
//            json_encode(['id' => $id, 'title' => 'Post #' . $id]),
//            $response->getBody()->getContents()
//        );
    }

    /**
     * @covers
     */
    public function testNotFound(): void
    {
        $action = new ShowAction(new PostReadRepository(), $this->renderer);

        $request = (new ServerRequest())
            ->withAttribute('id', $id = 10);

        $response = $action($request);

        self::assertEquals(404, $response->getStatusCode());
        self::assertStringContainsString('', $response->getBody()->getContents());
    }
}