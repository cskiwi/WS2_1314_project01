<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller\Admin;


use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Reservation implements ControllerProviderInterface {
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];
        $controllers->before(array($this, 'loginCheck'));

        // Messaging
        $controllers
            ->get('/', array($this, 'reservations'))
            ->bind('reservations');
        $controllers
            ->get('/accept/{id}/', array($this, 'accept'))
            ->bind('reservations.accept');

        $controllers
            ->get('/deny/{id}/', array($this, 'deny'))
            ->bind('reservations.deny');

        $controllers
            ->match('/make/{id}/', array($this, 'reservation'))
            ->method('GET|POST')
            ->bind('reservations.make');

        return $controllers;

    }

    public function reservations(Application $app){
        $waiting_reservations = $app['db.reservations']->findAllForUser($app['session']->get('user')['id']);
        $accepted_reservations = $app['db.reservations']->findAllForUser($app['session']->get('user')['id'], 'accepted');
        $denied_reservations = $app['db.reservations']->findAllForUser($app['session']->get('user')['id'], 'denied');

        return $app['twig']->render('admin/tool/reservations.twig', [
            'waiting_reservations' => $waiting_reservations,
            'accepted_reservations' => $accepted_reservations,
            'denied_reservations' => $denied_reservations
        ]);
    }

    public function reservation(Application $app, $id){
        $user = $app['session']->get('user');
        $tool = $app['db.tools']->find($id);

        if ($user && $tool) {
            // Create Form
            $reservationForm = $app['form.factory']->createNamed('reservationForm', 'form')
                ->add('start_date', 'date',[
                    'constraints' => array(new Assert\NotBlank()),
                    'required' => true,
                    'widget' =>'single_text',
                    'format' =>'yyyy-MM-dd',
                ])
                ->add('end_date', 'date',[
                    'constraints' => array(new Assert\NotBlank()),
                    'required' => true,
                    'widget' =>'single_text',
                    'format' =>'yyyy-MM-dd'
                ]);

            // Form was submitted: process it
            if ('POST' == $app['request']->getMethod()) {
                $reservationForm->bind($app['request']);
                if ($reservationForm->isValid()) {
                    $data = $reservationForm->getData();

                    if ($app['db.tools']->reservationsInPeriod($data['start_date']->format('Y-m-d'), $data['end_date']->format('Y-m-d'), $tool['id'])){
                        $reservationForm->get('end_date')->addError(new \Symfony\Component\Form\FormError('already a reservation this period'));
                        $reservationForm->get('start_date')->addError(new \Symfony\Component\Form\FormError(''));

                    } else {
                        if ($tool['user_id'] != $user['id']){
                            $app['db.reservations']->insert(array(
                                'start_date'    => $data['start_date']->format('Y-m-d'),
                                'end_date'      =>  $data['end_date']->format('Y-m-d'),
                                'user_id'         =>  $user['id'],
                                'tool_id'         =>  $tool['id']
                            ));
                            $reservation = $app['db.reservations']->findForUser($app['db.reservations']->lastID(), $tool['user_id']);
                            if ($app['mails']){
                                $Tooluser = $app['db.users']->find($tool['user_id']);

                                $message = \Swift_Message::newInstance()
                                    ->setSubject('Reservation made')
                                    ->setFrom(array('mailmaster@rmt.be'))
                                    ->setTo([$Tooluser['email']])
                                    ->setBody(
                                        $app['twig']->render(
                                            'Mail/Reservations/New.twig',
                                            array('user' => $user, 'reservation' =>$reservation)
                                        ), 'text/html'
                                    );

                                $app['mailer']->send($message);
                            }
                            return $app->redirect($app['url_generator']->generate('index'));
                        }
                    }
                }
            }

            return $app['twig']->render('admin/Tool/reservation.twig', array(
                'reservationForm' => $reservationForm->createView(),
                'tool' => $tool
            ));
        }
    }
    public function accept(Application $app, $id) {
        $user = $app['session']->get('user');
        $reservation = $app['db.reservations']->findForUser($id, $user['id']);
        $requestedUser = $app['db.users']->find($reservation['user_id']);

        if ($reservation==null) return $app->redirect($app['url_generator']->generate('reservations') . '?feedback=notyours');

        $app['db.reservations']->update([
            'status' =>  'accepted',
        ], array('id' => $id));

        if ($app['mails']){

            $app['db.messages']->insert([
                'from_user' => $user['id'],
                'to_user' => $requestedUser['id'],
                'title' => 'Reservation got accepted',
                'content' => 'your reservation is accepted. you can reply here to meet the owner',
                'date_send' =>  gmdate("Y-m-d", time())
            ]);

            $message = \Swift_Message::newInstance()
                ->setSubject('Reservation accepted')
                ->setFrom(array('mailmaster@rmt.be'))
                ->setTo([$requestedUser['email']])
                ->setBody(
                    $app['twig']->render(
                        'Mail/Reservations/Accepted.twig',
                        array('user' => $requestedUser, 'reservation' =>$reservation)
                    ), 'text/html'
                );

            $app['mailer']->send($message);
        }
        return $app->redirect($app['url_generator']->generate('reservations') . '?feedback=accepted');

    }
    public function deny(Application $app, $id) {
        $user = $app['session']->get('user');
        $reservation = $app['db.reservations']->findForUser($id, $user['id']);
        $requestedUser = $app['db.users']->find($reservation['user_id']);

        if ($reservation==null) return $app->redirect($app['url_generator']->generate('reservations') . '?feedback=notyours');

        $app['db.reservations']->update([
            'status' =>  'denied',
        ], array('id' => $id));

        if ($app['mails']){

            $app['db.messages']->insert([
                'from_user' => $user['id'],
                'to_user' => $requestedUser['id'],
                'title' => 'Reservation got denied',
                'content' => 'your reservation is denied. you can reply here talk to the owner',
                'date_send' =>  gmdate("Y-m-d", time())
            ]);

            $message = \Swift_Message::newInstance()
                ->setSubject('Reservation denied')
                ->setFrom(array('mailmaster@rmt.be'))
                ->setTo([$requestedUser['email']])
                ->setBody(
                    $app['twig']->render(
                        'Mail/Reservations/Denied.twig',
                        array('user' => $requestedUser, 'reservation' =>$reservation)
                    ), 'text/html'
                );

            $app['mailer']->send($message);
        }
        return $app->redirect($app['url_generator']->generate('reservations') . '?feedback=denied');

    }


    public function loginCheck(\Symfony\Component\HttpFoundation\Request $request, Application $app){
        if (!$app['session']->get('user')) {
            return $app->redirect($app['url_generator']->generate('auth.login'));
        }
        return null;
    }

}