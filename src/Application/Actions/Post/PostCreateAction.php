<?php


namespace App\Application\Actions\Post;


use App\Application\Actions\Action;
use App\Domain\Post\Post;
use App\Infrastructure\Persistence\Post\DbPostRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Application\TemplateRenderer\TemplateRenderer;

class PostCreateAction extends Action
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
        $textData = $postData['textInput'] ?: null;

        $newPost = new Post(null, $textData);

        if ($method === 'POST' && $newPost->validate()) {
            if ($this->postRepository->save($newPost)) {

                // ... flash message

                return $this->response
                    ->withStatus(302)
                    ->withHeader('Location', '/');
            }
        }

        $this->response->getBody()->write($this->templateRenderer->render('create', [
            'newPost' => $newPost,
        ]));
        return $this->response;
    }
}