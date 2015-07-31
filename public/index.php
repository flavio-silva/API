<?php
require __DIR__ . '/../bootstrap.php';

$clientes = [
	['nome' => 'Flavio', 'email' => 'flv.alves@gmail.com', 'cpf' => '999.999.999-99'],
	['nome' => 'Mayara', 'email' => 'mayarachagas2010@gmail.com', 'cpf' => '888.888.888-88'],
];

$app->get('/clientes', function(\Silex\Application $app) use($clientes) {
	return $app->json($clientes);
});

$app->run();