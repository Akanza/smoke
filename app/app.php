<?php
require 'core/smoke.php';

get('/','index');
function index(){
  	$t = ['JOse','Koffi','Sinner']; 
  	shuffle($t);
  	var_dump($t[0]);
 
  	view('index');
}
get('/test',function(){
	flash_create('YOUPIII','success');
	redirect();
  echo "phrase de test";
});

get('/ptest/(.*)',function($num){
	// redirect('apropos');
	echo url('','Accueil',['style' => "color:green;"]);
  echo "phrase de test numero $num";
});

get('/apropos',function(){
  echo "ma page apropos avec le num";
});