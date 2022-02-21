<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Mdl_general');
		$this->load->model('Mdl_message');
	}
	public function index(){

	}

	public function withUser(){
		if (!empty($_GET['userid'])) {
			if ($this->Mdl_general->sessionCheck()) {
				$session_data = $this->session->userdata('tamilcoder');
				$data['username'] = $session_data['username'];
				$this->load->view('vwUserMenu',$data);
				$this->load->view('vwMessagesWithUser',$data);
			}
			else{
				$this->load->view('vwGuestMenu');
				$this->load->view('vwGuestHome');
			}
		}
		else{
			redirect('');
		}
	}

	private function sendJson($data) {
		$this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}