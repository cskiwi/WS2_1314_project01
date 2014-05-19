<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Home implements ControllerProviderInterface {
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/', array($this, 'index'))
            ->bind('index');

        return $controllers;

    }

    public function index(Application $app){
        $toolCount = $app['db.tools']->countTools()['count(*)'];
        return $app['twig']->render('landing.twig', ['toolCount' => $toolCount]);
    }


}