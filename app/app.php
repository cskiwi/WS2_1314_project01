<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$lang = "en";
if ($app['session']->get('current_language')) {
	$lang = $app['session']->get('current_language');
}

foreach (glob(__DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'locale'. DIRECTORY_SEPARATOR .  $lang . '/*.yml') as $locale) {
	$app['translator']->addResource('yaml', $locale, $lang);
}

// sets current language
$app['locale'] = $lang;

$app->get('/lang/{lang}', function($lang) use($app) {
	 // check if language exists
	if (is_dir(__DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR  . 'locale' .DIRECTORY_SEPARATOR .  $lang)) {
		// save user selection in session
		$app['session']->set('current_language', $lang);
	}

	return $app->redirect($_SERVER['HTTP_REFERER']);
});


$app->mount('/',        new \GlennLatomme\Controller\Home());
$app->mount('/tool/',    new \GlennLatomme\Controller\Tool());
$app->mount('/user/',    new \GlennLatomme\Controller\User());

$app->mount('/admin/',   new \GlennLatomme\Controller\Admin());
$app->mount('/auth/',   new \GlennLatomme\Controller\Admin\Auth());
