<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Tool implements ControllerProviderInterface {
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/search/', array($this, 'search'))
            ->bind('tool.search');

        $controllers
            ->get('/{id}/', array($this, 'detail'))
            ->assert('id', '\d+')
            ->bind('tool.detail');

        return $controllers;

    }

    public function detail(Application $app, $id){
        $tool = $app['db.tools']->find($id);
        $by = $app['db.users']->find($tool['user_id']);
        $tags = $app['db.keywords']->findKeywords($tool['id']);

        if ($tool){
            return $app['twig']->render('tool/detail.twig', [
                'tool' => $tool,
                'by' => $by,
                'tags' => $tags
            ]);
        }
        return $app->redirect($app['url_generator']->generate('index'));
    }


    public function search(Application $app, Request $request) {

        $data = $request->get('q');
        $user = $app['db.users']->find($app['session']->get('user')['id']);
        $search_result = "";
        if ($data){
            // var_dump(explode(" ", $data));
            $search_result = $app['db.tools']->search(explode(" ", $data));
        }
        return $app['twig']->render('tool/search.twig', [
            'search_result' => $search_result,
            'user' => $user,
            'searchQuerry' => $data
        ]);
    }

}