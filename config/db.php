<?php

//return [
//    'class' => 'yii\db\Connection',
//    'dsn' => 'oci:dbname=//localhost:1521/XE;charset=UTF8',
//    'username' => 'carpoolnowdb',
//    'password' => 'Duip34jitjit-',
//    'charset' => 'utf8',
//];

//return         [
//	'class' => 'apaoww\oci8\Oci8DbConnection',
//            'dsn' => 'oci8:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=52.8.1.171)(PORT=1521))(CONNECT_DATA=(SID=ORCL)));charset=AL32UTF8;', // Oracle
////	'dsn' => 'oci8:dbname=(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=trisager)(PORT=1521))(CONNECT_DATA=(SID=ORCL)));charset=AL32UTF8;', // Oracle
//	'username' => 'carpoolnowdb',
//	'password' => 'Duip34jitjit-',
//	'attributes' => [
//		PDO::ATTR_STRINGIFY_FETCHES => true,
//		PDO::ATTR_CASE => PDO::CASE_LOWER,
//	]
//
//];
return [
	'class' => 'yii\db\Connection',
	'dsn' => 'mysql:host=localhost;dbname=carpoolnowdb',
	'username' => 'carpoolnowdb',
	'password' => 'Duip34jitjit-',
	'charset' => 'utf8',
];