<?php

require __DIR__ . '/../bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

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


// API Restfull

$app->post('/products', function (Application $app, Request $request){
	$data['name'] = $request->get('name');
	$data['description'] = $request->get('description');
	$data['value'] = $request->get('value');
        
        $constraint = new Assert\Collection([
            'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 100])],
            'description' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])],
            'value' => [new Assert\NotBlank(), new Assert\Regex('/^[1-9]{1}[0-9]*,[0-9]{2}$/')]
        ]);
        
        $errors = $app['validator']->validateValue($data, $constraint);
        
        if($errors->count() > 0) {
            $messages = [];
            
            /*@var $error Symfony\Component\Validator\ConstraintViolationInterface */
            foreach($errors as $error) {
                $messages[$error->getPropertyPath()] =$error->getMessage();
            }
            
            return $app->json($messages);
        }
        
        $product = $app['product.service']->save($data);
        return $app->json([
            'message' => "Product {$product->getId()} was inserted successfully"
        ]);
	
});

$app->get('/products', function (Application $app) {
    foreach($app['product.service']->findAll() as $product) {
        $data[$product->getId()] = $product->toArray();
    }
    
    return $app->json($data);
});

$app->get('/products/{id}', function (Application $app, $id){
    $product = $app['product.service']->findBy($id);
    
    if($product) {
        return $app->json($product->toArray());
    }
    
    return $app->json([
       'error' => "Cannot find by id {$id}"
    ]);
})->assert('id', '\d+');


$app->delete('/products/{id}', function(Application $app, $id) {
    $app['product.service']->delete($id);
    
        return $app->json([
            'message' => "Product {$id} was deleted successfully"
        ]);
});

$app->put('/products/{id}', function (Application $app, Request $request, $id){
    
    $data['name'] = $request->request->get('name');
	$data['description'] = $request->request->get('description');
	$data['value'] = $request->request->get('value');
    $data['id'] = $id;
    
    $constraint = new Assert\Collection([
        'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 100])],
        'description' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])],
        'value' => [new Assert\NotBlank(), new Assert\Regex('/^[1-9]{1}[0-9]*,[0-9]{2}$/')],
        'id' => [new Assert\NotBlank]
    ]);

    $errors = $app['validator']->validateValue($data, $constraint);

    if($errors->count() > 0) {
        $messages = [];

        /*@var $error Symfony\Component\Validator\ConstraintViolationInterface */
        foreach($errors as $error) {
            $messages[$error->getPropertyPath()] =$error->getMessage();
        }

        return $app->json($messages);
    }

    $app['product.service']->save($data);
    
    return $app->json([
        'message' => "Product {$id} updated successfully"
    ]); 
});

/*@var $app \Silex\Application*/
$app->mount('/product', $product);
$app->mount('/client', $client);
$app->run();