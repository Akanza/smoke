<?php



function app_files($files = []){
	foreach ($files['files'] as $file) {
		$f = str_replace('.','/', $file);
		require ROOT.'/'.$f . '.php';
	}
}
