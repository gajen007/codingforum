<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mdl_user extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function deriveUserData($userName){
    return $this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row();
  }

  public function updateUserData($userID,$firstName,$lastName,$loggedInUserName,$userPassword,$aboutMe,$avatarURL){
    if ($this->db->query("SELECT * FROM users WHERE username='$loggedInUserName' AND id!='$userID'")->num_rows()==0) {
    	$newEncodedPassword=$this->db->query("SELECT * FROM users WHERE id='$userID'")->first_row()->password;
    	if ($userPassword!="") { $newEncodedPassword=md5($userPassword); }
  		if ($this->db->query("UPDATE users SET firstname='$firstName', lastname='$lastName', username='$loggedInUserName', password='$newEncodedPassword', aboutme='$aboutMe', avatarURL='$avatarURL' WHERE id='$userID'")) {
  			return array("result"=>true,"message"=>"உங்களின் சுயவிபரம் இற்றைப்படுத்தப்பட்டது. பாதுகாப்பு காரணங்களுக்காக வெளியேறி மீண்டும் உள்நுழையவும்!");
  		}
  		else{
  			return array("result"=>false,"message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.");
  		}
    }
    else{
      return array("result"=>false,"message"=>"இந்த மின்னஞ்சல் ஏற்கெனவே இன்னொருவருக்கு உள்ளது. வேறேதும் மின்னஞ்சலில் முயற்சிக்கவும்!");
    }
  }

  public function getUserData($userid,$loggedInUserName){
    //check whether this looking user is blocked by the targetted user
    $loggedInUserID=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
    $targetUser=$this->db->query("SELECT concat(u.firstname, ' ',u.lastname) as fullName, username as authorUserName, avatarURL, aboutme as aboutMe FROM users u WHERE id='$userid'")->first_row();
    $targetUser->{'questionsCount'}=$this->db->query("SELECT * FROM questions WHERE userid='$userid'")->num_rows();
    $targetUser->{'answersCount'}=$this->db->query("SELECT * FROM answers WHERE userid='$userid'")->num_rows();
    if ($this->db->query("SELECT * FROM usersFollowers WHERE starUserid='$userid' AND fanUserid='$loggedInUserID'")->num_rows()==0) { $targetUser->{'followingStatus'}="No"; }
    else{ $targetUser->{'followingStatus'}="Yes"; }
    $targetUser->{'followersCount'}=$this->db->query("SELECT * FROM usersFollowers WHERE starUserid='$userid'")->num_rows();
    return $targetUser;
  }

  public function followThisUser($starID,$loggedInUserName){
    $userid=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
    if ($this->db->query("SELECT * FROM usersFollowers WHERE starUserid='$starID' AND fanUserid='$userid'")->num_rows()==0) {
      if ($this->db->query("INSERT INTO usersFollowers (starUserid,fanUserid) VALUES ('$starID','$userid')")) { return array("message"=>"நீங்கள் இந்த நபரை பின்தொடர்கிறீர்கள்","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
    else{
      if ($this->db->query("DELETE FROM usersFollowers WHERE starUserid='$starID' AND fanUserid='$userid'")) { return array("message"=>"நீங்கள் இந்தப் நபரை பின்தொடரவில்லை","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
  }

  public function getFollowingUsersForThisUser($userName){
    $userID=$this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row()->id;
    return $this->db->query("SELECT  concat(u.firstname,' ',u.lastname) as starName, u.avatarURL, u.id as starUserid FROM usersFollowers uF  JOIN users u ON u.id=uF.starUserid WHERE uF.fanUserid='$userID'")->result(); 
  }

  public function updateAvatar($userid,$upload_path){
    if ($this->db->query("UPDATE users SET avatarURL='$upload_path' WHERE id='$userid'")) {
      return array("message"=>"உங்களின் நிழற்படம் பதிவேற்றப்பட்டது","result"=>true);
    }
    else{
      return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
    }
  }

}