<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Symfony\Component\Translation\Loader\YamlFileLoader;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'views'
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'r0359502_PW',
        'user'      => 'r0359502_Student',
        'password'  => 'Azerty123',
        'charset'   => 'utf8',//*/
    )
));
$app->register(new Knp\Provider\RepositoryServiceProvider(), array(
    'repository.repositories' => array(
        'db.users' => 'GlennLatomme\\Repository\\Users',
        'db.tools' => 'GlennLatomme\\Repository\\Tools',
        'db.keywords' => 'GlennLatomme\\Repository\\Keywords',
    )
));

// config
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    return $translator;
}));


$app->before(function () use ($app) {
    foreach(glob(__DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR  . '/locale/*') as $locale) {
        $languages[] = basename($locale);
    }
    $app['twig']->addGlobal('languages', $languages);
});

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());