#!/usr/bin/env php
<?php
/*
scopo del programma
*/

function wrapper() {
	$cmd =" ";
	echo exec($cmd),"\n";
}

function usage($argv){
  return <<<__END__
uso:
	{$argv[0]} [action] [--go] [--test]
uso del programma
__END__;
}
//------------------------------------------------------------------------------
//  main
//------------------------------------------------------------------------------
$action = isset($argv[1])?$argv[1]:'test';

switch($action) {
	case 'x':
		die(' ... ');
	break;
	default:
		die(usage());
	break;
}

