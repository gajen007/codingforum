<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AddNew extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
	    $this->load->library('session');
	    $this->load->model('Mdl_general');
	}

    public function toAddNewQuestion(){
        if ($this->Mdl_general->sessionCheck()) {
            $session_data = $this->session->userdata('tamilcoder');
            $data['username'] = $session_data['username'];
            $this->load->view('vwUserMenu',$data);
            $this->load->view('vwToAddQuestion',$data);
        }
        else{
            $this->load->view('vwGuestMenu');
            $this->load->view('vwGuestHome');
        }
    }

    public function toAddNewHeading(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
            $data['username'] = $session_data['username'];
            $this->load->view('vwUserMenu',$data);
            $this->load->view('vwToAddHeading',$data);
        }
        else{
            $this->load->view('vwGuestMenu');
            $this->load->view('vwGuestHome');
        }
	}

	public function heading(){
		$this->load->view("vwUserMenu");
		$this->load->view("vwToAddHeading");
	}

	private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
