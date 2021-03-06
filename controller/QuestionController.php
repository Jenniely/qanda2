<?php

namespace Controller;

use \Model\Question;
use \Model\Answer;

class QuestionController
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

    public function show()
    {
        $q = new Question($this->connection);
        $questions = $q->getPublished();
        $categories = $q->showCat();
        echo $this->twig->render('/client.twig', ['questions' => $questions, 'categories' => $categories]);
    }

    public function add()
    {
        $q = new Question($this->connection);
        $categories = $q->showCat();
        $errors = [];
        if (count($_POST) > 0) {
            $data = [];
            if (isset($_POST['question'])) {
                $data['question'] = $_POST['question'];
            } else {
                $errors['question'] = 'Error question';
            }
            if (isset($_POST['category'])) {
                $data['category'] = $_POST['category'];
            } else {
                $errors['category'] = 'Error category';
            }
            if (isset($_POST['creator'])) {
                $data['creator'] = $_POST['creator'];
            } else {
                $errors['creator'] = 'Error creator';
            }
            if (isset($_POST['email'])) {
                $data['email'] = $_POST['email'];
            } else {
                $errors['creator'] = 'Error creator';
            }
            if (count($errors) == 0) {
                $idAdd = $q->add($data);
                if ($idAdd) {
                    header('Location: ?c=question&a=show');
                }
            }
        }
        echo $this->twig->render('/add.twig', ['categories' => $categories]);
    }

    public function update($id)
    {
        $q = new Question($this->connection);
        $questions = $q->listAll();
        $categories = $q->showCat();
        $errors = [];
        if (count($_POST) > 0) {
            $data = [];
            if (isset($_POST['question'])) {
                $data['q.question'] = $_POST['question'];
            } else {
                $errors['question'] = 'Error question';
            }
            if (isset($_POST['creator'])) {
                $data['q.creator'] = $_POST['creator'];
            } else {
                $errors['creator'] = 'Error creator';
            }
            if (isset($_POST['category'])) {
                $data['q.category'] = $_POST['category'];
            } else {
                $errors['creator'] = 'Error creator';
            }
            if (count($errors) == 0) {
                $isUpdate = $q->update($id, $data);
                if ($isUpdate) {
                    header('Location: ?c=question&a=handleContent');
                }
            }
        }
        foreach ($questions as $subarray => $question) {
            if ($question['id'] == $id) {
                $data = $question;
            }
        }
        echo $this->twig->render('/update.twig', ['question' => $data, 'categories' => $categories]);
    }

    public function delete($id)
    {
        $q = new Question($this->connection);
        $a = new Answer($this->connection);
        $qDelete = $q->delete($id);
        $aDelete = $a->delete($id);
        if ($qDelete && $aDelete) {
            header('Location: ?c=question&a=handleContent');
        }
    }

    public function handleContent()
    {
        $q = new Question($this->connection);
        $stats = $q->showStat();
        $questions = $q->listAll();
        $categories = $q->showCat();
        echo $this->twig->render('/handlecontent.twig', ['questions' => $questions, 'categories' => $categories, 'stats' => $stats]);
    }

    public function publish($id)
    {
        $q = new Question($this->connection);
        if (isset($_POST['is_up'])) {
            $status = $_POST['is_up'];
            $pubUpdate = $q->publish($id, $status);
            if ($pubUpdate) {
                header('Location: ?c=question&a=handleContent');
                exit;
            }
        }
    }

    public function getUnanswered()
    {
        $q = new Question($this->connection);
        $questions = $q->getUnanswered();
        $categories = $q->showCat();
        echo $this->twig->render('/handlecontent.twig', ['questions' => $questions, 'categories' => $categories]);
    }

    public function findCat()
    {
        $q = new Question($this->connection);
        $categories = $q->showCat();
        $category = $_POST['category'];
        $questions = $q->findCat($category);
        echo $this->twig->render('/handlecontent.twig', ['questions' => $questions, 'categories' => $categories]);
    }

    public function displayCat() 
    {
        $q = new Question($this->connection);
        $categories = $q->showCat();
        $category = $_GET['category'];
        $questions = $q->findCat($category);
        echo $this->twig->render('/client.twig', ['questions' => $questions, 'categories' => $categories]);
    }
}
