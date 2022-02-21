<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Heading extends CI_Controller {
	public function __construct($config="rest") {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		parent::__construct();
		$this->load->model('Mdl_general');
        $this->load->model('Mdl_user');
        $this->load->model('Mdl_question');
        $this->load->model('Mdl_answer');
        $this->load->model('Mdl_heading');
	}
	public function index(){
		$this->load->view('welcome_message');
	}

	public function singleHeading(){
		if ($this->Mdl_general->sessionCheck()) {
    		if (!empty($_GET['headingid'])) {
    			$session_data = $this->session->userdata('tamilcoder');
    			$data['username'] = $session_data['username'];
    			$this->load->view('vwUserMenu',$data);
    			$this->load->view('vwSingleHeading',$data);
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

	public function addNewHeading(){
        $this->sendJson($this->Mdl_heading->addNewHeading($_POST['userName'],$_POST['headingText']));
    }
    public function addNewHeadingToQuestion(){
        $this->sendJson($this->Mdl_heading->addNewHeadingToQuestion($_POST['userName'],$_POST['questionID'],$_POST['headingText']));
    }
    
    public function headingsOfQuestion(){
    	if ($this->Mdl_general->sessionCheck()) {
    		if (!empty($_GET['questionID'])) {
    			$session_data = $this->session->userdata('tamilcoder');
    			$data['username'] = $session_data['username'];
    			$this->load->view('vwUserMenu',$data);
    			$this->load->view('vwHeadingsOfQuestion',$data);
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

    public function getHeadingsForQuestion(){
        $this->sendJson($this->Mdl_heading->getHeadingsForQuestion($_GET['questionID']));
    }

    public function getFollowingHeadingsByThisUser(){
    	$this->sendJson($this->Mdl_heading->getFollowingHeadingsByThisUser($_GET['loggedInUserName']));	
    }

	public function getQuestionsOfHeading(){
		$this->sendJson($this->Mdl_heading->getQuestionsOfHeading($_GET['headingID'],$_GET['loggedInUserName']));
	}

	public function followThisHeading(){
		$this->sendJson($this->Mdl_heading->followThisHeading($_POST['loggedInUserName'],$_POST['headingID']));
	}
	
    private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
