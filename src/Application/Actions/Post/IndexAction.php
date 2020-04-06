<?php


namespace App\Application\Actions\Post;


use App\Application\Actions\Action;
use App\Application\TemplateRenderer\TemplateRenderer;
use App\Infrastructure\Persistence\Post\DbPostRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class IndexAction extends Action
{

    private $templateRenderer;
    private $postRepository;

    public function __construct(LoggerInterface $logger, DbPostRepository $postRepository, TemplateRenderer $templateRenderer)
    {
        parent::__construct($logger);
        $this->templateRenderer = $templateRenderer;
        $this->postRepository = $postRepository;
    }

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $posts = $this->postRepository->findAll();

        $this->response->getBody()->write($this->templateRenderer->render('index', [
            'posts' => $posts,
        ]));
        return $this->response;
    }
}