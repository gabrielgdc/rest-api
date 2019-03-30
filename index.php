<?php
header("Access-Control-Allow-Origin: *");
//autoload
$loader = require 'vendor/autoload.php';

//instanciando objeto
$app = new \Slim\Slim(array(
	'templates.path' => 'templates'
));

//listar cep pelo id
$app->get('/cep/:id', function ($id) use ($app) {
	(new \controllers\Cep($app))->get($id);
});

//listar todos os ce
$app->get('/cep/', function () use ($app) {
	(new \controllers\Cep($app))->lista();
});

//criar cep
$app->post('/cep/', function () use ($app) {
	(new \controllers\Cep($app))->inserir();
});

//editar cep
$app->put('/cep/:id', function ($id) use ($app) {
	(new \controllers\Cep($app))->editar($id);
});

//apagar cep
$app->post('/cepdelete/:id', function ($id) use ($app) {
	(new \controllers\Cep($app))->excluir($id);
});

//rodando a aplicação
$app->run();
 