<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Answer extends CI_Controller {
	public function __construct($config="rest") {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		parent::__construct();
		$this->load->model('Mdl_general');
        $this->load->model('Mdl_user');
        $this->load->model('Mdl_question');
        $this->load->model('Mdl_answer');
	}
	public function index(){
		$this->load->view('welcome_message');
	}

	public function getVotes(){
		$this->sendJson($this->Mdl_answer->getVotes($_GET['answerID'],$_GET['voteType']));
	}

	public function deleteOrModifyAnswer(){
		$this->sendJson($this->Mdl_answer->deleteOrModifyAnswer($_POST['answerID'],$_POST['action']));
	}

	public function deriveAnswerForEditing(){
		$this->sendJson($this->Mdl_answer->deriveAnswerForEditing($_GET['answerID']));
	}

	public function editAnswer(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			if (!empty($_GET['answerID'])) {
				if($this->Mdl_answer->isThisAuthorOfAnswer($session_data['username'],$_GET['answerID'])){
					$data['username'] = $session_data['username'];
                	$this->load->view('vwUserMenu',$data);
                	$this->load->view('vwToEditAnswer',$data);
				}
				else{
					redirect('answer/viewSingleAnswerWithQuestion?answerid='.$_GET['answerID']);
				}
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

	public function getAnswersForQuestion(){
		$this->sendJson($this->Mdl_answer->getAnswersForQuestion($_GET['questionID']));
	}

	public function toAddAnswer(){
		if ($this->Mdl_general->sessionCheck()) {
			if (!empty($_GET['questionID'])) {
				$session_data = $this->session->userdata('tamilcoder');
				//check whether the user has already given an answer for this question: if yes, redirect with an alert message
                $data['username'] = $session_data['username'];
                $this->load->view('vwUserMenu',$data);
                $this->load->view('vwToAddAnswer',$data);
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

	public function updateAnswer(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			if($this->Mdl_answer->isThisAuthorOfAnswer($session_data['username'],$_POST['answerID'])){
				$this->sendJson($this->Mdl_answer->updateAnswer($_POST['answerID'],$_POST['editedAnswer']));	
			}
			else{
				array("message"=>"Sorry; You are not the author of this answer!","result"=>false);
			}
        }
        else{
        	array("message"=>"Session Expired; Please login and submit the answer!","result"=>false);
        }
	}

    public function submitAnswer(){
        $this->sendJson($this->Mdl_answer->addAnswer($_POST['questionID'],$_POST['wholeAnswer'],$_POST['byUserName']));
    }

	public function getAnswerWithQuestion(){
		$this->sendJson($this->Mdl_answer->getAnswerWithQuestion($_GET['answerID'],$_GET['userName']));
	}

	public function viewSingleAnswerWithQuestion(){
		if ($this->Mdl_general->sessionCheck()) {
            if (!empty($_GET['answerid'])) {
                $session_data = $this->session->userdata('tamilcoder');
                $data['username'] = $session_data['username'];
                $this->load->view('vwUserMenu',$data);
                $this->load->view('vwSingleAnswer',$data);
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

	public function getFeedComment(){
		$this->sendJson($this->Mdl_answer->feedComment($_POST['answerID'],$_POST['userName'],$_POST['comment']));
	}

	public function feedVote(){
		$this->sendJson($this->Mdl_answer->feedVote($_POST['answerID'],$_POST['userName'],$_POST['vote']));
	}

	public function getAnswersOfUserWithItsQuestion(){
		$this->sendJson($this->Mdl_answer->getAnswersOfUserWithItsQuestion($_GET['userName']));
	}

	public function viewAnswersOfUser(){
        if ($this->Mdl_general->sessionCheck()) {
            if (!empty($_GET['userid'])) {
                $session_data = $this->session->userdata('tamilcoder');
                $data['username'] = $session_data['username'];
                $this->load->view('vwUserMenu',$data);
                $this->load->view('vwAnswersOfUser',$data);
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

	public function getAnswersOfUserWithItsQuestionForViewing(){
		$this->sendJson($this->Mdl_answer->getAnswersOfUserWithItsQuestionForViewing($_GET['authorID']));
	}
	
	public function getFollowingAnswersOfUserWithItsQuestion(){
		$this->sendJson($this->Mdl_answer->getFollowingAnswersOfUserWithItsQuestion($_GET['userName']));
	}

	public function followThisAnswer(){
		$this->sendJson($this->Mdl_answer->followThisAnswer($_POST['loggedInUserName'],$_POST['answerID']));
	}

    private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
