<?php

if (!defined('TYPO3')) {
        die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '$2y$12$ShyYPFPwEOjSRr0qbfp19OQJTbMSPgMhyEm99TrkyRbKiHZNul18q';

// You may add PHP code here, wich is executed on every request after the configuration is loaded.
// The code here should only manipulate TYPO3_CONF_VARS for example to set the database configuration
// dependent to the requested environment.
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = 'smtp';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_server'] = 'localhost:1025';

// Database settings.
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'db';
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'db';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = '.*\\.ddev.site';

if(\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment() === true) {
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = '1';
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandler'] = 'TYPO3\\CMS\\Core\\Error\\ErrorHandler';
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandlerErrors'] = E_ALL ^ E_NOTICE;
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['exceptionalErrors'] = E_ALL ^ E_NOTICE ^ E_WARNING ^ E_USER_ERROR ^ E_USER_NOTICE ^ E_USER_WARNING;
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['debugExceptionHandler'] = 'TYPO3\\CMS\\Core\\Error\\DebugExceptionHandler';
  $GLOBALS['TYPO3_CONF_VARS']['SYS']['productionExceptionHandler'] = 'TYPO3\\CMS\\Core\\Error\\DebugExceptionHandler';
}
