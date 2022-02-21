<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mdl_heading extends CI_Model {
    public function __construct() {
    parent::__construct();
  }

  public function addNewHeading($loggedInUserName,$headingText){
    $escapedHeading=$this->db->escape_str($headingText);
    $this->load->model('Mdl_admin');
    if(!$this->Mdl_admin->anyBadWords($escapedHeading)){
      $userID=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
      if ($this->db->query("INSERT INTO headings (headingText,addedUserID) VALUES('$escapedHeading','$userID')")) {
        return array("message"=>"தலைப்பு சேர்க்கப்பட்டது","result"=>true);
      }
      else{
        return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
      }
    }
    else{
      return array("message"=>"மன்னிக்கவும். உங்களின் தலைப்பில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் தலைப்பை சமர்ப்பிக்கவும்..!","result"=>false);
    }
  }

  public function addNewHeadingToQuestion($loggedInUserName,$questionID,$headingText){
    $escapedHeading=$this->db->escape_str($headingText);
    $this->load->model('Mdl_admin');
    if(!$this->Mdl_admin->anyBadWords($escapedHeading)){
      $this->load->model('Mdl_notifications');
      $userID=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
      if ($this->db->query("SELECT * FROM headings WHERE headingText='$escapedHeading'")->num_rows()==0) {
        if ($this->db->query("INSERT INTO headings (headingText,addedUserID) VALUES('$escapedHeading','$userID')")) {
          $addedHeadingID=$this->db->query("SELECT * FROM headings WHERE headingText='$escapedHeading'")->first_row()->headingid;
          if ($this->db->query("INSERT INTO questionsHeadings (questionid,headingid) VALUES ('$questionID','$addedHeadingID')")) {
            $this->Mdl_notifications->notifyQuestionAddedUnderHeading($addedHeadingID,$questionID);
            return array("message"=>"தலைப்பு சேர்க்கப்பட்டது","result"=>true);
          }
          else{
            return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);  
          }
        }
        else{
          return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
        }
      }
      else{
        $existingHeadingID=$this->db->query("SELECT * FROM headings WHERE headingText='$headingText'")->first_row()->headingid;
        if ($this->db->query("SELECT * FROM questionsHeadings WHERE questionid='$questionID' AND headingid='$existingHeadingID'")->num_rows()==0) {
          if ($this->db->query("INSERT INTO questionsHeadings (questionid,headingid) VALUES ('$questionID','$existingHeadingID')")) {
            $this->Mdl_notifications->notifyQuestionAddedUnderHeading($existingHeadingID,$questionID);
            return array("message"=>"தலைப்பு சேர்க்கப்பட்டது","result"=>true);
          }
          else{
            return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
          }
        }
        else{
          return array("message"=>"மன்னிக்கவும்; இந்த தலைப்பு ஏற்கெனவே இந்த கேள்வியுடன் சேர்க்கப்பட்டுள்ளது.","result"=>false);
        }
      }
    }
    else{
      return array("message"=>"மன்னிக்கவும். உங்களின் தலைப்பில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் தலைப்பை சமர்ப்பிக்கவும்..!","result"=>false);
    }
  }

  public function getFollowingHeadingsByThisUser($loggedInUserName){
    return $this->db->query("SELECT h.headingid, h.headingText FROM headingsFollowers hf JOIN headings h ON h.headingid=hf.headingid JOIN users u ON u.id=hf.userid WHERE u.username='$loggedInUserName'")->result();
  }

  public function getHeadingsForQuestion($questionID){
    return $this->db->query("SELECT q.questionText, h.headingid, h.headingText FROM questionsHeadings qh JOIN headings h ON h.headingid=qh.headingid JOIN questions q ON q.questionid=qh.questionid WHERE qh.questionid='$questionID'")->result();
  }

  public function getQuestionsOfHeading($headingID,$loggedInUserName){
    $questions=$this->db->query("SELECT q.questionid, q.questionText FROM questionsHeadings qh JOIN questions q ON q.questionid=qh.questionid WHERE qh.headingid='$headingID'")->result();
    $headingText=$this->db->query("SELECT * FROM headings WHERE headingid='$headingID'")->first_row()->headingText;
    $followingStatus="No";
    if ($loggedInUserName=="guest") { $followingStatus="No"; }
    else{
      $userID=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
      if ($this->db->query("SELECT * FROM headingsFollowers WHERE userid='$userID' AND headingid='$headingID'")->num_rows()!=0) { $followingStatus="Yes"; }  
    }
    return array("questionsData"=>$questions,"headingText"=>$headingText,"followingStatus"=>$followingStatus);
  }

  public function followThisHeading($loggedInUserName,$headingID){
     $userid=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
    if ($this->db->query("SELECT * FROM headingsFollowers WHERE headingid='$headingID' AND userid='$userid'")->num_rows()==0) {
      if ($this->db->query("INSERT INTO headingsFollowers (headingid,userid) VALUES ('$headingID','$userid')")) { return array("message"=>"நீங்கள் இந்தத் தலைப்பை பின்தொடர்கிறீர்கள்","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
    else{
      if ($this->db->query("DELETE FROM headingsFollowers WHERE headingid='$headingID' AND userid='$userid'")) { return array("message"=>"நீங்கள் இந்தத் தலைப்பை பின்தொடரவில்லை","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }   
  }

}