<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class User implements ControllerProviderInterface {
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];
        $user = $app['session']->get('user');

        $controllers->get('/', function(Application $app) {
            return $app->redirect($app['url_generator']->generate('index'));
        });

        $controllers
            ->get   ('/profile/{username}/', array($this, 'detail'))
            ->assert('username', '^(?!\s*$).+')
            ->value ('username', $user['username'] )
            ->bind  ('user.profile');

        return $controllers;

    }
    public function detail(Application $app, $username){
        $profile =  $app['db.users']->findUserByUsername($username);
        $tools = $app['db.tools']->findAllForUser( $profile['id']);
        return $app['twig']->render('user/profile.twig',[
            'profile' => $profile,
            'tools' => $tools
        ]);

    }
}