<?php

namespace API\Controller;

class ProductsController extends AbstractController
{

    protected $service = 'product.service';
    protected $form = 'product.form';
    
    public function index()
    {
        $search = $this->request->get('search');
        $maxResults = 10;

        $page = $this->request->get('page');

        $offset = ($page - 1) * $maxResults;

        if ($search != null) {
            $products = new \LimitIterator($this->app[$this->service]->findByNameOrDescription($search), $offset, $maxResults);
        } else {
            $products = new \LimitIterator(new \ArrayIterator($this->app[$this->service]->findAll()), $offset, $maxResults);
        }

        $totalItems = count($products->getInnerIterator());
        $totalPages = ceil($totalItems / $maxResults);
        $firstPage = 1;
        $lastPage = $totalPages;

        $prevPage = $page - 1 <= $firstPage ? $firstPage : $page - 1;
        $nextPage = $page + 1 >= $lastPage ? $lastPage : $page + 1;

        return $this->app['twig']->render('products.twig', [
                    'products' => $products,
                    'search' => $search,
                    'page' => $page,
                    'prev' => $prevPage,
                    'next' => $nextPage
        ]);
    }

    public function newP()
    {
        return $this->app['twig']->render('create.twig', [
                    'form' => $this->app['product.form']->createView()
        ]);
    }

    public function edit()
    {
        $id = $this->request->get('id');
        
        $product = $this->app[$this->service]->findOneById($id);
        $form = $this->app[$this->form];
        $data = $product->toArray();
        $numberFormatter = new \NumberFormatter('pt_BR', \NumberFormatter::DECIMAL);
        $data['value'] = $numberFormatter->format($data['value']);

        $form->setData($data);

        return $this->app['twig']->render('edit.twig', [
                    'form' => $form->createView()
        ]);
    }
    
    public function save()
    {
        $data = $this->request->request->get('form');    
        $this->app[$this->service]->save($data);
    
        return $this->app->redirect('/products/page');
    }
    
    public function delete()
    {
        $id = $this->request->get('id');
        $this->app[$this->service]->delete($id);
        
        return $this->app->redirect('/products/page');
    }

}
