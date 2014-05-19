<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Auth implements ControllerProviderInterface {

    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/login/', array($this, 'login')
            )->method('GET|POST')
            ->bind('auth.login');
        $controllers
            ->get('/logout/', array($this, 'logout'))
            ->bind('auth.logout');
        $controllers
            ->match('/register/', array($this, 'register'))
            ->method('GET|POST')
            ->bind('auth.register');

        // Redirect to login by default
        $controllers->get('/', function(Application $app) {
            return $app->redirect($app['url_generator']->generate('auth.login'));
        });

        return $controllers;

    }

    public function login(Application $app) {

        // Already logged in
        if ($app['session']->get('user')) {
            return $app->redirect($app['url_generator']->generate('index'));
        }

        // Create Form
        $loginform = $app['form.factory']
            ->createNamed('loginForm', 'form')
            ->add('username', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter your username'
                )
            ))
            ->add('password', 'password', array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter your password'
                )
            ));
        // Form was submitted: process it
        if ('POST' == $app['request']->getMethod()) {
            $loginform->bind($app['request']);

            // Form is valid
            if ($loginform->isValid()) {

                $data = $loginform->getData();
                $user = $app['db.users']->findUserByUsername($data['username']);
                if ($user){
                    // Password checks out
                    if (crypt($data['password'], $user['password']) === $user['password'] ) {

                        // Unset user password from record so that no-one can read the (encrypted) password from the session
                        unset($user['password']);

                        // Store user in session
                        $app['session']->set('user', ['id' => $user['id'], 'username' => $user['username']]);

                        $tools = $app['db.tools']->findAllForUser($data['username']);
                        $app['session']->set('tools', array_slice($tools, 0, 5, true));

                        // Redirect to admin index
                        return $app->redirect($app['url_generator']->generate('index'));
                    }
                    // Password does not check out: add an error to the form
                    else {
                        $loginform->get('password')->addError(new \Symfony\Component\Form\FormError('Invalid credentials'));
                    }
                } else {
                    // username doesn't exists
                    $loginform->get('username')->addError(new \Symfony\Component\Form\FormError('username does not exists'));
                }
            }
        }

        return $app['twig']->render('admin/auth/login.twig', [
            'loginForm' => $loginform->createView()
        ]);
    }

    public function register(Application $app){
        if ($app['session']->get('user')) {
            return $app->redirect($app['url_generator']->generate('home'));
        }

        // Create Form
        $registerForm = $app['form.factory']->createNamed('registerForm', 'form')
            ->add('username', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter username'
                )
            ))
            ->add('email', 'text', array(
                'constraints' => array(new Assert\NotBlank()),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter email'
                )
            ))
            ->add('password', 'password', array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter password'
                )
            ));

        // Form was submitted: process it
        if ('POST' == $app['request']->getMethod()) {
            $registerForm->bind($app['request']);

            if ($registerForm->isValid()) {
                $data = $registerForm->getData();
                $user = $app['db.users']->findUserByUsername($data['username']);

                if ($user == null) {
                    $app['db.users']->insert(array(
                        'username'       => $data['username'],
                        'password' =>  crypt($data['password']),
                        'email' =>  $data['email']
                    ));

                    $app['session']->set('user', array(
                        'id' => $app['db.users']->lastID(),
                        'username' => $data['username']
                    ));
                    // messaging
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Sign up message')
                        ->setFrom(array('mailmaster@rmt.be'))
                        ->setTo([$data['email']])
                        ->setBody(
                            $app['twig']->render(
                                'Mail/signup.twig',
                                array('name' => $data['username'])
                            ), 'text/html'
                        );

                    $app['mailer']->send($message);
                } else {
                    $registerForm->get('username')->addError(new \Symfony\Component\Form\FormError('Username Already exists'));
                }


                return $app->redirect($app['url_generator']->generate('index'));
            }
        }
        return $app['twig']->render('admin/auth/register.twig', array('registerForm' => $registerForm->createView()));

    }

    public function logout(Application $app) {
        $app['session']->remove('user');
        $app['session']->remove('tools');
        return $app->redirect($_SERVER['HTTP_REFERER']);
    }

}