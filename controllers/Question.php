<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Question extends CI_Controller {
	public function __construct($config="rest") {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		parent::__construct();
		$this->load->model('Mdl_general');
        $this->load->model('Mdl_user');
        $this->load->model('Mdl_question');
        $this->load->library('session');
	}
	public function index(){
		$this->load->view('welcome_message');
	}

    public function addNewQuestion(){
        $this->sendJson($this->Mdl_question->addNewQuestion($_POST['userName'],$_POST['questionText']));
    }

    public function deriveQuestion(){
        $this->sendJson($this->Mdl_question->deriveQuestion($_GET['questionID']));
    }

    public function viewSingleQuestionWithAnswers(){
        if ($this->Mdl_general->sessionCheck()) {
            if (!empty($_GET['questionid'])) {
                $session_data = $this->session->userdata('tamilcoder');
                $data['username'] = $session_data['username'];
                $this->load->view('vwUserMenu',$data);
                $this->load->view('vwSingleQuestion',$data);
            }
            else{
                redirect('/');
            }
        }
        else{
            $this->load->view('vwGuestMenu');
            $this->load->view('vwGuestHome');
        }
    }

    public function deriveQuestionWithAnswers(){
        $this->sendJson($this->Mdl_question->deriveQuestionWithAnswers($_GET['questionID'],$_GET['loggedInUserName']));
    }

    public function modifyHeadingsForQuestion(){
    	$this->sendJson($this->Mdl_question->modifyHeadingsForQuestion($_POST['questionID'],$_POST['headingsString']));
    }

    public function viewQuestionsOfUser(){
        if ($this->Mdl_general->sessionCheck()) {
            if (!empty($_GET['userid'])) {
                $session_data = $this->session->userdata('tamilcoder');
                $data['username'] = $session_data['username'];
                $this->load->view('vwUserMenu',$data);
                $this->load->view('vwQuestionsOfUser',$data);
            }
            else{
                redirect('/');
            }
        }
        else{
            $this->load->view('vwGuestMenu');
            $this->load->view('vwGuestHome');
        }
    }

    public function getQuestionsOfUser(){
        $this->sendJson($this->Mdl_question->getQuestionsOfUser($_GET['userName']));
    }

    public function getQuestionsOfUserForViewing(){
        $this->sendJson($this->Mdl_question->getQuestionsOfUserForViewing($_GET['authorID']));
    }

    public function getFollowingQuestionsOfUser(){
        $this->sendJson($this->Mdl_question->getFollowingQuestionsOfUser($_GET['userName']));
    }

    public function getQuestionsForUser(){
        $userName="all";
        if (!empty($_GET['userName'])) { $userName=$_GET['userName']; }
        $this->sendJson($this->Mdl_question->getQuestionsForUser($userName));
    }

    public function followThisQuestion(){
        $this->sendJson($this->Mdl_question->followThisQuestion($_POST['loggedInUserName'],$_POST['questionID']));
    }

    private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
