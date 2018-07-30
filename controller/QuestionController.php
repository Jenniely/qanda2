<?php
namespace Controller;
class QuestionController
{
 
 public $connection;

    public function __construct($connection, $twig) 
    {
    $this->connection = $connection;
    $this->twig = $twig;
    }

    public function show()
    {
        $q = new \Model\Question($this->connection);
        $questions = $q->getAnswered();
        $categories = $q->showCat();
        echo $this->twig->render('/client.twig', ['questions' => $questions, 'categories' => $categories]);
    }

	public function add()
	{
       $q = new \Model\Question($this->connection);
       $questions = $q->getAnswered();
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
                    header('Location: index.php');
                }
            }
        }
        echo $this->twig->render('/add.twig', ['questions' => $questions, 'categories' => $categories]);
    }

	public function update($id)
	{
        $q = new \Model\Question($this->connection);
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
                if (isset($_POST['answer'])) {
                    $data['a.answer'] = $_POST['answer'];
                } else {
                    $errors['answer'] = 'Error answer';
                }
                if (isset($_POST['category'])) {
                    $data['q.category'] = $_POST['category'];
                } else {
                    $errors['creator'] = 'Error creator';
                }
                if (count($errors) == 0) {
                    $isUpdate = $q->update($id, $data);
                    if ($isUpdate) {
                        header('Location: index.php');
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
            $q = new \Model\Question($this->connection);
            $isDelete = $q->delete($id);
            if ($isDelete) {
                header('Location: index.php');
            }
    }
}