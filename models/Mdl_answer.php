<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mdl_answer extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function getVotes($answerID,$voteType){
    return $this->db->query("SELECT CONCAT(u.firstname,' ',u.lastname) as respondent, u.id as respondentid FROM feedbacks fb JOIN users u ON u.id=fb.userid WHERE feedback='$voteType' AND answerid='$answerID'")->result();
  }

  public function deleteOrModifyAnswer($answerID,$action){
    $targetStatus=""; $message="";
    if ($action=="delete") { $targetStatus="INACTIVE"; $message="பதில் நீக்கப்பட்டது"; }
    else if ($action=="revoke") { $targetStatus="ACTIVE"; $message="பதில் மீட்டமைக்கப்பட்டது"; }
    if ($this->db->query("UPDATE answers SET status='$targetStatus' WHERE answerid='$answerID'")) {
      return array("message"=>$message,"result"=>true);
    }
    else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
  }

  public function deriveAnswerForEditing($answerID){
    return $this->db->query("SELECT a.status, a.answerid, a.answerText, date(a.createdon) as answeredOn, u.id as userid, u.username as authorUserName, concat(u.firstname,' ',u.lastname) as authorName, q.questionText FROM answers a JOIN users u ON u.id=a.userid JOIN questions q ON q.questionid=a.questionid WHERE a.answerid='$answerID'")->first_row();
  }

  public function isThisAuthorOfAnswer($username,$answerID){
    return (($this->db->query("SELECT * FROM users WHERE username='$username'")->first_row()->id)==($this->db->query("SELECT * FROM answers WHERE answerid='$answerID'")->first_row()->userid));
  }

  public function getAnswersForQuestion($questionID){
    $authorUserName=$this->db->query("SELECT u.username FROM questions q JOIN users u ON u.id=q.userid WHERE q.questionid='$questionID'")->first_row()->username;
    $answers=$this->db->query("SELECT a.answerid, SUBSTRING(a.answerText, 1, 50) as answerTextBrief, date(a.createdon) as answeredOn, u.id as userid, u.username as authorUserName, concat(u.firstname,' ',u.lastname) as authorName FROM answers a JOIN users u ON u.id=a.userid WHERE a.questionid='$questionID' AND a.status='ACTIVE'")->result();
    return array("answers"=>$answers,"authorUserName"=>$authorUserName);
  }

  public function followThisAnswer($loggedInUserName,$answerID){
    $userid=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
    if ($this->db->query("SELECT * FROM answersFollowers WHERE answerid='$answerID' AND userid='$userid'")->num_rows()==0) {
      if ($this->db->query("INSERT INTO answersFollowers (answerid,userid) VALUES ('$answerID','$userid')")) { return array("message"=>"நீங்கள் இந்தப் பதிலை பின்தொடர்கிறீர்கள்","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
    else{
      if ($this->db->query("DELETE FROM answersFollowers WHERE answerid='$answerID' AND userid='$userid'")) { return array("message"=>"நீங்கள் இந்தப் பதிலை பின்தொடரவில்லை","result"=>true); }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
    }
  }

  public function updateAnswer($answerID,$editedAnswer){
    $this->load->model('Mdl_notifications');
    $escapedAnswer=$this->db->escape_str($editedAnswer);
    $this->load->model('Mdl_admin');
    if(!$this->Mdl_admin->anyBadWords($escapedAnswer)){
      if ($this->db->query("UPDATE answers SET answerText='$escapedAnswer' WHERE answerid='$answerID'")) {
        $questiond=$this->db->query("SELECT * FROM answers WHERE answerid='$answerID'")->first_row()->questionid;
        $this->Mdl_notifications->notifyAnswerEdited($answerID);
        return array("message"=>"பதில் வெற்றிகரமாக திருத்தப்பட்டது","questionid"=>$questiond,"result"=>true);
      }
      else{
        return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
      }
    }
    else{
      return array("message"=>"மன்னிக்கவும். உங்களின் பதிலில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் பதிலை சமர்ப்பிக்கவும்..!","result"=>false);
    }
  }

public function addAnswer($questionID,$wholeAnswer,$loggedInUserName){
  $this->load->model('Mdl_notifications');
  $userid=$this->db->query("SELECT * FROM users WHERE username='$loggedInUserName'")->first_row()->id;
  $escapedAnswer=$this->db->escape_str($wholeAnswer);
  $this->load->model('Mdl_admin');
  if(!$this->Mdl_admin->anyBadWords($escapedAnswer)){
   if ($this->db->query("INSERT INTO answers (answerText,questionid,userid) VALUES('$escapedAnswer','$questionID','$userid')")) {
    $this->Mdl_notifications->notifyAnswerAdded($questionID,$userid);
    return array("message"=>"பதில் வெற்றிகரமாக சேர்க்கப்பட்டது","result"=>true);
    }
    else{
      return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
    }
  }
  else{
    return array("message"=>"மன்னிக்கவும். உங்களின் பதிலில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் பதிலை சமர்ப்பிக்கவும்..!","result"=>false);
  }
}

public function getAnswerWithQuestion($answerID,$viewingUserName){
  $userid=$this->db->query("SELECT * FROM users WHERE username='$viewingUserName'")->first_row()->id;
  $answer=$this->db->query("SELECT a.status, a.answerid, a.answerText, date(a.createdon) as answeredOn, u.id as userid, u.username as authorUserName, concat(u.firstname,' ',u.lastname) as authorName, q.questionText FROM answers a JOIN users u ON u.id=a.userid JOIN questions q ON q.questionid=a.questionid WHERE a.answerid='$answerID'")->first_row();
  $answer->{'upvotes'}=$this->db->query("SELECT * FROM feedbacks WHERE feedback='up' AND answerid='$answerID'")->num_rows();
  $answer->{'downvotes'}=$this->db->query("SELECT * FROM feedbacks WHERE feedback='down' AND answerid='$answerID'")->num_rows();
  $answer->{'encodedcomments'}=json_encode($this->db->query("SELECT u.id as commenterID, concat (u.firstname,' ',u.lastname) as commenterName, c.commentid, c.comment, c.updatedon FROM comments c JOIN users u ON u.id=c.userid WHERE c.answerid='$answerID' ORDER BY c.updatedon DESC")->result());
  if ($this->db->query("SELECT * FROM feedbacks WHERE userid='$userid' AND answerid='$answerID'")->num_rows()!=0) {
    $answer->{'givenFeedback'}=$this->db->query("SELECT * FROM feedbacks WHERE userid='$userid' AND answerid='$answerID'")->first_row()->feedback;
  }
  else{
    $answer->{'givenFeedback'}="none";
  }
  if ($this->db->query("SELECT * FROM answersFollowers WHERE answerid='$answerID' AND userid='$userid'")->num_rows()==0) { $answer->{'followingStatus'}="No"; }
  else{ $answer->{'followingStatus'}="Yes"; }
  $answer->{'answerFollowersCount'}=$this->db->query("SELECT * FROM answersFollowers WHERE answerid='$answerID'")->num_rows();
  return $answer;
}

public function feedComment($answerID,$userName,$comment){
  $this->load->model('Mdl_notifications');
  $userid=$this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row()->id;
  $escapedComment=$this->db->escape_str($comment);
  $this->load->model('Mdl_admin');
  if(!$this->Mdl_admin->anyBadWords($escapedComment)){
    if ($this->db->query("INSERT INTO comments (comment,userid,answerid) VALUES('$escapedComment','$userid','$answerID')")) {
      $this->Mdl_notifications->notifyComment($answerID,$userid);
      return array("message"=>"உங்களின் கருத்து வெற்றிகரமாக சேர்க்கப்பட்டது","result"=>true);
    }
    else{
      return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
    }
  }
  else{
    return array("message"=>"மன்னிக்கவும். உங்களின் கருத்தில் சில தகாத வார்த்தைகள் இருப்பதாக தெரிகின்றது. தயவு செய்து அந்த வார்த்தைகளை நீக்கி விட்டு உங்களின் கருத்தை சமர்ப்பிக்கவும்..!","result"=>false);
  }
}

public function feedVote($answerID,$userName,$vote){
  $this->load->model('Mdl_notifications');
  $userid=$this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row()->id;
  if($this->db->query("SELECT * FROM feedbacks WHERE answerid='$answerID' AND userid='$userid'")->num_rows()!=0){
    if($vote!=$this->db->query("SELECT * FROM feedbacks WHERE answerid='$answerID' AND userid='$userid'")->first_row()->feedback){
      if ($this->db->query("UPDATE feedbacks SET feedback='$vote' WHERE answerid='$answerID' AND userid='$userid'")) {
        $this->Mdl_notifications->notifyVote($answerID,$userid,$vote);
        return array("message"=>"","result"=>true);
      }
      else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }        
    }
  }
  else{
    if ($this->db->query("INSERT INTO feedbacks(answerid,userid,feedback) VALUES('$answerID','$userid','$vote')")) {
      $this->Mdl_notifications->notifyVote($answerID,$userid,$vote);
      return array("message"=>"","result"=>true);
    }
    else{ return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false); }
  }
}

public function getAnswersOfUserWithItsQuestion($userName){
  return $this->db->query("SELECT a.answerid, a.answerText, SUBSTRING(a.answerText, 1, 250) as answerTextBrief, a.status, a.createdon, q.questionid, q.questionText, concat(u.firstname,'',u.lastname) as addedby FROM answers a JOIN users u ON u.id=a.userid JOIN questions q ON q.questionid=a.questionid WHERE u.username='$userName' AND a.status='ACTIVE'")->result();
}

public function getAnswersOfUserWithItsQuestionForViewing($authorID){
  $author=$this->db->query("SELECT * FROM users WHERE id='$authorID'")->first_row();
  $questions=$this->db->query("SELECT a.answerid, a.answerText, SUBSTRING(a.answerText, 1, 250) as answerTextBrief, a.status, a.createdon, q.questionid, q.questionText, concat(u.firstname,'',u.lastname) as addedby FROM answers a JOIN users u ON u.id=a.userid JOIN questions q ON q.questionid=a.questionid WHERE u.id='$authorID' AND a.status='ACTIVE'")->result();
  return array("questionsData"=>$questions,"authorName"=>$author->firstname." ".$author->lastname);
}

public function getFollowingAnswersOfUserWithItsQuestion($userName){
  $followingUserID=$this->db->query("SELECT * FROM users WHERE username='$userName'")->first_row()->id;
  return $this->db->query("SELECT  a.answerid,  a.answerText, SUBSTRING(a.answerText, 1, 250) as answerTextBrief, a.status,  a.createdon,  q.questionid,  q.questionText, uA.id as authorID, concat(uA.firstname,'',uA.lastname) as authorName  FROM answers a  JOIN users uA ON uA.id=a.userid  JOIN answersFollowers aF on aF.answerid=a.answerid JOIN users uF ON uF.id=aF.userid JOIN questions q ON q.questionid=a.questionid WHERE aF.userid='$followingUserID' AND a.status='ACTIVE'")->result();
}

}