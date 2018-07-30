<?php
namespace Model;
class Question {


	public function __construct($connection) 
	{
	$this->connection = $connection;
    }

    public function add($parameters)
	{
		$sth = $this->connection->prepare(
			'INSERT INTO questions (question, category, date_added, creator, email)'
			.' VALUES(:question, :category, :date_added, :creator, :email)'
		);
		$sth->bindValue(':question', $parameters['question'], \PDO::PARAM_STR);
		$sth->bindValue(':category', $parameters['category'], \PDO::PARAM_STR);
		$sth->bindValue(':date_added', date("Y-m-d H:i:s"), \PDO::PARAM_STR);
		$sth->bindValue(':creator', $parameters['creator'], \PDO::PARAM_STR);
		$sth->bindValue(':email', $parameters['email'], \PDO::PARAM_STR);
		return $sth->execute();
	}

	public function delete($id)
	{
		$sth = $this->connection->prepare('DELETE FROM `questions` WHERE id=:id');
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
			$update[] = $parameter.'=?';
		}
		$sth = $this->connection->prepare('UPDATE questions as q, answers as a SET'. ' '. implode(', ', $update).' WHERE q.id=a.que_id AND q.id=?');
		if (isset($parameters['q.question'])) {
			$sth->bindValue(1, $parameters['q.question'], \PDO::PARAM_STR);
		}
		if (isset($parameters['q.category'])) {
			$sth->bindValue(4, $parameters['q.category'], \PDO::PARAM_STR);
		}
		if (isset($parameters['q.creator'])) {
			$sth->bindValue(2, $parameters['q.creator'], \PDO::PARAM_STR);
		}
		if (isset($parameters['a.answer'])) {
			$sth->bindValue(3, $parameters['a.answer'], \PDO::PARAM_STR);
		}
		$sth->bindValue(5, $id, \PDO::PARAM_INT);
		return $sth->execute();
	}

	public function listAll()
	{
		$sth = $this->connection->prepare('SELECT q.id, q.question, q.category, q.date_added, q.creator, q.email, a.answer FROM `questions` as q left JOIN `answers` AS a ON q.id=a.que_id ORDER BY category');
		if ($sth->execute()) {
			return $sth->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function findId($id)
	{
		$sth = $this->connection->prepare('SELECT * FROM `questions` WHERE id=:id');
		$sth->bindValue(':id', $id, \PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	public function getUnanswered() 
    {
      $sth = $this->connection->prepare('SELECT * FROM `questions` WHERE is_answered=0 ORDER BY category');
		if ($sth->execute()) {
			return $sth->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
    }

    	public function getAnswered() 
    {
      $sth = $this->connection->prepare('SELECT q.id, q.question, q.category, q.date_added, q.creator, a.answer FROM `questions` as q JOIN `answers` AS a ON q.id=a.que_id WHERE is_answered=1 ORDER BY category');
		if ($sth->execute()) {
			return $sth->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
    }

    	public function findCat($category)
	{
		$sth = $this->connection->prepare('SELECT * FROM `questions` WHERE category=:category AND is_answered=1');
		$sth->bindValue(':category', $category, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

	    	public function showCat()
	{
		$sth = $this->connection->prepare('SELECT DISTINCT category FROM `questions`');
		$sth->execute();
		$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
		return $result;
	}

}
