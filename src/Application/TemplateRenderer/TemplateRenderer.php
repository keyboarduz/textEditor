<?php


namespace App\Application\TemplateRenderer;


class TemplateRenderer
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param $view
     * @param array $params
     * @return string
     */
    public function render($view, array $params = []): string
    {
        $templateFile = $this->path . '/' . $view . '.php';

        ob_start();
        extract($params, EXTR_SKIP);
        require $templateFile;
        return ob_get_clean();
    }
}