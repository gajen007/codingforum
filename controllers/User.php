<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {
	public function __construct($config="rest") {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		parent::__construct();
		$this->load->model('Mdl_general');
        $this->load->model('Mdl_user');
	}
	public function index(){
		$this->load->view('welcome_message');
	}
	public function deriveUserProfile(){
		$this->sendJson($this->Mdl_user->deriveUserData($_GET['userName']));
	}
	public function singleuser(){
		if ($this->Mdl_general->sessionCheck()) {
            if (!empty($_GET['userid'])) {
                $session_data = $this->session->userdata('tamilcoder');
                $data['username'] = $session_data['username'];
                $this->load->view('vwUserMenu',$data);
                $this->load->view('vwSingleUser',$data);
            }
            else{
                redirect('/');
            }
        }
        else{
            $this->load->view('vwGuestMenu');
            $this->load->view('vwGuestHome');
        }
	}
    public function updateUserProfile(){
        $this->sendJson($this->Mdl_user->updateUserData($_POST['userid'],$_POST['firstName'],$_POST['lastName'],$_POST['userEmail'],$_POST['userPassword'],$_POST['aboutMe'],$_POST['avatarURL']));
    }
	public function getUserData(){
		$this->sendJson($this->Mdl_user->getUserData($_GET['userid'],$_GET['lookingUserName']));
	}
	public function followThisUser(){
		$this->sendJson($this->Mdl_user->followThisUser($_POST['starID'],$_POST['loggedInUserName']));
	}
	public function getFollowingUsersForThisUser(){
		$this->sendJson($this->Mdl_user->getFollowingUsersForThisUser($_GET['userName']));	
	}
	public function updateAvatar(){
		if ($this->input->method()){
			if(!$_FILES) { $this->sendJson(array("message"=>"No File Selected","result"=>false)); }
			else{
				$upload_path = './images/userAvatars/'.$_POST['userid'].'/';
				//check the file extensions..!
				if (!file_exists($upload_path)) { mkdir($upload_path, 0777, true); }
				if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$upload_path.$_FILES['fileToUpload']['name'])) {
					$this->sendJson($this->Mdl_user->updateAvatar($_POST['userid'],$upload_path.$_FILES['fileToUpload']['name']));
				}
				else { $this->sendJson(array("message"=>"மன்னிக்கவும்; நிழற்படத்தை பதிவேற்ற முடியவில்லை! மீண்டும் முயற்சிக்கவும்.","result"=>false)); }
			}
		} else{ $this->sendJson(array("message"=>"மன்னிக்கவும். ஏதோ தவறாகி விட்டது. மீண்டும் முயற்சிக்கவும்!","result"=>false)); }
	}
    private function sendJson($data) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8')->set_output(json_encode($data));
	}
}
