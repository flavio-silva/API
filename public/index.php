<?php

require __DIR__ . '/../bootstrap.php';

use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

$app['pdo'] = function() {
   return new \PDO('mysql:host=localhost;dbname=api', 'root', 'root'); 
};

$app['em'] = $em;

$app['product.service'] = function (\Silex\Application $app) {
    return new \API\Service\ProductService($app['em'], 'API\Entity\Product');
};

$app['category.service'] = function(Application $app){
    return new \API\Service\CategoryService($app['em'], 'API\Entity\Category');
};

$app['tag.service'] = function(Application $app){
    return new \API\Service\TagService($app['em'], 'API\Entity\Tag');
};

$app['category.constraint'] = new Assert\Collection([
            'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])],
    ]);
$app['product.constraint'] = $constraint = new Assert\Collection([
            'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 100])],
            'description' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])],
            'value' => [new Assert\NotBlank(), new Assert\Regex('/^[1-9]{1}[0-9]*,[0-9]{2}$/')],
            'category' => [new Assert\NotBlank(), new Assert\Regex('/^\d+$/')]
    ]);

$app['tag.constraint'] = new Assert\Collection([
        'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])]
    ]);

$app['product.form'] = function (\Silex\Application $app) {
    
    $categories = [];
    
    foreach($app['category.service']->findAll() as $category) {
        $categories[$category->getId()] = $category->getName();
    }
    
    return $app['form.factory']
        ->createBuilder()
        ->add('id', 'hidden')
        ->add('name', 'text', ['required' => true])
        ->add('description', 'text', ['required' => true])
        ->add('value', 'text', ['required' => true])
        ->add('category', 'choice', [
            'choices' => $categories
        ])
        ->getForm();
};

$app->get('/', function (Application $app){
   return $app['twig']->render('index.twig');
})->bind('home');

$product = $app['controllers_factory'];

$app['products.controller'] = $app->share(function(Application $app) {
    return new \API\Controller\ProductsController($app, $app['request']);
});

$app->get('/products/page/{page}', "products.controller:index")
    ->bind('list')
    ->value('page', 1);

$app->get('/products/new', "products.controller:newP")
    ->bind('new');

$app->get('/products/edit/{id}', 'products.controller:edit')
    ->bind('edit');

$app->post('/products/edit', 'products.controller:save')
    ->bind('save');

$app->get('/products/delete/{id}', 'products.controller:delete')
    ->bind('delete');

// API Restfull
$app->get('/{controller}', 'API\Controller\Loader::getController');
$app->get('/{controller}/{id}', 'API\Controller\Loader::getController');
$app->post('/{controller}', 'API\Controller\Loader::getController');
$app->delete('/{controller}/{id}', 'API\Controller\Loader::getController');
$app->put('/{controller}/{id}', 'API\Controller\Loader::getController');

$app->mount('/products', $product);
$app->run();