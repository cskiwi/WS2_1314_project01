<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller\Admin;


use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Message implements ControllerProviderInterface {
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];

        // Messaging
        $controllers
            ->get('/', array($this, 'inbox'))
            ->bind('messages');
        $controllers
            ->get('/out/', array($this, 'outbox'))
            ->bind('messages.out');
        $controllers
            ->match('/{id}/', array($this, 'message'))
            ->method('GET|POST')
            ->assert('id', '\d+')
            ->bind('messages.detail');
        $controllers
            ->match('/compose/{to}', array($this, 'compose'))
            ->method('GET|POST')
            ->value('to', '')
            ->bind('messages.compose');

        return $controllers;

    }

    public function inbox(Application $app){
        $messages = $app['db.messages']->findInbox($app['session']->get('user')['id']);

        return $app['twig']->render('admin/messaging/inbox.twig', [
            'messages' => $messages,
        ]);
    }
    public function outbox(Application $app){
        $messages = $app['db.messages']->findOutbox('to_id', $app['session']->get('user')['id']);
        return $app['twig']->render('admin/messaging/outbox.twig', [
            'messages' => $messages
        ]);
    }

    public function message(Application $app, $id){
        $user = $app['session']->get('user');
        if ($user) {
            $message = $app['db.messages']->find($id);

            if ($message){

                if ($message['to_user'] == $user ['id'] || $message['from_user'] == $user ['id']){
                    // mark messages as read

                    if ($user['id'] == $message['to_user'] && $message['message_read'] == false){
                        $app['db.messages']->update(
                            ['message_read' => true],
                            ['id' => $message['id']]
                        );
                    }

                    return $app['twig']->render('admin/messaging/detail.twig', [
                        'message' => $message,
                        'fromUser' => $app['db.users']->find($message['from_user'])
                    ]);
                }
            }
            return $app->redirect($app['url_generator']->generate('messages'));
        }
        return $app->redirect($app['url_generator']->generate('home'));

    }
    public function compose(Application $app, $to){
        $user = $app['session']->get('user');

        $sender = $app['db.users']->find($user['id']);
        $receiver = $app['db.users']->findUserByUsername($to);
        $tof = ['to' =>  ($receiver == null) ? $to : $receiver['username'] ];

        if ($user) {
            // Create Form
            $composeForm = $app['form.factory']->createNamed('composeForm', 'form', $tof)
                ->add('title', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                    'attr' => array(
                        'placeholder' => 'Enter a good title'
                    )
                ))
                ->add('to', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                    'attr' => array(
                        'placeholder' => 'Receipents username'
                    )
                ))
                ->add('content', 'textarea', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                    'attr' => array(
                        'placeholder' => 'Your message',
                        'rows' => 10
                    )
                ));

            // Form was submitted: process it
            if ('POST' == $app['request']->getMethod()) {
                $composeForm->bind($app['request']);

                if ($composeForm->isValid()) {

                    $data = $composeForm->getData();
                    $receiver = $app['db.users']->findUserByUsername($data['to']);

                    if ($receiver != null) {
                        $app['db.messages']->insert(array(
                            'from_user'  => $sender['id'],
                            'to_user' =>  $receiver['id'],
                            'title' =>  $data['title'],
                            'content' =>  $data['content'],
                            'date_send' => date('Y-m-d H:i:s')
                        ));

                        return $app->redirect($app['url_generator']->generate('messages'));
                    }
                    $to = $data['to'];
                }
            }
            if ($receiver == null && !empty($to)) {
                $composeForm->get('to')->addError(new \Symfony\Component\Form\FormError('Invalid username'));
            }

            return $app['twig']->render('admin/messaging/compose.twig', array(
                'composeForm' => $composeForm->createView(),
                'user' => $to
            ));
        }
        return $app->redirect($app['url_generator']->generate('home'));

    }


}