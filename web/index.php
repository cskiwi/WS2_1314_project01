<?php

// Require the app and run it
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'app.php';

$app['rmt.base_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
$app['rmt.base_url'] = '/files';
$app->run();

// set tools
if($app['session']->get('user')) {
    $userId = $app['session']->get('user')['id'];

    $tools = $app['db.tools']->findAllForUser($userId);
    $app['session']->set('tools', array_slice($tools, 0, 5, true));

    $tools = $app['db.messages']->findInbox($userId);
    $app['session']->set('messages', array_slice($tools, 0, 5, true));//*/

    $app['session']->set('messagesUnread', $app['db.messages']->countUnread($userId)['count(*)']);
}

// get messages

// get comments
