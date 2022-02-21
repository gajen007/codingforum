<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
	public function __construct($config="rest") {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		parent::__construct();
	    $this->load->library('session');
		$this->load->model('Mdl_general');
		$this->load->model('Mdl_admin');
	}
//$this->load->model('Mdl_notifications');

	public function index(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
    		if ($session_data['username']=="admin@tamilcoders.ca") {
    			$data['username'] = $session_data['username'];
    			$this->load->view('vwAdminMenu',$data);
				$this->load->view('vwAdminHome');
    		}
    		else{
    			redirect('');
    		}
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}

	}

	public function scan(){
		$this->sendJson($this->Mdl_admin->scan($_GET['typed']));
	}

	public function addBadWord(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			if ($session_data['username']=="admin@tamilcoders.ca") {
				$this->sendJson($this->Mdl_admin->addBadWord($_POST['badWord']));
			}
			else{
				$this->sendJson(array("message"=>"Access Denied","result"=>false));
			}
		}
		else{
			$this->sendJson(array("message"=>"Access Denied","result"=>false));
		}
	}

	public function allusers(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			if ($session_data['username']=="admin@tamilcoders.ca") {
				$data['username'] = $session_data['username'];
				$this->load->view('vwAdminMenu',$data);
				$this->load->view('vwAllUsers');
			}
			else{
    			redirect('');
			}
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