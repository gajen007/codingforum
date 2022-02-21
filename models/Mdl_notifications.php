<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mdl_notifications extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function makeNotificationSeen($notificationid){
    if ($this->db->query("UPDATE notifications SET status='seen' WHERE notificationid='$notificationid'")) {
      return array("result"=>true);
    }
    else{
      return array("result"=>false,"message"=>"Database Error..!");
    }
  }

  public function getNotificationsForUser($username){
    $userid=$this->db->query("SELECT * FROM users WHERE username='$username'")->first_row()->id;
    return $this->db->query("SELECT * FROM notifications WHERE userid='$userid' and status='notseen'")->result();
  }

  public function getSettings($usermail){
    return $this->db->query("SELECT ns.settingid, ns.sendemail, ns.type1, ns.type2, ns.type3, ns.type4, ns.type5, ns.type6, ns.type7, ns.type8, ns.type9, ns.type10 FROM notificationSetting ns JOIN users u ON u.id=ns.userid WHERE u.username='$usermail'")->first_row();
  }

  public function modifySettings($username, $sendemail, $type1, $type2, $type3, $type4, $type5, $type6, $type7, $type8, $type9, $type10){
    $userid=$this->db->query("SELECT * FROM users WHERE username='$username'")->first_row()->id;
    if ($this->db->query("UPDATE notificationSetting SET sendemail=".$sendemail.", type1=".$type1.", type2=".$type2.", type3=".$type3.", type4=".$type4.", type5=".$type5.", type6=".$type6.", type7=".$type7.", type8=".$type8.", type9=".$type9.", type10=".$type10." WHERE userid='$userid'")) {
      return array("message"=>"அறிவிப்புகளுக்கான அமைப்புகள் வெற்றிகரமாக சேமிக்கப்பட்டன..!","result"=>true);
    }
    else{
      return array("message"=>"மன்னிக்கவும்; தரவுத்தள சிக்கல் ஒன்று ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.","result"=>false);
    }
  }

  public function notifyVote($answerID,$voterID,$voteType){
    if ($voteType=="up") {
      $voterName=$this->db->query("SELECT concat(u.firstname,' ',u.lastname) as voterName FROM users u WHERE u.id='$voterID'")->first_row()->voterName;
      $answerAuthorID=$this->db->query("SELECT * FROM answers WHERE answerid='$answerID'")->first_row()->userid;
      $questionText=$this->db->query("SELECT q.questionText FROM answers a JOIN questions q ON q.questionid=a.questionid WHERE a.answerid='$answerID'")->first_row()->questionText;
      $notificationText="கேள்வி <strong>".$questionText."</strong>ற்கான உங்களின் பதிலுக்கு <strong>".$voterName."</strong> ஆதரவுவாக்கு அளித்துள்ளார்";
      if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText','$answerAuthorID','answer','$answerID')")) {
          //check email setting and send email also
      }
    }
  }

  public function notifyComment($answerID,$commenterID){
      $commentorName=$this->db->query("SELECT concat(u.firstname,' ',u.lastname) as commentorName FROM users u WHERE u.id='$commenterID'")->first_row()->commentorName;
      $questionText=$this->db->query("SELECT q.questionText FROM answers a JOIN questions q ON q.questionid=a.questionid WHERE a.answerid='$answerID'")->first_row()->questionText;
    //to the user who wrote this answer  
      $answerAuthorID=$this->db->query("SELECT * FROM answers WHERE answerid='$answerID'")->first_row()->userid;
      if ($answerAuthorID!=$commenterID) {
        $notificationText1="கேள்வி <strong>".$questionText."</strong>ற்கான உங்களின் பதிலுக்கு <strong>".$commentorName."</strong> கருத்து இட்டுள்ளார்.";
        if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText1','$answerAuthorID','answer','$answerID')")) {
          //check email setting and send email also
        }
      }
    $followerIDs=array();
    $aF=$this->db->query("SELECT * FROM answersFollowers WHERE answerid='$answerID'")->result();
    foreach ($aF as $key) { $followerID=$key->userid; if ($followerID!=$commenterID) { array_push($followerIDs, $followerID); } }
    $commentorIDs=array();
    $aC=$this->db->query("SELECT * FROM comments WHERE answerid='$answerID'")->result();
    foreach ($aC as $key) { $previousCommentorID=$key->userid; if ($previousCommentorID!=$commenterID) { array_push($commentorIDs, $previousCommentorID); } }
    //to the people who commented and also following this answer
    $intersect=array_intersect($followerIDs, $commentorIDs);
    foreach ($intersect as $feedbackerID) {
      $notificationText4="கேள்வி <strong>".$questionText."</strong>யில் நீங்கள் பின்தொடரும் மற்றும் கருத்திட்ட பதிலுக்கு <strong>".$commentorName."</strong> கருத்து இட்டுள்ளார்.";
      if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText4','$feedbackerID','answer','$answerID')")) {
        //check email setting and send email also
      }  
    }
    //to the people who are only following this answer
    foreach ($followerIDs as $followerID) {
      if (!in_array($followerID, $intersect)) {
        $notificationText2="கேள்வி <strong>".$questionText."</strong>யில் நீங்கள் பின்தொடரும் பதிலுக்கு <strong>".$commentorName."</strong> கருத்து இட்டுள்ளார்.";
        if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText2','$followerID','answer','$answerID')")) {
          //check email setting and send email also
        }
      }
    }
    //to the people who only commented this answer
    foreach ($commentorIDs as $commentorID) {
      if (!in_array($commentorID, $intersect)) {
        $notificationText3="கேள்வி <strong>".$questionText."</strong>யில் நீங்கள் கருத்திட்ட பதிலுக்கு <strong>".$commentorName."</strong> கருத்து இட்டுள்ளார்.";
        if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText3','$previousCommentorID','answer','$answerID')")) {
          //check email setting and send email also
        }
      }
    }
  }

  public function notifyAnswerAdded($questionID,$authorID){
    $authorName=$this->db->query("SELECT concat(u.firstname,' ',u.lastname) AS authorName FROM users u WHERE u.id='$authorID'")->first_row()->authorName;
    $targetAnswerID=$this->db->query("SELECT * FROM answers WHERE userid='$authorID'")->first_row()->answerid;
    $questionText=$this->db->query("SELECT q.questionText FROM questions q WHERE q.questionid='$questionID'")->first_row()->questionText;
    $askedUserID=$this->db->query("SELECT * FROM questions WHERE questionid='$questionID'")->first_row()->userid;
    $notificationText1="நீங்கள் கேட்ட கேள்வி <strong>".$questionText."</strong> இற்கு <strong>".$authorName."</strong> பதில் அளித்துள்ளார்.";
    if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText1','$askedUserID','answer','$targetAnswerID')")) {
      //check email setting and send email also
    }
    $f=$this->db->query("SELECT * FROM questionsFollowers WHERE questionid='$questionID'")->result();
    foreach ($f as $key) {
      $followerID=$key->userid;
      $notificationText2="நீங்கள் பின்தொடரும் கேள்வி <strong>".$questionText."</strong> இற்கு <strong>".$authorName."</strong> பதில் அளித்துள்ளார்.";
      if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText2','$followerID','answer','$targetAnswerID')")) {
          //check email setting and send email also
      }
    }
  }

  public function notifyAnswerEdited($answerID){
    //to the person who asked the question
    //to the people who are following the answer
  }

  public function notifyQuestionAddedUnderHeading($headingID,$questionID){
    $headingText=$this->db->query("SELECT * FROM headings WHERE headingid='$headingID'")->first_row()->headingText;
    $questionText=$this->db->query("SELECT q.questionText FROM questions q WHERE q.questionid='$questionID'")->first_row()->questionText;
    $f=$this->db->query("SELECT * FROM headingsFollowers WHERE headingid='$headingID'")->result();
    foreach ($f as $key) {
      $followerID=$key->userid;
      $notificationText="நீங்கள் பின்தொடரும் தலைப்பு <strong>".$headingText."</strong> இல் சேர்க்கப்பட்ட கேள்வி <strong>".$questionText."</strong>";
      if ($this->db->query("INSERT INTO notifications (notificationtext,userid,targettype,targetid) VALUES('$notificationText','$followerID','question','$questionID')")) {
          //check email setting and send email also
      }
    }
  }

}