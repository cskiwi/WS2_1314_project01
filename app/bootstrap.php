<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Symfony\Component\Translation\Loader\YamlFileLoader;

$app = new Silex\Application();

$app['debug'] = true;
$app['mails'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => [
        __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'views',
        __DIR__.'/../vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/views/Form']
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'r0359502_pw',
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
        'db.messages' => 'GlennLatomme\\Repository\\Messaging',
        'db.reservations' => 'GlennLatomme\\Repository\\Reservations',
    )
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    return $translator;
}));

$app['swiftmailer.options'] = array(
    'host' => 'mail.latomme-g.be',
    'port' => '587',
    'username' => 'mailmaster@latomme-g.be',
    'password' => 'mailmaster',
    'encryption' => 'tls',
    'auth_mode' => null
);

$app->before(function () use ($app) {
    foreach(glob(__DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR  . '/locale/*') as $locale) {
        $languages[] = basename($locale);
    }
    $app['twig']->addGlobal('languages', $languages);
});

$app['twig'] = $app->share($app->extend('twig', function($twig) {
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapIconExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapLabelExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapBadgeExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapFormExtension);
    return $twig;
}));


