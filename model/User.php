<?php

/*3. класс администрирование
а)добавление пользователей;
б) изменение пользователей;
в) удаление пользователей;
г) отображение пользователей.*/
class User {

	public function __construct($connection) 
	{
	$this->connection = $connection;
    }

    public function add($parameters)
	{
		$sth = $this->connection->prepare(
			'INSERT INTO answers (answer, que_id, date_added)'
			.' VALUES(:answer, :que_id, :date_added)'
		);
		$sth->bindValue(':answer', $parameters['answer'], \PDO::PARAM_STR);
		$sth->bindValue(':que_id', $parameters['que_id'], \PDO::PARAM_INT);
		$sth->bindValue(':date_added', date("Y-m-d H:i:s"), \PDO::PARAM_STR);
		return $sth->execute();
	}

	public function delete($id)
	{
		$sth = $this->connection->prepare('DELETE FROM `answers` WHERE id=:id');
		$sth->bindValue(':id', $id, \PDO::PARAM_INT);
		return $sth->execute();
	}

    public function update($id, $parameters)
	{
		if (count($parameters) == 0) {
			return false;
		}
		$update = [];
		foreach ($parameters as $parameter => $value) {
			$update[] = $parameter.'`=:'.$parameter;
		}
		$sth = $this->connection->prepare('UPDATE `answers` SET `'.implode(', `', $update).' WHERE `id`=:id');
		if (isset($parameters['answer'])) {
			$sth->bindValue(':answer', $parameters['answer'], \PDO::PARAM_INT);
		}
		if (isset($parameters['que_id'])) {
			$sth->bindValue(':que_id', $parameters['que_id'], \PDO::PARAM_STR);
		}
		$sth->bindValue(':id', $id, \PDO::PARAM_INT);
		return $sth->execute();
	}

	public function listAll()
	{
		$sth = $this->connection->prepare('SELECT * FROM `answers`');
		if ($sth->execute()) {
			return $sth->fetchAll();
		}
		return false;
	}

	public function find($id)
	{
		$sth = $this->connection->prepare('SELECT * FROM `answers` WHERE id=:id');
		$sth->bindValue(':id', $id, \PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

}
