<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Mdl_question extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function followThisQuestion($loggedInUserName,$questionID){
    $userid=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
    if ($this->db->query("SELECT * FROM questionsFollowers WHERE questionid='$questionID' AND userid='$userid'")->num_rows()==0) {
      if ($this->db->query("INSERT INTO questionsFollowers (questionid,userid) VALUES ('$questionID','$userid')")) { return array("message"=>"நீங்கள் இந்தக் கேள்வியை பின்தொடர்கிறீர்கள்","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
    else{
      if ($this->db->query("DELETE FROM questionsFollowers WHERE questionid='$questionID' AND userid='$userid'")) { return array("message"=>"நீங்கள் இந்தக் கேள்வியை பின்தொடரவில்லை","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
  }
    
  public function addNewQuestion($userName,$questionText){
    $escapedQuestion=$this->db->escape_str($questionText);
    $userID=$this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row()->id;
    $timeSignature=date('Y-m-d h:i:s');
    $this->load->model('Mdl_admin');
    if(!$this->Mdl_admin->anyBadWords($escapedQuestion)){
      if ($this->db->query("INSERT INTO questions (questionText,userid,createdon) VALUES('$escapedQuestion','$userID','$timeSignature')")) {
        $question=$this->db->query("SELECT * FROM questions WHERE createdon='$timeSignature' AND userid='$userID'")->first_row();
        return array("message"=>"கேள்வி சேர்க்கப்பட்டது","result"=>true,"questionID"=>$question->questionid);
      }
      else{
        return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
      }
    }
    else{
      return array("message"=>"மன்னிக்கவும். உங்களின் கேள்வியில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் கேள்வியை சமர்ப்பிக்கவும்..!","result"=>false);
    }
  }
  
  public function deriveQuestion($questionid){
    $question=$this->db->query("SELECT * FROM questions WHERE questionid='$questionid'")->first_row();
    $questionPieces=explode(" ",$question->questionText);
    $allHeadings=array();
    foreach ($questionPieces as $piece) {
      $possibilities=$this->db->query("SELECT * FROM headings WHERE headingText like '".$piece."%'")->result();
      foreach ($possibilities as $possibility) {
        array_push($allHeadings,$possibility->headingText);
      }
    }
    $relations=$this->db->query("SELECT * FROM questionsHeadings WHERE questionid='$questionid'")->result();
    foreach ($relations as $relation) {
      $headingText=$this->db->query("SELECT * FROM headings WHERE headingid='$relation->headingid'")->first_row()->headingText;
      array_push($allHeadings,$headingText);
    }
    $filteredHeadings=array_unique($allHeadings);
    $question->{'encodedHeadings'}=json_encode($filteredHeadings);
    return $question;
  }
  
  public function deriveQuestionWithAnswers($questionid,$loggedInUserName){
    $question=$this->db->query("SELECT q.questionid, q.questionText, q.userid, q.status, q.createdon, u.username FROM questions q JOIN users u ON u.id=q.userid WHERE q.questionid='$questionid'")->first_row();
    $question->{'encodedAnswers'}=json_encode($this->db->query("SELECT a.answerid, SUBSTRING(a.answerText, 1, 300) as answerTextBrief, a.createdon as answercreatedon, u.id as userid, u.username as authorUsername, u.firstname, u.lastname FROM answers a JOIN users u ON u.id=a.userid WHERE a.questionid='$questionid' AND a.status='ACTIVE'")->result());
    if ($loggedInUserName=="guest") { $question->{'followingStatus'}="No"; }
    else{
      $loggedInUserID=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
      if ($this->db->query("SELECT * FROM questionsFollowers WHERE questionid='$questionid' AND userid='$loggedInUserID'")->num_rows()==0) { $question->{'followingStatus'}="No"; }
      else{ $question->{'followingStatus'}="Yes"; }      
    }
    $question->{'followersCount'}=$this->db->query("SELECT * FROM questionsFollowers WHERE questionid='$questionid'")->num_rows();
    return $question;
  }
  
  public function getQuestionsOfUser($userName){
    return $this->db->query("SELECT q.questionid, q.questionText, q.userid, q.status, q.createdon, concat(u.firstname,'',u.lastname) as addedby FROM questions q JOIN users u ON u.id=q.userid WHERE u.username='$userName' AND q.status='ACTIVE'")->result();
  }

  public function getQuestionsOfUserForViewing($authorID){
    $author=$this->db->query("SELECT * FROM users WHERE id='$authorID'")->first_row();
    $questions=$this->db->query("SELECT q.questionid, q.questionText, q.userid, q.status, q.createdon, concat(u.firstname,'',u.lastname) as addedby FROM questions q JOIN users u ON u.id=q.userid WHERE u.id='$authorID' AND q.status='ACTIVE' ORDER BY q.createdon DESC")->result();
    return array("questionsData"=>$questions,"authorName"=>$author->firstname." ".$author->lastname);
  }

  public function getFollowingQuestionsOfUser($userName){
   $userID=$this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row()->id;
   return $this->db->query("SELECT  q.questionid, q.questionText, q.createdon FROM questions q  JOIN questionsFollowers qF ON qF.questionid=q.questionid JOIN users u ON u.id=qF.userid WHERE qF.userid='$userID' AND q.status='ACTIVE' ORDER BY q.createdon DESC")->result(); 
  }
  
  public function getQuestionsForUser($userName){
    $customQuery="SELECT q.questionid, q.questionText, q.userid, q.status, q.createdon, concat(u.firstname,'',u.lastname) as addedby FROM questions q JOIN users u ON u.id=q.userid WHERE q.status='ACTIVE' ORDER BY q.createdon DESC";
    if ($userName!="all") {
      $customQuery="SELECT q.questionid, q.questionText, q.userid, q.status, q.createdon, concat(u.firstname,'',u.lastname) as addedby FROM questions q JOIN users u ON u.id=q.userid WHERE u.username!='$userName' AND q.status='ACTIVE' ORDER BY q.createdon DESC";
    }
    $questions=$this->db->query($customQuery)->result();
    foreach ($questions as $question) {
      $question->{'answersCount'}=$this->db->query("SELECT * FROM answers WHERE questionid='$question->questionid'")->num_rows();
      $question->{'followersCount'}=$this->db->query("SELECT * FROM questionsFollowers WHERE questionid='$question->questionid'")->num_rows();
    }
    return $questions;
  }
  
  public function modifyHeadingsForQuestion($questionID,$headingsString){
    $this->load->model('Mdl_admin');
    if(!$this->Mdl_admin->anyBadWords($headingsString)){
      $addedHeadings=0;
      $withCommas=urldecode($headingsString);
      $withSpaces=explode(",",$withCommas);
      foreach ($withSpaces as $withSpace) {
        $atlast=str_replace(" ","",$withSpace);
        $escapedHeading=$this->db->escape_str($atlast);
        if ($this->db->query("SELECT * FROM headings WHERE headingText='$escapedHeading'")->num_rows()==0) {
          if ($this->db->query("INSERT INTO headings(headingText) VALUES ('$escapedHeading')")) {
            $headingID=$this->db->query("SELECT * FROM headings WHERE headingText='$escapedHeading'")->first_row()->headingid;
            if ($this->db->query("INSERT INTO questionsHeadings (questionid,headingid) VALUES('$questionID','$headingID')")) {
              $addedHeadings+=1;
            }else{}
          }else{}
        }
      }
      return array("message"=>"நன்றி! இந்த கேள்வியோடு ".$addedHeadings." புதிய தலைப்புகள் சேர்க்கப்பட்டன!","result"=>true);
    }
    else{
      return array("message"=>"மன்னிக்கவும். உங்களின் தலைப்பில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் கேள்வியை சமர்ப்பிக்கவும்..!","result"=>false);
    }
  }

}