<?php

namespace Controller;

class AnswerController
{
    /** @var \PDO */
    private $connection;
    /** @var \Twig_Environment */
    private $twig;

    public function __construct($connection, $twig)
    {
        $this->connection = $connection;
        $this->twig = $twig;
    }

    public function show($questionId)
    {
        $a = new \Model\Answer($this->connection);

        return $a->displayAnswer($questionId);
    }

    public function modify($questionId)
    {
        $a = new \Model\Answer($this->connection);
        $check = $a->isAnswered($questionId);
        if ($check) {
            $this->update($questionId);
        } else {
            $this->add($questionId);
        }
    }

    public function add($questionId)
    {
        $a = new \Model\Answer($this->connection);
        $q = new \Model\Question($this->connection);
        $question = $q->findId($questionId);
        $errors = [];
        if (\count($_POST) > 0) {
            $data = [];
            if (isset($_POST['answer'])) {
                $data['answer'] = $_POST['answer'];
            } else {
                $errors['answer'] = 'Error answer';
            }
            $data['que_id'] = $questionId;
            if (count($errors) == 0) {
                $idAdd = $a->add($data);
                if ($idAdd) {
                    header('Location: ?c=question&a=handleContent');
                }
            }
        }
        echo $this->twig->render('/addanswer.twig', ['question' => $question]);
    }

    public function update($questionId)
    {
        $a = new \Model\Answer($this->connection);
        $answer = $a->displayAnswer($questionId);
        $q = new \Model\Question($this->connection);
        $question = $q->findId($questionId);
        $errors = [];
        if (count($_POST) > 0) {
            $data = [];
            if (isset($_POST['answer'])) {
                $data['answer'] = $_POST['answer'];
            } else {
                $errors['answer'] = 'Error answer';
            }
            $data['que_id'] = $questionId;
            if (count($errors) == 0) {
                $idUpdate = $a->update($data);
                if ($idUpdate) {
                    header('Location: ?c=question&a=handleContent');
                }
            }
        }
        echo $this->twig->render('/updateanswer.twig', ['question' => $question, 'answer' => $answer]);
    }
}
