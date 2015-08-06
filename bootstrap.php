<?php
require __DIR__ . '/vendor/autoload.php';

$app = new \Silex\Application();
$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ .'/views'
]);
$app['debug'] = true;