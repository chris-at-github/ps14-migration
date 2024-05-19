<?php

// BE
$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '$2y$12$ShyYPFPwEOjSRr0qbfp19OQJTbMSPgMhyEm99TrkyRbKiHZNul18q';
$GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = '1';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = 'kist-escherich-com-v12.ddev.site';

// DB
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['initCommands'] = 'SET SESSION sql_mode=\'\'';

if(\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment() === true) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] =  '1';
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = 'TYPO3\\CMS\\Core\\Error\\ErrorHandler';
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandlerErrors'] = E_ALL ^ E_NOTICE;
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['exceptionalErrors'] = E_ALL ^ E_NOTICE ^ E_WARNING ^ E_USER_ERROR ^ E_USER_NOTICE ^ E_USER_WARNING;
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = 'TYPO3\\CMS\\Core\\Error\\DebugExceptionHandler';
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = 'TYPO3\\CMS\\Core\\Error\\DebugExceptionHandler';
}