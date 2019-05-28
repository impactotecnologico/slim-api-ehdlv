<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../services/post/postService.php';

$app->get('/alquileres', function ($request, $response, $args) use ($post, $app) {
	$post = new Post;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.fotocasa.es/PropertySearch/Search?clientId=9202751260688&combinedLocationIds=724,0,0,0,0,0,0,0,0&culture=es-ES&hrefLangCultures=ca-ES%3Bes-ES%3Bde-DE%3Ben-GB&isMap=false&isNewConstruction=false&latitude=40&longitude=-4&pageNumber=1&platformId=1&sortOrderDesc=true&sortType=bumpdate&transactionTypeId=3");
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result   

	// Fetch and return content, save it.
	$raw_data = curl_exec($ch);
	curl_close($ch);

	// If the API is JSON, use json_decode.
	$data = json_decode($raw_data);
	/*for($i=0;$i<count($data->realEstates[0]->features);$i++){
		foreach($data->realEstates[0]->features[$i] as $key){
			if($key == 'surface'){
				echo $data->realEstates[0]->features[$i]->value[0];
			}
		}
	}*/
//	echo json_encode($data->realEstates[0]->features, JSON_PRETTY_PRINT);
	$result = Array();
	
	for($i=0;$i<count($data->realEstates);$i++){
		array_push($result,$post->registrar($data->realEstates[$i], "alquiler"));
	}
	echo json_encode($result);
});

$app->get('/ventas', function ($request, $response, $args) {
	$post = new Post;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.fotocasa.es/PropertySearch/Search?clientId=9202751260688&combinedLocationIds=724,0,0,0,0,0,0,0,0&culture=es-ES&hrefLangCultures=ca-ES%3Bes-ES%3Bde-DE%3Ben-GB&isMap=false&isNewConstruction=false&latitude=40&longitude=-4&pageNumber=1&platformId=1&sortOrderDesc=true&sortType=bumpdate&transactionTypeId=1");
	curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result   

	// Fetch and return content, save it.
	$raw_data = curl_exec($ch);
	curl_close($ch);

	// If the API is JSON, use json_decode.
	$data = json_decode($raw_data);
	/*for($i=0;$i<count($data->realEstates[0]->features);$i++){
		foreach($data->realEstates[0]->features[$i] as $key){
			if($key == 'surface'){
				echo $data->realEstates[0]->features[$i]->value[0];
			}
		}
	}*/
//	echo json_encode($data->realEstates[0]->features, JSON_PRETTY_PRINT);
	$result = Array();
	for($i=0;$i<1;$i++){
		array_push($result,$post->registrar($data->realEstates[$i], "ventas"));
	}
	echo json_encode($result);
});

/*$app->get('/bancos/{id}', function (Request $request, Response $response) use ($bancos, $app) {
      $id = $request->getAttribute('id');
      $result = $bancos->getById($id);
      echo json_encode($result);
});*/