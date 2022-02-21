<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mdl_general extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->library('session');
  }

  public function resetPassword($memberEmail){
    if ($this->db->query("SELECT * FROM users WHERE username='$memberEmail'")->num_rows()>0) {
      $currentTimestamp=time();
      $encoded=md5($currentTimestamp);
      $this->db->query("UPDATE users SET password='$encoded' WHERE username='$memberEmail'");
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: <info@tamilcoders.ca>' . "\r\n";
      $emailBody="<p>வணக்கம்..!</p><p>இது தமிழ்Coders தளத்துக்கான உங்களின் தற்காலிக கடவுச்சொல்.</p><h1>".$currentTimestamp."</h1><p>இதன்மூலம் நீங்கள் தமிழ்Coders தளத்தில் உள்நுழைந்து உங்களின் கடவுச்சொல்லை மாற்றிக்கொள்ளலாம்.</p><p>நன்றி</p>";
      mail($memberEmail,"தமிழ் Coders - கடவுச்சொல் மீட்டமைத்தல்",$emailBody,$headers);
      return array("message"=>"நன்றி; உங்களுக்கு மின்னஞ்சல் வெற்றிகரமாக அனுப்பப்பட்டது..!","result"=>true);
    }
    else{
      return array("message"=>"மன்னிக்கவும்; இந்த மின்னஞ்சல் முகவரியுடன் எவரும் தமிழ்Coders தளத்தில் உறுப்பினராக இல்லை.","result"=>true);
    }
  }

  public function getallUsers($userName){
    return $this->db->query("SELECT id, concat(firstname, ' ',lastname) as memberName FROM users")->result();
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
      return true;
    }
  }

  public function login($userName,$passWord){
    if ($this->db->query("SELECT * FROM users WHERE username='$userName' AND password=md5('$passWord')")->num_rows()==1) {
      return array("result"=>true,"message"=>"உள்நுழைகின்றீர்கள்..!");
    }
    else{
      return array("result"=>false,"message"=>"பிழையான பயனர்பெயர் அல்லது கடவுச்சொல்..!");
    }
  }
  public function signup($userName,$passWord){
    if ($this->db->query("SELECT * FROM users WHERE username='$userName'")->num_rows()==0) {
      if ($this->db->query("INSERT INTO users (username,password) VALUES('$userName',md5('$passWord'))")) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <info@tamilcoders.ca>' . "\r\n";
        mail("gajen007@gmail.com","Tamilcoders: New User Registration","User registered in Tamilcoders with the email ".$userName,$headers);
        return array("result"=>true,"message"=>"மகிழ்ச்சி! நீங்கள் இப்போது உள்நுழையலாம். உறுதிப்படுத்தும் மின்னஞ்சல் எதுவும் அனுப்பப்படவில்லை..!");
      }
      else{
        return array("result"=>true,"message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.");
      }
    }
    else{
      return array("result"=>false,"message"=>"இந்த மின்னஞ்சல் ஏற்கெனவே இன்னொருவருக்கு உள்ளது. வேறேதும் மின்னஞ்சலில் முயற்சிக்கவும். அல்லது உங்களின் கடவுச்சொல்லை மீட்கவும்.");
    }
  }

  public function questionSearch($searchKey){
    return $this->db->query("SELECT q.questionid, q.questionText FROM questions q WHERE q.questionText LIKE '%$searchKey%' AND q.status='ACTIVE'")->result();
  }

  public function headingSearch($searchKey){
    return $this->db->query("SELECT h.headingid, h.headingText FROM headings h WHERE h.headingText LIKE '%$searchKey%'")->result();
  }

  public function userSearch($searchKey){
    return $this->db->query("SELECT u.id, concat(u.firstname,' ',u.lastname) as userFullName FROM users u WHERE u.firstname LIKE '$searchKey%'")->result();
  }

  public function answerSearch($searchKey){
    return $this->db->query("SELECT a.answerid, q.questionText FROM answers a JOIN questions q ON q.questionid=a.questionid WHERE a.answerText LIKE '%$searchKey%' AND a.status='ACTIVE'")->result();
  }

}