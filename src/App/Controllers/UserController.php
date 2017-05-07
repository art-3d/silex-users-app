<?php
namespace App\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\UserManager;
use Exception;

class UserController
{
    private $userManager;

    public function __construct(UserManager $manager)
    {
        $this->userManager = $manager;
    }

    public function homepageAction(Application $app)
    {
        return $app['twig']->render('index.html.twig');
    }

    public function errorAction(Exception $e, Request $request, $code)
    {
        switch ($code) {
            case 404:
                $message = 'The requested page could not be found.';
                break;
            default:
                $message = 'We are sorry, but something went terribly wrong.';
        }
        return new Response($message, $code);
    }

    public function registerAction(Application $app, Request $request)
    {
        if ($request->isMethod('POST')) {
            try {
                $user = $this->createUserFromRequest($request);
                $this->userManager->insert($user);
                $app['session']->getFlashBag()->set('alert', 'Account created.');
                return $app->redirect($app['url_generator']->generate('user.login'));

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $app['twig']->render('register.html.twig', array(
            'error' => isset($error) ? $error : null,
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'nickname' => $request->get('nickname'),
            'age' => $request->get('age')
        ));
    }

    public function loginAction(Application $app, Request $request)
    {
        return $app['twig']->render('login.html.twig', array(
            'error' => $app['security.last_error']($request),
            'alert' => implode(',', $app['session']->getFlashBag()->get('alert')),
            'nickname' => $app['session']->get('_security.last_username')
        ));
    }

    public function getAll(Application $app, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $condition = array($request->get('key') => $request->get('value'));
            $users = $this->userManager->findUsers($condition);

            $response = new JsonResponse();
            $response->setData($users);
            return $response;
        }

        $users = $this->userManager->findUsers();
        return $app['twig']->render('users.html.twig', array(
            'users' => $users
        ));
    }

    protected function createUserFromRequest(Request $request)
    {
        $request = $request->request;
        $data = array(
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'nickname' => $request->get('nickname'),
            'age' => $request->get('age'),
            'password' => $request->get('password')
        );

        $user = $this->userManager->createUser($data);

        $errors = $user->validate();
        if ($request->get('password') !== $request->get('confirm_password')) {
            $errors[] = "Passwords don\'t match.";
        }

        $users = $this->userManager->getSecureUsers();
        if (isset($users[$request->get('nickname')])) {
            $errors[] = "Nickname is already in use.";
        }

        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors));
        }

        return $user;
    }
}
