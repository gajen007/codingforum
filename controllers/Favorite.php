<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Favorite extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
	    $this->load->library('session');
   		$this->load->model('Mdl_general');
	}
	public function questions(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$data['username'] = $session_data['username'];
			$this->load->view("vwUserMenu");
			$this->load->view("vwFavoriteQuestions",$data);
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}
	public function answers(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$data['username'] = $session_data['username'];
			$this->load->view("vwUserMenu");
			$this->load->view("vwFavoriteAnswers",$data);
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}
	public function writers(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$data['username'] = $session_data['username'];
			$this->load->view("vwUserMenu");
			$this->load->view("vwFavoriteWriters",$data);
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}
	public function headings(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$data['username'] = $session_data['username'];
			$this->load->view("vwUserMenu");
			$this->load->view("vwFavoriteHeadings",$data);
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}
	private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
