<?php

require __DIR__ . '/../bootstrap.php';

$clientes = [
	['nome' => 'Flavio', 'email' => 'flv.alves@gmail.com', 'cpf' => '999.999.999-99'],
	['nome' => 'Mayara', 'email' => 'mayarachagas2010@gmail.com', 'cpf' => '888.888.888-88'],
];

$app['pdo'] = function() {
   return new \PDO('mysql:host=localhost;dbname=api', 'root', 'root'); 
};


$app['product.entity'] = function () {
   return new \API\Entity\Product(); 
};

$app['product.dao'] = function (\Silex\Application $app) {
   return new \API\Mapper\ProductDAO($app['pdo']); 
};

$app['product.service'] = function (\Silex\Application $app) {
    return new \API\Service\ProductService($app['product.entity'], $app['product.dao']);    
};


$app->get('/clientes', function(\Silex\Application $app) use($clientes) {
    return $app->json($clientes);
});

$app->get('/products', function (\Silex\Application $app) {
    return $app['twig']->render('products.twig', [
        'products' => $app['product.service']->findAll()
    ]);
});

$app->run();