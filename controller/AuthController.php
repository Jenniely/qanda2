<?php
namespace Controller;

class AuthController {

	 public function __construct($connection, $twig) 
    {
    $this->connection = $connection;
    $this->twig = $twig;
    }

    public function login() {
       $auth = new \Model\Auth($this->connection);
	   $errors = [];
        if (count($_POST) > 0) {
            $data = [];
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

 public function logout() {
    $auth = new \Model\Auth($this->connection);
    return $auth->logout();
 }

 public function manage() {
        $auth = new \Model\Auth($this->connection);
        $check = $auth->isAuthorized();
        if ($check) {
            $user = $_SESSION['user'];
            echo $this->twig->render('/admin.twig', ['user' => $user]);
        }
        else {
            echo $this->twig->render('/authform.twig');
        }
    }

    public function registerNew() {
       $auth = new \Model\Auth($this->connection);
        $errors = [];
        if (count($_POST) > 0) {
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
                $idAdd = $auth->registerNew($login, $password);
                if ($idAdd) {
                    header('Location: ?c=auth&a=handleUsers');
                }
            }
        }
        echo $this->twig->render('/regform.twig');
    }

    public function deleteUser($id) {
        $auth = new \Model\Auth($this->connection);
        $delete = $auth->deleteUser($id);
            if ($delete) {
                header('Location: ?c=auth&a=handleusers');
            }

    }

    public function updateUser($id) {
        $id = intval($id);
        $auth = new \Model\Auth($this->connection);
        $user = $auth->findUser($id);
        if (count($_POST) > 0) {
        $password = $_POST['password'];
        $update = $auth->updateUser($id, $password);
            if ($update) {
                header('Location: ?c=auth&a=handleusers');
            }
        }
        else {
            echo $this->twig->render('/passchangeform.twig', ['user' => $user]);
        }

    }

        public function handleUsers() {
        $auth = new \Model\Auth($this->connection);
        $users = $auth->listAll();
        echo $this->twig->render('/handleusers.twig', ['users' => $users]);
    }
}