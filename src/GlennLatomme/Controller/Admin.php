<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class Admin implements ControllerProviderInterface {
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];
        // Check if user is logged in
        $controllers->before(array($this, 'loginCheck'));

        // Mount Admin “Subcontrollers”
        $app->mount('/admin/tool/', new Admin\Tool());

        // Redirect to blog overview if we hit /admin/
        $controllers
            ->get('/', array($this, 'overview'))
            ->bind('admin.overview');
        $controllers
            ->get('/settings/{type}', array($this, 'settings'))
            ->assert('type', '^(?!\s*$).+')
            ->value ('type', 'profile' )
            ->bind('admin.settings');

        return $controllers;

    }
    public function overview(Application $app) {
        // Render template
        return $app['twig']->render('admin/overview.twig', array(

        ));

    }
    public function settings(Application $app, $type) {
        return $app['twig']->render('admin/settings.twig', array(
            'type' => $type
        ));    }
    public function loginCheck(\Symfony\Component\HttpFoundation\Request $request, Application $app){
        if (!$app['session']->get('user')) {
            return $app->redirect($app['url_generator']->generate('auth.login'));
        } else {
            $tools = $app['db.tools']->findAllForUser($app['session']->get('user')['id']);
            $app['session']->set('tools', array_slice($tools, 0, 5, true));
        }
    }

}