<?php

// Require the app and run it
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'app.php';

$app['rmt.base_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
$app['rmt.base_url'] = '/files';
$app->run();

// set tools
if($app['session']->get('user')) {
    $tools = $app['db.tools']->findAllForUser($app['session']->get('user')['id']);
    $app['session']->set('tools', array_slice($tools, 0, 5, true));
}

// get messages

// get comments
