<?php
//i--- Error Reporting (Optional) can be in php.ini ; inside_4_core_structure ; torrison ; 01.05.2020 ; 1 ---/
error_reporting( E_ALL );

//i--- Autoloader based on PSR-o Standard ; inside_4_core_structure ; torrison ; 01.05.2020 ; 3 ---/
// https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md

require ('config.php');

function autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
    $fileName .= $className . '.php';

	// echo $fileName;

	require $fileName;

}
spl_autoload_register('autoload');

//i--- Composer Autoload ; inside_4_core_structure ; torrison ; 01.05.2020 ; 4 ---/
require __DIR__."/Tools/vendor/autoload.php";

$GLOBALS['app']['debugger'] = new \Tools\CommonCore\Debugger();
$GLOBALS['app']['debugger']->init();

// print_r($_GET);

//i--- Easy MVC Routing System Class ; inside_4_core_structure ; torrison ; 01.05.2020 ; 6 ---/
$routing = new \Tools\CommonCore\Routing();
$routing->route();

