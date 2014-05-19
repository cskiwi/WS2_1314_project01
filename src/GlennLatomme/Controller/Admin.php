<?php
/**
 * User: Glenn Latomme
 * Date: 5/13/14
 */
namespace GlennLatomme\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Admin implements ControllerProviderInterface {
    protected $settings = ['privacy', 'profile', 'address'];
    public function connect(Application $app) {

        // Create new ControllerCollection
        $controllers = $app['controllers_factory'];
        // Check if user is logged in
        $controllers->before(array($this, 'loginCheck'));

        // Mount Admin “Subcontrollers”
        $app->mount('/admin/tool/', new Admin\Tool());
        $app->mount('/admin/message/', new Admin\Message());

        // Redirect to blog dashboard if we hit /admin/
        $controllers
            ->get('/', array($this, 'dashboard'))
            ->bind('admin.dashboard');
        $controllers
            ->match('/settings/{type}/', array($this, 'settings'))
            ->assert('type', '^(?!\s*$).+')
            ->value ('type', 'profile' )
            ->bind('admin.settings');

        return $controllers;

    }
    public function dashboard(Application $app) {
        $user = $app['session']->get('user');
        $tools = $app['db.tools']->findAllForUser($user['id'], 5);
        $messages = $app['db.messages']->findInbox($user['id'], 5);
        // Render template
        return $app['twig']->render('admin/dashboard.twig', array(
            'tools' => $tools,
            'messages' => $messages
        ));

    }
    public function settings(Application $app, $type) {
        $user = $app['session']->get('user');
        $user = $app['db.users']->find($user['id']);
        if (in_array($type, $this->settings)){
            switch($type){
                case 'profile':
                    $form = $app['form.factory']->createNamed('accountForm', 'form', $user)
                        ->add('username', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\email()),
                            'attr' => array(
                                'placeholder' => 'Username',
                                'disabled' => 'disbaled'
                            )
                        ))
                        ->add('email', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\email()),
                            'attr' => array(
                                'placeholder' => 'Email'
                            )
                        ));


                    break;
                case 'address':
                    $form = $app['form.factory']->createNamed('addressForm', 'form', $user)
                        ->add('name', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 4))),
                            'attr' => array(
                                'placeholder' => 'Full name'
                            )
                        ))
                        ->add('address1', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 4))),
                            'attr' => array(
                                'placeholder' => 'Address line 1'
                            )
                        ))
                        ->add('address2', 'text', array(
                            'attr' => array(
                                'placeholder' => 'Address line 2'
                            )
                        ))
                        ->add('cityTown', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 4))),
                            'attr' => array(
                                'placeholder' => 'City'
                            )
                        ))
                        ->add('stateProvinceRegion', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 4))),
                            'attr' => array(
                                'placeholder' => 'State / Province / Region'
                            )
                        ))
                        ->add('zipPostal', 'text', array(
                            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 4))),
                            'attr' => array(
                                'placeholder' => 'Zip or Postal Code'
                            )
                        ))
                        ->add('country', 'choice', array(
                            'choices' => [
                                'AF' => 'Afghanistan',          'AL' => 'Albania',          'DZ' => 'Algeria',
                                'AS' => 'American Samoa',       'AD' => 'Andorra',          'AO' => 'Angola',
                                'AI' => 'Anguilla',             'AQ' => 'Antarctica',       'AG' => 'Antigua and Barbuda',
                                'AR' => 'Argentina',            'AM' => 'Armenia',          'AW' => 'Aruba',
                                'AU' => 'Australia',            'AT' => 'Austria',          'AZ' => 'Azerbaijan',
                                'BS' => 'Bahamas',              'BH' => 'Bahrain',          'BD' => 'Bangladesh',
                                'BB' => 'Barbados',             'BY' => 'Belarus',          'BE' => 'Belgium',
                                'BZ' => 'Belize',               'BJ' => 'Benin',            'BM' => 'Bermuda',
                                'BT' => 'Bhutan',               'BO' => 'Bolivia',          'BA' => 'Bosnia and Herzegowina',
                                'BW' => 'Botswana',             'BV' => 'Bouvet Island',    'BR' => 'Brazil',
                                'IO' => 'British Indian Ocean Territory',                   'BN' => 'Brunei Darussalam',
                                'BG' => 'Bulgaria',             'BF' => 'Burkina Faso',     'BI' => 'Burundi',
                                'KH' => 'Cambodia',             'CM' => 'Cameroon',         'CA' => 'Canada',
                                'CV' => 'Cape Verde',           'KY' => 'Cayman Islands',   'CF' => 'Central African Republic',
                                'TD' => 'Chad',                 'CL' => 'Chile',            'CN' => 'China',
                                'CX' => 'Christmas Island',     'CC' => 'Cocos (Keeling) Islands',
                                'CO' => 'Colombia',             'KM' => 'Comoros',          'CG' => 'Congo',
                                'CD' => 'Congo, the Democratic Republic of the',            'CK' => 'Cook Islands',
                                'CR' => 'Costa Rica',           'CI' => 'Cote d\'Ivoire',   'HR' => 'Croatia (Hrvatska)',
                                'CU' => 'Cuba',                 'CY' => 'Cyprus',           'CZ' => 'Czech Republic'
                            ],
                            'expanded' => false,
                        ));
                    // Form was submitted: process it
                    if ('POST' == $app['request']->getMethod()) {
                        $form->bind($app['request']);

                        if ($form->isValid()) {
                            $data = $form->getData();

                            $app['db.users']->update(array(
                                'name'  => $data['name'],
                                'address1'  => $data['address1'],
                                'address2'  => $data['address2'],
                                'cityTown'  => $data['cityTown'],
                                'stateProvinceRegion'  => $data['stateProvinceRegion'],
                                'zipPostal'  => $data['zipPostal'],
                                'country' =>  $data['country']
                            ), array('id'=> $user['id']));
                        } else {
                        }
                    }

                    break;
                case 'privacy':
                        $form = null;
                    break;
            }
            return $app['twig']->render('admin/settings.twig', array(
                'type' => $type,
                'form' => $form->createView()
            ));
        }

        return $app->redirect($app['url_generator']->generate('admin.settings'));
    }

    public function loginCheck(\Symfony\Component\HttpFoundation\Request $request, Application $app){
        if (!$app['session']->get('user')) {
            return $app->redirect($app['url_generator']->generate('auth.login'));
        }
        return null;
    }

}