<?php

define("ROUTE_BASE", "/api");

$metode = strtoupper($_SERVER['REQUEST_METHOD']);
$route = str_replace(ROUTE_BASE, "", $_SERVER['REQUEST_URI']);

$dataset = json_decode(file_get_contents("tintin.data"));

if($metode=="GET") {

    // Ruta "/"
    if(matchRoute("/", $route) !== false){
        echo "Very very simple api v0.1";
        exit(0);
    }
    
    // Ruta "/hola"
    if(matchRoute("/hola", $route) !== false){
        echo "Hola a tothom";
        exit(0);
    }

    // Ruta "/hola/{nom}"
    if($params = matchRoute("/hola/{nom}", $route)){
        $nom = $params[0];
        writeDataToJson("Hola $nom");
    }

    // Ruta "/tintin"
    if(matchRoute("/tintin", $route) !== false){
        writeDataToJson($dataset);
    }

    // Ruta "/tintin/{id}"
    if($params = matchRoute("/tintin/{id}", $route)){
        $id = $params[0];
        foreach($dataset as $item){
            if($item->id==$id){
                writeDataToJson($item);
            }
        }
        
        writeDataToJson(null);
    }
}


if($metode=="POST"){
    // not implemented yet
}

if($metode=="PUT"){
    // not implemented yet
}

if($metode=="DELETE"){
    // not implemented yet
}


// Funcions auxiliars
function matchRoute($pattern, $route) {
    $regex = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $pattern);
    $regex = "#^" . trim($regex, "/") . "/?$#"; 
    $route = trim($route, "/");

    if (preg_match($regex, $route, $matches)) {
        array_shift($matches);
        return $matches;
    }
    return false;
}

function writeDataToJson($data){
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit(0);
}