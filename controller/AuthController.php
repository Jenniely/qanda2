<?php

namespace Controller;

use \Model\Auth;

class AuthController
{

    public function __construct($connection, $twig)
    {
        $this->connection = $connection;
        $this->twig = $twig;
    }

    public function login()
    {
        $auth = new Auth($this->connection);
        $errors = [];
        if (count($_POST) > 0) {
//            $data = [];
            if (isset($_POST['login'])) {
                $login = $_POST['login'];
            } else {
                $errors['login'] = 'Error login';
            }
            if (isset($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                $errors['password'] = 'Error password';
            }
            if (count($errors) == 0) {
                $try = $auth->login($login, $password);
                if ($try) {
                    header('Location: ?c=auth&a=manage');
                }
            }
        }
        echo $this->twig->render('/authform.twig');
    }

    public function logout()
    {
        $auth = new Auth($this->connection);

        return $auth->logout();
    }

    public function manage()
    {
        $auth = new Auth($this->connection);
        $check = $auth->isAdmin();
        $user = $_SESSION['user'];
        if ($check) {
            echo $this->twig->render('/admin.twig', ['user' => $user]);
        } else {
            echo $this->twig->render('/authform.twig');
        }
    }

    public function registerNew()
    {

    }

    public function deleteUser()
    {

    }

    public function updateUser()
    {

    }
}
