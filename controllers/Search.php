<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Search extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
	    $this->load->library('session');
   	    $this->load->model('Mdl_general');
	}	
	
	public function question(){
		$this->load->view("vwUserMenu");
		if (empty($_GET['searchString'])) {
			$this->load->view("vwToSearchQuestion");
		}
		else{
			$data['searchResults']=$this->Mdl_general->questionSearch($_GET['searchString']);
			$this->load->view("vwSearchResultQuestions",$data);
		}
	}

	public function heading(){
		$this->load->view("vwUserMenu");
		if (empty($_GET['searchString'])) {
			$this->load->view("vwToSearchHeading");
		}
		else{
			$data['searchResults']=$this->Mdl_general->headingSearch($_GET['searchString']);
			$this->load->view("vwSearchResultHeadings",$data);
		}
	}

	public function writer(){
		$this->load->view("vwUserMenu");
		if (empty($_GET['searchString'])) {
			$this->load->view("vwToSearchWriter");
		}
		else{
			$data['searchResults']=$this->Mdl_general->userSearch($_GET['searchString']);
			$this->load->view("vwSearchResultWriters",$data);
		}
	}

	public function sessionCheck(){
		$session_data = $this->session->get_userdata();
		if (is_null($session_data)) {
			return false;
		}
		else if (empty($session_data['tamilcoder'])) {
			return false;
		}
		else if ($session_data['tamilcoder']=="") {
			return false;
		}
		else{
			$ses=$session_data['tamilcoder'];
			return $ses['username'];
		}
	}

	private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
