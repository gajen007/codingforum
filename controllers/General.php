<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class General extends CI_Controller {
	public function __construct($config="rest") {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		parent::__construct();
	    $this->load->library('session');
		$this->load->model('Mdl_general');
	}

	public function test(){
		$this->load->view('vwTest');
	}

	public function index(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
    		$data['username'] = $session_data['username'];
			$this->load->view('vwUserMenu',$data);
			$this->load->view('vwHome');
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}
	
	public function resetPassword(){
		$this->sendJson($this->Mdl_general->resetPassword($_POST['userEmail']));
	} 

	public function toResetPassword(){
		$this->load->view('vwGuestMenu');
		$this->load->view('vwToResetPassword');
	}

	public function getallUsers(){
		if (!empty($_GET['userName'])) {
			$this->sendJson($this->Mdl_general->getallUsers($_GET['userName']));
		}
	}

	public function login(){
		if (isset($_POST['userName'])&&isset($_POST['passWord'])){
			$fromDB=$this->Mdl_general->login($_POST['userName'],$_POST['passWord']);
			if ($fromDB['result']) {
				$this->session->set_userdata('tamilcoder',array('username' => $_POST['userName']));
			}
			$this->sendJson(array("message"=>$fromDB['message'],"result"=>$fromDB['result']));
		}
		else{
			$this->sendJson(array("message"=>"Incorrect Username and or Password!","result"=>false));
		}
	}

	public function authenticate(){
		$session_data = $this->session->get_userdata();
		if (is_null($session_data)) {
			$this->sendJson(array("result"=>false));
		}
		else if (empty($session_data['tamilcoder'])) {
			$this->sendJson(array("result"=>false));
		}
		else if ($session_data['tamilcoder']=="") {
			$this->sendJson(array("result"=>false));
		}
		else{
			$ses=$session_data['tamilcoder'];
			$this->sendJson(array("result"=>true,"tamilcoder"=>$ses['username']));
		}
	}

	public function logout(){
		$this->session->unset_userdata('tamilcoder');
		session_destroy();
	}
	
	public function signup(){
		$this->sendJson($this->Mdl_general->signup($_POST['userName'],$_POST['passWord']));
	}

	public function searchUser(){
		$this->sendJson($this->Mdl_general->userSearch($_GET['searchKey']));
	}

	public function searchHeading(){
		$this->sendJson($this->Mdl_general->headingSearch($_GET['searchKey']));
	}

	public function searchQuestion(){
		$this->sendJson($this->Mdl_general->questionSearch($_GET['searchKey']));
	}

	public function searchAnswer(){
		$this->sendJson($this->Mdl_general->answerSearch($_GET['searchKey']));
	}

	private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
