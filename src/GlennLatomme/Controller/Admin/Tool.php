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

        // Update a toolpost
        $controllers
            ->match('/edit/{toolpostId}/', array($this, 'edit'))
            ->assert('toolpostId', '\d+')
            ->method('GET|POST')
            ->bind('admin.tool.edit');

        // Delete a toolpost
        $controllers
            ->post('/delete/{toolpostId}/', array($this, 'delete'))
            ->assert('toolpostId', '\d+')
            ->bind('admin.tool.delete');

        return $controllers;

    }
    public function add(Application $app){
        $user = $app['db.users']->find($app['session']->get('user')['id']);

        $canAdd = ($user['address1'] != null && $user['cityTown'] != null && $user['zipPostal'] != null && $user['country'] != null ) ;

        // Create Form
        $addForm = $app['form.factory']->createNamed('addForm', 'form')
            ->add('title', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter a good title'
                )
            ))
            ->add('price', 'text', array(
                'constraints' => array(new Assert\Range(array('min' => 0, 'max' => '500'))),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter a price'
                )
            ))
            ->add('content', 'textarea', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter some good content',
                    'rows' => 10
                )
            ))
            ->add('image', 'file', array(
                'constraints' => array(new Assert\Image()),
                'attr' => array(
                    'class' => 'form-control',
                    "accept" => "image/*",
                )
            ))
            ->add('tags', 'collection', array(
                'type'   => 'text',
                'allow_add' => true,
                'allow_delete' => true,
                'options'  => array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                    'attr' => array(
                        'class' => 'form-control',
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
                if (isset($files['image'])){
                    if ('.jpg' != substr($files['image']->getClientOriginalName(), -4)) {
                        $addForm->get('image')->addError(new \Symfony\Component\Form\FormError('Only .jpg allowed'));
                    }
                }
                if (isset($files['image'])) {
                    $filename = time().'-'. $files['image']->getClientOriginalName();
                }

                $app['db.tools']->insert(array(
                    'user_id'  => $user['id'],
                    'title' =>  htmlentities($data['title']),
                    'content' =>  $data['content'],
                    'price' =>  $data['price'],
                    'image' => $filename
                ));
                $id = $app['db.tools']->lastID();
                foreach($data['tags'] as $tag){
                    $app['db.keywords']->insertKey(htmlentities($tag), $id);
                }
                if (isset($files['image'])) {
                    $files['image']->move($app['rmt.base_path'] . $id, $filename);
                }

                return $app->redirect($app['url_generator']->generate('tool.detail', array('id' => $id)));
            }
        }
        return $app['twig']->render('Admin/Tool/add.twig', array(
            'addForm' => $addForm->createView(),
            'canAdd' => $canAdd
        ));


    }

    public function edit(Application $app, $toolpostId) {
        // Fetch toolpost with given $toolPostId and logged in user Id
        $toolpost = $app['db.tool']->findForAuthor($toolpostId, $app['session']->get('user')['id']);

        // Redirect to overview if it does not exist
        if ($toolpost === false) {
            return $app->redirect($app['url_generator']->generate('admin.tool.overview'));
        }
        $images = @scandir('files/'. $toolpostId);
        unset($images[0]); unset($images[1]);

        if(empty($images)) {
            $images = array(); // empty fix
        }

        // Build the form with the toolpost data as default values
        $editform = $app['form.factory']
            ->createNamed('editform', 'form', $toolpost)
            ->add('title', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'class' => 'form-control',
                )
            ))
            ->add('content', 'textarea', array(
                'constraints' => array(new Assert\NotBlank()),
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => 10
                )
            ))
            ->add('images', 'file', array(
                'attr' => array(
                    'class' => 'form-control',
                    'multiple' => 'multiple',
                    'accept' => 'image/*'
                )
            ))
            ->add('delete', 'choice', array(
                'choices' => $images,
                'multiple' => true,
                'expanded' => true
            ));

        // Form was submitted: process it
        if ('POST' == $app['request']->getMethod()) {
            $editform->bind($app['request']);

            // Form is valid
            if ($editform->isValid()) {

                $data = $editform->getData();
                $files = $app['request']->files->get($editform->getName());
                unset ($data['images']);

                foreach ($files['images'] as $image){
                    // Uploaded file must be `.jpg`!
                    if (isset($image) && ('.jpg' == substr($image->getClientOriginalName(), -4))) {
                        // Move it to its new location
                        $image->move($app['cms.base_path'] . $toolpostId, time().'-'. $image->getClientOriginalName());
                    } else {
                        $editform->get('images')->addError(new \Symfony\Component\Form\FormError('Only .jpg allowed'));
                    }
                }

                if(!empty($data['delete'])) {
                    foreach ($data['delete'] as $picture) {
                        unlink('files/' . $toolpostId . '/' . $images[$picture]);
                    }
                }
                unset($data['photo']);
                unset($data['delete']);

                // Update data in DB
                $app['db.tool']->update($data, array('id' => $toolpostId));

                // Redirect to overview
                return $app->redirect($app['url_generator']->generate('admin.tool.overview') . '?feedback=edited');
            }
        }


        // Render the template with the form
        return $app['twig']->render('admin/tool/edit.twig', array(
            'user' => $app['session']->get('user'),
            'toolpost' => $toolpost,
            'editform' => $editform->createView(),
        ));

    }


    public function delete(Application $app, $toolpostId) {

        // Fetch toolpost with given $toolPostId and logged in user Id
        $toolpost = $app['db.tool']->findForAuthor($toolpostId, $app['session']->get('user')['id']);

        // Redirect to overview if it does not exist
        if ($toolpost === false) {
            return $app->redirect($app['url_generator']->generate('admin.tool.overview'));
        }

        // Delete the toolpost
        $app['db.tool']->delete(array('id' => $toolpostId));
        $images = @scandir('files/'. $toolpostId);
        unset($images[0]); unset($images[1]);

        if(!empty($images)) {
            foreach ($images as $image) {
                unlink('files/' . $toolpostId . '/' . $image);
            }
            rmdir('files/' . $toolpostId);
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