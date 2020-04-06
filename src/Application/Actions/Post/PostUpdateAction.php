<?php


namespace App\Application\Actions\Post;


use App\Application\Actions\Action;
use App\Application\TemplateRenderer\TemplateRenderer;
use App\Infrastructure\Persistence\Post\DbPostRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class PostUpdateAction extends Action
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
        $method = $this->request->getMethod();
        $postData = $this->request->getParsedBody();
        $id = $this->args['id'];

        $post = $this->postRepository->findPostById($id);

        if ($post === null) {
            return $this->response->withStatus(404);
        }

        if ($method === 'POST') {
            $post->setContent($postData['textInput'] ?: null);
            if ($post->validate() && $this->postRepository->update($id, $post)) {

                // ... flash message

                return $this->response->withStatus(302)->withHeader('Location', '/');
            }
        }

        $this->response->getBody()->write($this->templateRenderer->render('update', [
            'post' => $post,
        ]));
        return $this->response;
    }
}