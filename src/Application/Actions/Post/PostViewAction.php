<?php


namespace App\Application\Actions\Post;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Post\DbPostRepository;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\TemplateRenderer\TemplateRenderer;
use Psr\Log\LoggerInterface;

class PostViewAction extends Action
{
    private $templateRenderer;
    private $postRepository;

    public function __construct(TemplateRenderer $templateRenderer, DbPostRepository $postRepository, LoggerInterface $logger)
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
        $id = (int) $this->args['id'];

        if (($post = $this->postRepository->findPostById($id)) === null) {
            return $this->response->withStatus(404, 'Not found');
        }


        $this->response->getBody()->write($this->templateRenderer->render('view', [
            'post' => $post,
        ]));
        return $this->response;
    }
}