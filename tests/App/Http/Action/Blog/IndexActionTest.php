<?php declare(strict_types=1);

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use App\ReadModel\PostReadRepository;
use Framework\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use Template\Php\PhpRenderer;
use Template\TemplateRenderer;
use Template\Twig\Extension\RouteExtension;
use Template\Twig\TwigRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class IndexActionTest extends TestCase
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
        $action = new IndexAction(new PostReadRepository(), $this->renderer);
        $response = $action();

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsString('Blog', $response->getBody()->getContents());
//        self::assertJsonStringEqualsJsonString(
//            json_encode([
//                ['id' => 2, 'title' => 'The Second Post'],
//                ['id' => 1, 'title' => 'The First Post'],
//            ]),
//            $response->getBody()->getContents()
//        );
    }
}
