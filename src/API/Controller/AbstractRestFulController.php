<?php

namespace API\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRestFulController implements RestFulInterface
{
    /**
     *
     * @var Application
     */
    protected $app;
    /**
     *
     * @var Request
     */
    protected $request;
    
    protected $service;
    
    protected $constraint;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }
    
    public function index()
    {
        $method = $this->request->getMethod();
        
        switch ($method) {
            case 'GET':
                return $this->get();
                
            case 'POST':
                return $this->post();
                
            case 'PUT':
                return $this->put();
                
            case 'DELETE':
                return $this->delete();
        }
    }
    
    public function delete()
    {
        $id = $this->request->get('id');
        
        if($id === null) {
            throw new \BadMethodCallException('Id was expected');
        }

        $this->app[$this->service]->delete($id);
        
        return $this->app->json([
                    'message' => "{$id} was deleted successfully"
        ]);
    }
    
    public function get()
    {
        $id = $this->request->get('id');

        if ($id !== null) {
            return $this->getId($id);
        }

        return $this->getAll();
    }
    
    protected function getAll()
    {
        $collection = $this->app[$this->service]->findAll();
        
        $toArray = [];

        foreach ($collection as $entity) {
            $toArray[$entity->getId()] = $entity->toArray();
        }

        return $this->app->json($toArray);
    }
    
    protected function getId($id)
    {
        $category = $this->app[$this->service]
                ->findBy($id)
                ->toArray();

        return $this->app->json($category);
    }
    
    public function post()
    {        
        $data = $this->request->request->getIterator()->getArrayCopy();
        
        $errors = $this->app['validator']->validateValue($data, $this->app[$this->constraint]);

        if ($errors->count() > 0) {
            $messages = [];

            /* @var $error Symfony\Component\Validator\ConstraintViolationInterface */
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->app->json($messages);
        }

        $category = $this->app[$this->service]->save($data);

        return $this->app->json([
                    'message' => "{$category->getId()} was inserted successfully"
        ]);
    }
    
    public function put()
    {
        
        $data = $this->request
            ->request
            ->getIterator()
            ->getArrayCopy();
        
        $id = $this->request->get('id');
        
        if($id === null) {
            throw new \BadMethodCallException('Id was expected');
        }
        
        $errors = $this->app['validator']->validateValue($data, $this->app[$this->constraint]);

        if ($errors->count() > 0) {
            $messages = [];

            /* @var $error Symfony\Component\Validator\ConstraintViolationInterface */
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->app->json($messages);
        }
        
        $data['id'] = $id;
        
        $this->app[$this->service]->save($data);

        return $this->app->json([
                    'message' => "Id {$data['id']} updated successfully"
        ]);
    }
    
    
}
