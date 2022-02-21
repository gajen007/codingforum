<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Logout extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
	    $this->load->library('session');
	}
	public function index(){
		$this->session->unset_userdata('tamilcoder');
		session_destroy();
		redirect('');
	}
}
