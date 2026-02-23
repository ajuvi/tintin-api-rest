<?php

require_once("App.php");

define("ROUTE_BASE", "/api");

// Classe auxiliar Path per obtenir la ruta actual
class Path {
    public static function route(){
        $route = str_replace(ROUTE_BASE, "", $_SERVER['REQUEST_URI']);
        return rtrim(parse_url($route, PHP_URL_PATH), "/");
    }
}

$app = new App();

// Carregar dataset
$dataset = json_decode(file_get_contents("tintin.data"));

/**
 * GET /
 */
$app->get("/", function(){
    echo "Very very simple api v0.1";
});

/**
 * GET /hola
 */
$app->get("/hola", function(){
    echo "Hola a tothom";
});

/**
 * GET /hola/{nom}
 */
$app->get("/hola/{nom}", function(){
    $nom = App::param("nom");
    App::response_json("Hola $nom");
});

/**
 * GET /tintin
 */
$app->get("/tintin", function() use ($dataset){
    App::response_json($dataset);
});

/**
 * GET /tintin/{id}
 */
$app->get("/tintin/{id}", function() use ($dataset){
    $id = App::param("id");

    foreach($dataset as $item){
        if($item->id == $id){
            App::response_json($item);
        }
    }

    App::response_json(null);
});

/**
 * Ruta per defecte
 */
$app->get("default", function(){
    http_response_code(404);
    App::response_json(["error" => "Ruta no trobada"]);
});

$app->run();