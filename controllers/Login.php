<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Mdl_general');
	}
	public function index(){
		if (!$this->Mdl_general->sessionCheck()) {
			if (!empty($_POST['username'])&&!empty($_POST['password'])) {
				$fromModel=$this->Mdl_general->login($_POST['username'],$_POST['password']);
				if ($fromModel['result']) {
					$this->session->set_userdata('tamilcoder',array('username' => $_POST['username']));
				}
				$this->sendJson($fromModel);
			}
			else{
				$this->load->view('vwGuestMenu');
				$this->load->view('vwLoginPage');
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
