#Rotas Restful

#### /product (POST e PUT)
* name          -> TEXT
* description   ->  TEXT
* value         ->TEXT
* category      ->TEXT (Id da categoria)
* image         -> FILE
* tags[]        ->TEXT (Id das Tags)

#### /product (GET) Todos os produtos

#### /product/id (GET e DELETE) Retorna um produto e remove respectivamente

O mesmo esquema de rotas também para /category e /tag
