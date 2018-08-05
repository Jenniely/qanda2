<?php

namespace Model;

class Question 
{
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
            $sth->bindValue(3, $parameters['q.category'], \PDO::PARAM_STR);
        }
        if (isset($parameters['q.creator'])) {
            $sth->bindValue(2, $parameters['q.creator'], \PDO::PARAM_STR);
        }
        $sth->bindValue(4, $id, \PDO::PARAM_INT);
        return $sth->execute();
    }
    
    public function listAll()
    {
        $sth = $this->connection->prepare("SELECT q.id, q.question, q.category, q.date_added, q.creator, q.email, q.is_up, q.is_answered, a.answer FROM `questions` as q left JOIN `answers` AS a ON q.id=a.que_id ORDER BY category");
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
        $sth = $this->connection->prepare('SELECT * FROM `questions` WHERE is_answered=0 ORDER BY date_added');
        if ($sth->execute()) {
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
    
    public function getAnswered() 
    {
        $sth = $this->connection->prepare('SELECT q.id, q.question, q.category, q.date_added, q.creator, a.answer FROM `questions` as q JOIN `answers` AS a ON q.id=a.que_id WHERE is_answered=1 ORDER BY category');
        if ($sth->execute()) {
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getUnpublished() 
    {
        $sth = $this->connection->prepare('SELECT * FROM `questions` WHERE is_up=0 ORDER BY category');
        if ($sth->execute()) {
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function getPublished() 
    {
        $sth = $this->connection->prepare('SELECT q.id, q.question, q.category, q.date_added, q.creator, a.answer FROM `questions` as q JOIN `answers` AS a ON q.id=a.que_id WHERE is_up=1 ORDER BY category');
        if ($sth->execute()) {
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function findCat($category)
    {
        $sth = $this->connection->prepare('SELECT q.id, q.question, q.category, q.date_added, q.creator, a.answer FROM `questions` as q left JOIN `answers` AS a ON q.id=a.que_id WHERE category=:category');
        $sth->bindValue(':category', $category, \PDO::PARAM_INT);
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

    public function publish($id, $status) 
    {
        $sth = $this->connection->prepare("UPDATE questions SET is_up=:is_up WHERE id={$id}");
        $sth->bindValue(':is_up', $status, \PDO::PARAM_INT);
        return $sth->execute();
    }

    public function showStat() 
    {
        $q = new \Model\Question($this->connection);
        $questions = $q->listAll();
        $categories = $q->showCat();
        foreach ($categories as $category) {
            $cat = $category['category'];
            $result[$cat] = array( );
        }
        foreach ($questions as $question) {
            $key = $question['category'];
            array_push($result[$key], $question);
        }
        foreach ($result as $key => $category) {
            $questionCount = (count($category));
            $unansweredCount = 0;
            $publishCount = 0;
            foreach ($category as $question) {
                if (!$question['is_answered']) {
                    $unansweredCount++;
                }
                if ($question['is_up'] == 1) {
                    $publishCount++;
                }
            }
            $stats[] = [ 'category' => $key,'total' => $questionCount, 'unanswered' =>  $unansweredCount, 'published' => $publishCount];
        }
        return $stats;
    }
}
