<?php
namespace App;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\AssetServiceProvider;

class Application extends SilexApplication
{
    public function __construct($config)
    {
        parent::__construct();

        $app = $this;
        $config = require __DIR__.'/../../config/prod.php';
        $app['twig.options'] = $config['twig.options'];
        $app['db.options'] = $config['db.options'];

        $app->register(new ServiceControllerServiceProvider());
        $app->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__.'/views'
        ));
        $app->register(new DoctrineServiceProvider());
        $app->register(new AssetServiceProvider());
        $app->register(new HttpFragmentServiceProvider());
        $app->register(new SessionServiceProvider());

        $app->mount('', new ControllerProvider());

        $app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'secured_area' => array(
                    'anonymous' => true,
                    'form' => array(
                        'login_path' => '/login',
                        'check_path' => '/login_check'
                    ),
                    'logout' => array(
                        'logout_path' => '/logout'
                    ),
                    'users' => $app['user.manager']->getSecureUsers()
                )
            )
        ));
        $app['security.access_rules'] = array(
            array('/users$', 'ROLE_USER')
        );
    }
}
