<?php

namespace Model;

class Auth 
{

    public function __construct($connection) 
    {
    $this->connection = $connection;
    }

    public function listAll() 
    {
        $sql = "SELECT * FROM users";
        $users = $this->connection->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        return($users);
    }

    public function getUser($login) 
    {
        $sql = "SELECT * FROM users WHERE login='$login'";
        $myUser = $this->connection->query($sql)->fetch(\PDO::FETCH_ASSOC);
        return($myUser);
    }

    public function login($login, $password) 
    {
        $user = $this->getUser($login);
        if ($user['password'] == $password) {
        $_SESSION['user'] = $user;
        return true;
        } else {
        return false;
        }
    }

    public function isAuthorized() 
    {
        return !empty($_SESSION['user']);
    }

    public function isAdmin() 
    {
        return $this->isAuthorized() && $_SESSION['user']['is_admin'];
    }

    public function getAuthorizedUser() 
    {
        return $_SESSION['user'];
    }

    public function registerNew($login, $password) 
    {
        $sth =  $this->connection->prepare("SELECT id_user FROM users WHERE login='$login'");
        $sth->execute();
        $check = $sth->fetch(\PDO::FETCH_ASSOC);
        if (!empty($check['user_id'])) {
            echo 'Такой пользователь уже зарегистрирован. <a href="?c=auth&a=handleUsers">Вернуться</a>';
            exit;
        } else {
            $sth =  $this->connection->prepare("INSERT INTO users(`login`, `password`) VALUES ('$login','$password')");
            $sth->execute();
            $sth =  $this->connection->prepare("SELECT id_user FROM users WHERE login='$login'");
            $sth->execute();
            $check = $sth->fetch(\PDO::FETCH_ASSOC);
            if (!empty($check['id_user'])) {
                return true;
            } else {
                echo "Что-то не так";
            }
        }
    }

    public function deleteUser($id)     
    {
        $sth = $this->connection->prepare('DELETE FROM `users` WHERE id_user=:id');
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        return $sth->execute();
    }

    public  function updateUser($id, $password) 
    {
        $sth = $this->connection->prepare('UPDATE `users` SET password=:password WHERE id_user=:id');
        $sth->bindValue(':password', $password, \PDO::PARAM_INT);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        return $sth->execute();
    }

    public function logout() 
    {
        session_destroy();
        header('Location: index.php');
    }

    public function findUser($id) 
    {
        $sql = "SELECT * FROM users WHERE id_user={$id}";
        $user = $this->connection->query($sql)->fetch(\PDO::FETCH_ASSOC);
        return($user);
    }
}
