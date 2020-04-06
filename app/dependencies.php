<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Application\TemplateRenderer\TemplateRenderer;
use App\Application\Actions\Post\UploadImageAction;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        TemplateRenderer::class => function(ContainerInterface $c) {
            return new TemplateRenderer(__DIR__ . '/../templates');
        },
        UploadImageAction::class => function(ContainerInterface $c) {
            return new UploadImageAction($c->get('upload_directory'), $c->get(LoggerInterface::class));
        },
        \PDO::class => function (ContainerInterface $c) {
            return new \PDO(
                $c->get('settings')['db']['dsn'],
                $c->get('settings')['db']['username'],
                $c->get('settings')['db']['password']
            );
        }
    ]);
};
