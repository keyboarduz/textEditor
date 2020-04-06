<?php


namespace App\Application\Actions\Post;


use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\UploadedFile;

class UploadImageAction extends Action
{
    /**
     * @var string
     */
    private $directory;

    public function __construct($directory, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->directory = $directory;
    }

    /**
     * @return Response
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $uploadedFiles = $this->request->getUploadedFiles();

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $uploadedFiles['imageFile'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $uploadedFile->moveTo(
                $this->request->getServerParams()['DOCUMENT_ROOT'] . $this->directory . '/' . $uploadedFile->getClientFilename()
            );
            $this->respondWithData([
                'location' => $this->directory . '/' . $filename->getClientFilename()
            ]);
        }

        return $this->response;
    }
}