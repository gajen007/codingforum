<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Signup extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Mdl_general');
	}
	public function index(){
		if (!$this->Mdl_general->sessionCheck()) {
			if (!empty($_POST['usernameSignUp'])&&!empty($_POST['passwordSignUp'])) {
				$this->sendJson($this->Mdl_general->signup($_POST['usernameSignUp'],$_POST['passwordSignUp']));
			}
			else{
				$this->sendJson(array("message"=>"மன்னிக்கவும்; படிவத்தை முழுமையாக நிரப்பவும்..!","result"=>false));
			}
		}
		else{
			redirect('general');
		}
	}

	private function sendJson($data) {
		$this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}