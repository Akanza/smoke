<?php
session_start();
define('ROOT', dirname($_SERVER['PHP_SELF' ]));
// var_dump(ROOT);

/***********
  ROUTING
************/
class router{
  private $_uri = '';
  private $_callback = "";
  public  $method;

  public function get($uri = [], $callback = []){
    $this->_uri["GET"][] = $uri;
    $this->_callback[] = $callback;
  }
  public function post($uri = [], $callback = []){
    $this->_uri["POST"][] = $uri;
    $this->_callback[] = $callback;
  }
  public function put($uri = [], $callback = []){
    $this->_uri["PUT"][] = $uri;
    $this->_callback[] = $callback;
  }
  public function delete($uri = [], $callback = []){
    $this->_uri["DELETE"][] = $uri;
    $this->_callback[] = $callback;
  }
  public function run(){
    $url = isset($_GET['url']) ? '/'.$_GET['url'] : '/';

    foreach ($this->_uri[$_SERVER['REQUEST_METHOD']] as $key => $value) {

      $value = $this->match($value);
      if (preg_match("#^$value$#",$url,$params)){
        $params = implode('/',$params);
        $params = explode('/',$params);
        array_shift($params);
        array_shift($params);

        call_user_func_array($this->_callback[$key],$params);

      }
    }
  }
  private function match($value){
    $n = explode(':num',$value);
    $a = explode(':alpha',$value);
    $i = explode(':any',$value);
    if (count($n) > 1) {
      $value = str_replace(":num", '[0-9]++', $value);
    }
    if (count($a) > 1) {
      $value = str_replace(":alpha", '[A-Za-z]++', $value);
    }
    if (count($i) > 1) {
      $value = str_replace(":any", '(.*)', $value);
    }
    return $value;
  }
}

global $r ;
$r = new router();
function get($url,$callback){
  global $r;
  $r->get($url,$callback);
}
function post($url,$callback){
  global $r;
  $r->post($url,$callback);
}
function delete($url,$callback){
  global $r;
  $r->delete($url,$callback);
}
function put($url,$callback){
  global $r;
  $r->put($url,$callback);
}
function run(){
  global $r;
  $r->run();
}


/*****

REDIRECTION

*****/
function redirect($route ="/"){
  if ($route != "/") {
    header("location:".ROOT.'/'.$route);
  }else{
    header("location:".ROOT.'/');
  }
}
/*****

VIEWS

*****/
function view($view,$vars=[],$layout = 'default'){
  ob_start();
  extract($vars);
  $view = str_replace('.', '/', $view);
  $views = "app/templates/".$view.'.php';
  if (file_exists($views)) {
    
      require $views;
      $content = ob_get_clean();
      if (file_exists('app/templates/layouts/'.$layout .'.php')) {
        require "app/templates/layouts/".$layout .'.php';  
      }else{
        return $content;
      }
      
  }else{

      return "Le fichier $views n'existe pas ";
  }
}
/*****

SESSION

*****/
function session($type="",$key ="",$value=""){
  if ($type == "create" || $type == "put") {
      
      $_SESSION[$key] = $value;

  }
  if ($type == "get") {
    if (isset($_SESSION[$key])) {
      return $_SESSION[$key];
    }else{
      return null;
    }
    
  }
  if ($type == "delete") {
      
      unset($_SESSION[$key]);
  }

  if ($type == "put") {

      if (isset($_SESSION[$key])) {
        $_SESSION[$key] = $value;
      }else{
        $_SESSION[$key] = $value;
      }
      
      
  }
  
  if ($type == "" && $key == "" && $value == "") {

    return $_SESSION;
  }

}
/*****

FLASH

*****/
function flash_create($message,$type = 'success'){

    session('create','flash',['message' => $message, 'type' => $type]);

    ob_start();

    $render = session('get','flash')['message'];
    
    require 'app/templates/flash/'.session('get','flash')['type'].".php";

    session('get','flash')['message'] = ob_get_clean();
    if (session('get','flash')) {
      return true;
    }
}

function flash_display(){
  if (isset($_SESSION['flash'])) {

    echo session('get','flash')['message'];

    unset($_SESSION['flash']);
  } 

}
/*****

HTML HELPER

*****/

function img($src,$attributes=[]){

  $r = '<img src="'.ROOT."/".'app'."/".'templates'."/".'assets/img'."/".''.$src.'"';
  foreach ($attributes as $k => $v) {
      
      $r.= ' '.$k.'="'.$v.'"';
    }
  $r.=">";
  return $r;
}

function url($lien,$title='',$attributes=[]){

  $r = '<a href="'.ROOT."/".$lien.'"';

  foreach ($attributes as $k => $v) {
  
    $r.= ' '.$k.'="'.$v.'"';
  }
  $r.='>'.$title.'</a>';

  return $r;
  
  
}

function css($title){
    $r =  "";
    if (is_array($title)) {
      
      foreach ($title as $css) {
        $r .= '<link rel="stylesheet" type="text/css" href="';
        $r  .= ROOT."/".'app'."/".'templates/assets'."/".'css'."/".$css.'">';
      }
    }else{
        $r .= '<link rel="stylesheet" type="text/css" href="';
        $r.= ROOT."/".'app'."/".'templates/assets'."/".'css'."/".$title.'">';
    }
    
    return $r;
}

function js($title){
   $r =  "";
    if (is_array($title)) {
      foreach ($title as $css) {
        $r .= '<script type="text/javascript" src="';
        $r.=  ROOT."/".'app'."/".'assets'."/".'js'."/".$css.'"></script>';
      }
    }else{
       $r .= '<script type="text/javascript" src="';
       $r.=  ROOT."/".'app'."/".'assets'."/".'js'."/".$title.'"></script>';
    }
  
      return $r;
  }