<?php	require 'ant.php';	Ant::init()	->setup(		array(			'view' => $_SERVER['DOCUMENT_ROOT'] . "/trunk/templates",			'cache' => $_SERVER['DOCUMENT_ROOT'] . "/trunk/cache",			'extension' => 'htm',			'minify' => true		)	);	echo Ant::init()	->get('variables')	->assign(		array(			'range' => array()		)	)	->draw();?>