<?php	error_reporting(-1);	require __DIR__ . '/Ant/Ant.php';	$GLOBALS['sasaika'] = 'Hellow';	\Ant\Ant::init()	->setup(		array(			'view' => $_SERVER['DOCUMENT_ROOT'] . "templates",			'cache' => $_SERVER['DOCUMENT_ROOT'] . "cache",			'extension' => 'php',			'debug' => true,			'minify' => false		)	)	->activate('Asset')	->activate('YouTube')	->activate('Validator')	->rule('~{@.+?@}~',function($match){		return '<h1>Ebal ebal</h1>';	});	echo \Ant\Ant::init()		->get('me')		->assign([			'name' => 'Jack',			'attr'   => '<>\'"',			'table' => [				range(0, 10),				range(10, 20),				range(20, 30),				range(30, 40),				range(40, 50)			]		])		->draw();?>