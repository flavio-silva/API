<?php

require __DIR__ . '/../bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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

$app['product.form'] = function (\Silex\Application $app) {
  return $app['form.factory']
        ->createBuilder()
        ->add('id', 'hidden')
        ->add('name', 'text', ['required' => true])
        ->add('description', 'text', ['required' => true])
        ->add('value', 'text', ['required' => true])
        ->getForm();
};

$client = $app['controllers_factory'];

$client->get('/clientes', function(\Silex\Application $app) use($clientes) {
    return $app->json($clientes);
});

$app->get('/', function (Application $app){
   return $app['twig']->render('index.twig');
})->bind('home');

$product = $app['controllers_factory'];

$product->get('/', function (\Silex\Application $app) {
    return $app['twig']->render('products.twig', [
        'products' => $app['product.service']->findAll()
    ]);
})->bind('list');

$product->get('new', function (Application $app){
    return $app['twig']->render('create.twig', [
        'form' => $app['product.form']->createView()
    ]);
})->bind('new');

$product->get('edit/{id}', function (Application $app, $id) {
    $product = $app['product.service']->findBy($id);
    $form = $app['product.form'];
    $data = $product->toArray();
    $numberFormatter = new \NumberFormatter('pt_BR', \NumberFormatter::DECIMAL);    
    $data['value'] = $numberFormatter->format($data['value']);
    $form->setData($data);
    
    return $app['twig']->render('edit.twig', [
        'form' => $form->createView()
    ]);
})->bind('edit');

$product->post('edit', function (Application $app, Request $request) {
    $app['product.service']->save($request->get('form'));
    return $app->redirect('/product');
})->bind('save');

$product->get('delete/{id}', function (\Silex\Application $app, $id) {
    
    $app['product.service']->delete($id);
    return $app->redirect('/product');
})->bind('delete');

$products = $app['controllers_factory'];

$products->get('/', function (Application $app) {
    foreach($app['product.service']->findAll() as $product) {
        $data[$product->getId()] = $product->toArray();
    }
    
    return $app->json($data);
});

$products->get('/{id}', function (Application $app, $id){
    $product = $app['product.service']->findBy($id);
    return $app->json($product->toArray());
});

$products->post('/', function (Application $app, Request $request){
   $data['name'] = $request->get('name');
   $data['description'] = $request->get('description');
   $data['value'] = $request->get('value');
   $product = $app['product.service']->save($data);
   
   return $app->json($product->toArray());
});

/*@var $app \Silex\Application*/
$app->mount('/product', $product);
$app->mount('/client', $client);
$app->mount('/products', $products);
$app->run();