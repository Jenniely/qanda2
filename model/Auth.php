<?php
namespace Model;
class Auth {

public function __construct($connection) 
{
	$this->connection = $connection;
}

public function getUser($login) 
{
	$sql = "SELECT * FROM users WHERE login='$login'";
	$myUser = $this->connection->query($sql)->fetch(PDO::FETCH_ASSOC);
	return($myUser);
}

public function login($login, $password) 
{
	$user = $this->getUser($login);
	if ($user['password'] == $password) 
	{
		$_SESSION['user'] = $user;
		return true;
	}
	return false;
}


public function isAuthorized() {
	return !empty($_SESSION['user']);
    }

public function isAdmin() {
	return $this->isAuthorized() && $_SESSION['user']['is_admin'];
    }

public function getAuthorizedUser() {
	return $_SESSION['user'];
    }

public function registerNew($login, $password) {
    $check = $this->connection->query("SELECT user_id FROM users WHERE login='$login'")->fetch(PDO::FETCH_ASSOC);
	if (!empty($check['user_id'])) {
	echo "Такой пользователь уже зарегистрирован";
	exit;
	}
	else 
	$sql = "INSERT INTO users(`login`, `password`) VALUES ('$login','$password')";
	$action =  $this->connection->exec($sql);
	$check =  $this->connection->query("SELECT user_id FROM users WHERE login='$login'")->fetch(PDO::FETCH_ASSOC);
	if (!empty($check['user_id'])) {
	echo "Учетная запись создана, теперь вы можете войти.";
	}
	else {
		echo "Что-то не так";
	}
 }

public function deleteUser($id) 	
    {
		$sth = $this->connection->prepare('DELETE FROM `users` WHERE id=:id');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		return $sth->execute();
	}

public  function updateUser($id, $parameters) 
{
	if (count($parameters) == 0) {
			return false;
		}
		$update = [];
		foreach ($parameters as $parameter => $value) {
			$update[] = $parameter.'`=:'.$parameter;
		}
		$sth = $this->connection->prepare('UPDATE `users` SET `'.implode(', `', $update).' WHERE `id`=:id');
		if (isset($parameters['password'])) {
			$sth->bindValue(':password', $parameters['password'], PDO::PARAM_INT);
		}
		if (isset($parameters['is_admin'])) {
			$sth->bindValue(':is_admin', $parameters['is_admin'], PDO::PARAM_STR);
		}
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		return $sth->execute();
}


}
