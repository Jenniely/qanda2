<?php
namespace Model;

class Answer 
{
    public function __construct($connection) 
    {
    $this->connection = $connection;
    }

    public function add($parameters)
    {
        $id = $parameters['que_id'];
        $statusUpdate = $this->connection->prepare("UPDATE questions SET is_answered=1 WHERE id={$id}");
        $statusUpdate->execute();
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
        $sth = $this->connection->prepare('DELETE FROM `answers` WHERE que_id=:id');
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        return $sth->execute();
    }

    public function update($parameters)
    {
        if (count($parameters) == 0) {
            return false;
        }
        $sth = $this->connection->prepare('UPDATE `answers` SET `answer`=:answer WHERE `que_id`=:que_id');
        if (isset($parameters['answer'])) {
            $sth->bindValue(':answer', $parameters['answer'], \PDO::PARAM_INT);
        }
        if (isset($parameters['que_id'])) {
            $sth->bindValue(':que_id', $parameters['que_id'], \PDO::PARAM_STR);
        }
        return $sth->execute();
    }
    
    public function find($id)
    {
        $sth = $this->connection->prepare('SELECT * FROM `answers` WHERE id=:id');
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function displayAnswer($questionId) {
        $sth = $this->connection->prepare('SELECT answer FROM `answers` WHERE que_id=:que_id');
        $sth->bindValue(':que_id', $questionId, \PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function isAnswered($questionId) {
        $sth = $this->connection->prepare('SELECT is_answered FROM `questions` WHERE id=:id');
        $sth->bindValue(':id', $questionId, \PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        if ($result == 1) {
            return true;
        } else {
            return false;
        }
    }
}
