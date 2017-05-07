<?php
namespace App;

use Silex\Api\ControllerProviderInterface;
use Silex\Application as App;
use App\Controllers\UserController;
use App\UserManager;

class ControllerProvider implements ControllerProviderInterface
{
    private $app;

    public function connect(App $app)
    {
        $this->app = $app;
        $this->instantiateControllers();

        $app->error('user.controller:errorAction');

        $controllers = $this->app['controllers_factory'];

        $controllers->get('/', 'user.controller:homepageAction')
            ->method('GET')
            ->bind('homepage');

        $controllers->get('/users', 'user.controller:getAll')
            ->bind('user.list');

        $controllers->match('/register', 'user.controller:registerAction')
            ->method('GET|POST')
            ->bind('user.register');

        $controllers->match('/login', 'user.controller:loginAction')
            ->method('GET|POST')
            ->bind('user.login');

        $controllers->method('GET|POST')->match('/login_check', function() {
        })->bind('user.login_check');

        $controllers->method('GET|POST')->match('/logout', function() {
        })->bind('user.logout');

        return $controllers;
    }

    private function instantiateControllers()
    {
        $manager = new UserManager($this->app);
        $this->app['user.manager'] = $manager;

        $this->app['user.controller'] = function () use ($manager) {
            return new UserController($manager);
        };
    }
}
