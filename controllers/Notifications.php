<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notifications extends CI_Controller {
	public function __construct($config="rest") {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Mdl_general');
		$this->load->model('Mdl_notifications');
	}

	public function index(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
    		$data['username'] = $session_data['username'];
			$this->load->view('vwUserMenu',$data);
			$this->load->view('vwNotifications');
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}

	public function makeNotificationSeen(){
		$this->sendJson($this->Mdl_notifications->makeNotificationSeen($_POST['notificationid']));
	}

	public function unseennotificationscount(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$this->sendJson($this->Mdl_notifications->getNotificationsForUser($session_data['username']));
		}
	}

	public function getNotificationsForUser(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$this->sendJson($this->Mdl_notifications->getNotificationsForUser($session_data['username']));
		}
		else{
			array("message"=>"Session Expired","result"=>false);
		}
	}

	public function settings(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
    		$data['username'] = $session_data['username'];
			$data['ns'] = $this->Mdl_notifications->getSettings($session_data['username']);
			$this->load->view('vwUserMenu',$data);
			$this->load->view('vwNotificationSettings',$data);
		}
		else{
			$this->load->view('vwGuestMenu');
			$this->load->view('vwGuestHome');
		}
	}

	public function modifySettings(){
		if ($this->Mdl_general->sessionCheck()) {
			$session_data = $this->session->userdata('tamilcoder');
			$this->sendJson($this->Mdl_notifications->modifySettings($session_data['username'], $_POST['sendemail'], $_POST['type1'], $_POST['type2'], $_POST['type3'], $_POST['type4'], $_POST['type5'], $_POST['type6'], $_POST['type7'], $_POST['type8'], $_POST['type9'], $_POST['type10']));
		}
		else{
			$this->sendJson(array("message"=>"Session Expired","result"=>false));
		}
	}

	private function sendJson($data) {
		$this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
