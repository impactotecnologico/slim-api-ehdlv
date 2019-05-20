<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../services/post/postService.php';

$post = new Post;
$app->get('/alquileres', function ($request, $response, $args) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.fotocasa.es/PropertySearch/Search?clientId=9202751260688&combinedLocationIds=724,0,0,0,0,0,0,0,0&culture=es-ES&hrefLangCultures=ca-ES%3Bes-ES%3Bde-DE%3Ben-GB&isMap=false&isNewConstruction=false&latitude=40&longitude=-4&pageNumber=1&platformId=1&sortOrderDesc=true&sortType=bumpdate&transactionTypeId=3");
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result   

	// Fetch and return content, save it.
	$raw_data = curl_exec($ch);
	curl_close($ch);

	// If the API is JSON, use json_decode.
	$data = json_decode($raw_data);
	//this.registerPost(json_encode($data->realEstates, JSON_PRETTY_PRINT));
	echo json_encode($data, JSON_PRETTY_PRINT);
});

$app->get('/ventas', function ($request, $response, $args) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.fotocasa.es/PropertySearch/Search?clientId=9202751260688&combinedLocationIds=724,0,0,0,0,0,0,0,0&culture=es-ES&hrefLangCultures=ca-ES%3Bes-ES%3Bde-DE%3Ben-GB&isMap=false&isNewConstruction=false&latitude=40&longitude=-4&pageNumber=1&platformId=1&sortOrderDesc=true&sortType=bumpdate&transactionTypeId=1");
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result   

	// Fetch and return content, save it.
	$raw_data = curl_exec($ch);
	curl_close($ch);

	// If the API is JSON, use json_decode.
	$data = json_decode($raw_data);
	var_dump($data);
});

/*$app->get('/bancos/{id}', function (Request $request, Response $response) use ($bancos, $app) {
      $id = $request->getAttribute('id');
      $result = $bancos->getById($id);
      echo json_encode($result);
});*/