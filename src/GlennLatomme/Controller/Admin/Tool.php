<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\Validator\Constraints as Assert;

class Tool implements ControllerProviderInterface {

    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];
        $controllers->before(array($this, 'loginCheck'));


        // Insert new toolpost
        $controllers
            ->match('/new/', array($this, 'add'))
            ->method('GET|POST')
            ->bind('admin.tool.add');

        // overview new toolpost
        $controllers
            ->match('/overview/', array($this, 'overview'))
            ->method('GET')
            ->bind('admin.tool.overview');

        // Update a toolpost
        $controllers
            ->match('/edit/{toolId}/', array($this, 'edit'))
            ->assert('toolId', '\d+')
            ->method('GET|POST')
            ->bind('admin.tool.edit');

        // Delete a toolpost
        $controllers
            ->match('/delete/{toolId}/', array($this, 'delete'))
            ->method('GET|POST')
            ->assert('toolId', '\d+')
            ->bind('admin.tool.delete');

        return $controllers;

    }
    public function overview(Application $app){
        $user = $app['session']->get('user');

        $tools = $app['db.tools']->findAllForUser($user['id']);

        return $app['twig']->render('Admin/Tool/overview.twig', array(
            'tools' => $tools,
        ));
    }
    public function add(Application $app){
        $user = $app['db.users']->find($app['session']->get('user')['id']);

        $canAdd = ($user['address1'] != null && $user['cityTown'] != null && $user['zipPostal'] != null && $user['country'] != null ) ;

        // Create Form
        $addForm = $app['form.factory']->createNamed('addForm', 'form')
            ->add('title', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'placeholder' => 'Enter a good title'
                )
            ))
            ->add('price', 'text', array(
                'constraints' => array(new Assert\Range(array('min' => 0, 'max' => '500'))),
                'attr' => array(
                    'placeholder' => 'Enter a price (or leave blank when free)',
                    'input_group' => array('prepend' => '@', 'size' => 'large')
                ),
            ))
            ->add('content', 'textarea', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'placeholder' => 'Enter some good content',
                    'rows' => 10
                )
            ))
            ->add('images', 'file', array(
                'attr' => array(
                    'multiple' => 'multiple',
                    'accept' => 'image/*'
                )
            ))
            ->add('tags', 'collection', array(
                'type'   => 'text',
                'allow_add' => true,
                'allow_delete' => true,
                'options'  => array(
                    'attr' => array(
                        'placeholder' => 'Tag'
                    )
                ),
            ));

        // Form was submitted: process it
        if ('POST' == $app['request']->getMethod() && $canAdd) {
            $addForm->bind($app['request']);

            if ($addForm->isValid()) {
                $data = $addForm->getData();
                $files = $app['request']->files->get($addForm->getName());

                // check if valid images
                if (isset($files['images'][0])){
                    foreach ($files['images'] as $image){
                        // Uploaded file must be `.jpg`!
                        if ('.jpg' != substr($image->getClientOriginalName(), -4)) {
                            $addForm->get('images')->addError(new \Symfony\Component\Form\FormError('Only .jpg allowed'));
                        }
                    }
                }

                if ($addForm->getErrorsAsString() == null){

                    // insert tool
                    $app['db.tools']->insert(array(
                        'date' =>  gmdate("Y-m-d", time()),
                        'user_id'  => $user['id'],
                        'title' =>  htmlentities($data['title']),
                        'content' =>  $data['content'],
                        'price' =>  $data['price']
                    ));

                    // get ID
                    $id = $app['db.tools']->lastID();

                    // insert keywords
                    foreach($data['tags'] as $tag){
                        $app['db.keywords']->insertKey(htmlentities($tag), $id);
                    }

                    // copy images
                    if (isset($files['images'][0])){
                        foreach ($files['images'] as $image){
                            $image->move($app['rmt.base_path'] . $id, time().'-'. $image->getClientOriginalName());
                        }
                    }

                    // redirect
                    return $app->redirect($app['url_generator']->generate('tool.detail', array('toolId' => $id)));
                }
            }
        }
        return $app['twig']->render('Admin/Tool/add.twig', array(
            'addForm' => $addForm->createView(),
            'canAdd' => $canAdd
        ));


    }
    public function edit(Application $app, $toolId) {
        // Fetch tool with given $toolPostId and logged in user Id
        $tool = $app['db.tools']->findForUser($toolId, $app['session']->get('user')['id']);
        $tags = array_map('current',  $app['db.keywords']->findKeywords($toolId));

        // Redirect to overview if it does not exist
        if ($tool === false) {
            return $app->redirect($app['url_generator']->generate('admin.tool.overview'));
        }
        $images = @scandir('files/'. $toolId);
        unset($images[0]); unset($images[1]);

        if(empty($images)) {
            $images = array(); // empty fix
        }

        // Build the form with the tool data as default values
        $editForm = $app['form.factory']
            ->createNamed('editForm', 'form', $tool)
            ->add('title', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'placeholder' => 'Enter a good title'
                )
            ))
            ->add('price', 'text', array(
                'constraints' => array(new Assert\Range(array('min' => 0, 'max' => '500'))),
                'attr' => array(
                    'placeholder' => 'Enter a price (or leave blank when free)',
                    'input_group' => array('prepend' => '@', 'size' => 'large')
                ),
            ))
            ->add('content', 'textarea', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'placeholder' => 'Enter some good content',
                    'rows' => 10
                )
            ))
            ->add('images', 'file', array(
                'attr' => array(
                    'multiple' => 'multiple',
                    'accept' => 'image/*'
                )
            ))
            ->add('tags', 'collection', array(
                'data' => $tags,
                'type'   => 'text',
                'allow_add' => true,
                'allow_delete' => true,
                'options'  => array(
                    'attr' => array(
                        'placeholder' => 'Tag'
                    )
                ),
            ))
            ->add('delete', 'choice', array(
                'choices' => $images,
                'multiple' => true,
                'expanded' => true
            ));

        // Form was submitted: process it
        if ('POST' == $app['request']->getMethod()) {
            $editForm->bind($app['request']);


            // Form is valid
            if ($editForm->isValid()) {

                $data = $editForm->getData();
                $files = $app['request']->files->get($editForm->getName());
                unset ($data['images']);

                foreach ($files['images'] as $image){
                    // Uploaded file must be `.jpg`!
                    if (isset($image) && ('.jpg' == substr($image->getClientOriginalName(), -4))) {
                        // Move it to its new location
                        $image->move($app['rmt.base_path'] . $toolId, time().'-'. $image->getClientOriginalName());
                    } else {
                        $editForm->get('images')->addError(new \Symfony\Component\Form\FormError('Only .jpg allowed'));
                    }
                }

                if(!empty($data['delete'])) {
                    foreach ($data['delete'] as $picture) {
                        unlink('files/' . $toolId . '/' . $images[$picture]);
                    }
                }

                unset($data['delete']);

                // Update data in DB
                $app['db.tools']->update([
                    'date' =>  gmdate("Y-m-d", time()),
                    'title' =>  $data['title'],
                    'content' =>  $data['content'],
                    'price' =>  $data['price']
                ], array('id' => $toolId));

                // insert keywords
                foreach($data['tags'] as $tag){
                    $app['db.keywords']->insertKey(htmlentities($tag), $toolId);
                }
                // Redirect to overview
                return $app->redirect($app['url_generator']->generate('tool.detail', ['toolId' => $toolId]) . '?feedback=edited');
            }
        }


        // Render the template with the form
        return $app['twig']->render('admin/tool/edit.twig', array(
            'tool' => $tool,
            'editForm' => $editForm->createView(),
        ));

    }
    public function delete(Application $app, $toolId) {

        // Fetch toolpost with given $toolPostId and logged in user Id
        $tool = $app['db.tools']->findForUser($toolId, $app['session']->get('user')['id']);

        // Redirect to overview if it does not exist
        if ($tool === false) {
            return $app->redirect($app['url_generator']->generate('admin.tool.overview'));
        }

        // Delete the toolpost
        $app['db.tools']->delete(array('id' => $toolId));
        $images = @scandir('files/'. $toolId);
        unset($images[0]); unset($images[1]);

        if(!empty($images)) {
            foreach ($images as $image) {
                unlink('files/' . $toolId . '/' . $image);
            }
            rmdir('files/' . $toolId);
        }

        // Redirect to overview
        return $app->redirect($app['url_generator']->generate('admin.tool.overview') . '?feedback=deleted');

    }

    public function loginCheck(\Symfony\Component\HttpFoundation\Request $request, Application $app){
        if (!$app['session']->get('user')) {
            return $app->redirect($app['url_generator']->generate('auth.login'));
        } else {
            $tools = $app['db.tools']->findAllForUser($app['session']->get('user')['id']);
            $app['session']->set('tools', array_slice($tools, 0, 5, true));
        }
    }

}