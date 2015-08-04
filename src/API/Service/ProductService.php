<?php

namespace API\Service;

use API\Mapper\ProductDAO;
use API\Entity\Product as ProductEntity;
use API\Hydrator;

class ProductService
{
    
    protected $dao;
    protected $product;
    
    public function __construct(ProductEntity $product, ProductDao $dao)
    {
        $this->dao = $dao;
        $this->product = $product;
    }
    
    public function save(array $data)
    {
        Hydrator::configure($this->product, $data);
        $this->dao->save($this->product);
    }
}