<?php

/**
 * App.php
 *
 * Simple-Rest-Api
 *
 * @category   Rest
 * @author     Artur Juvé Vidal
 * @copyright  2023 @ajuvi.github.com
 * @link       https://github.com/ajuvi/simple-rest-api.git
 */

class App{

    public $routes = array();
    public $metode;

    function __construct()
    {
        $this->routes = array();
        $this->metode = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function get($name,$function){
        if($this->metode=='GET')
            $this->route($name,$function);
    }

    public function post($name,$function){
        if($this->metode=='POST')
            $this->route($name,$function);
    }

    public function put($name,$function){
        if($this->metode=='PUT')
            $this->route($name,$function);
    }

    public function delete($name,$function){
        if($this->metode=='DELETE')
            $this->route($name,$function);
    }

    public function route($name,$function){
        $this->routes[$name] = $function;
    }

    public function run(){
    
        //assigna el camp default a la darrera posció
        if(isset($this->routes['default'])){
            $default = $this->routes['default'];
            unset($this->routes['default']);
            $this->routes['default'] = $default;
        }

        foreach ($this->routes as $route => $function) {
            if($this->match($route)){
                $function();
                break;
            }
        }
    }
    
    public function match($route){
        if($route == Path::route() || ($route . "/") == Path::route() || $route=='default'){
             return true;
        }else{
            $p_route = explode('/',rtrim($route,"/ "));
            $p_actual = explode('/',rtrim(Path::route(),"/ "));

            if(count($p_route)==count($p_actual)){
                for($i=0;$i<count($p_route);$i++){                    
                    if($this->_startsWith($p_route[$i],"{") && $this->_endsWith($p_route[$i],"}")){
                        //agafar els valors de {*}
                        $_GET[trim($p_route[$i],"{}")] = $p_actual[$i];
                    } else {
                        if($p_route[$i]!=$p_actual[$i]){
                            return false;
                        }
                    }
                }
                return true;
            }
        }

        return false;
    }

    private function _startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    private function _endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    static function param($name,$default=""){
        if(isset($_POST[$name]))
            return $_POST[$name];
        if(isset($_GET[$name]))
            return $_GET[$name];

        return $default;
    }

    static function response_json($data){
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60)));
        
        // headers to tell that result is JSON
        header('Content-type: application/json');
        
        // send the result now
        echo json_encode($data, JSON_UNESCAPED_UNICODE);    
        exit(0);    
    }

}