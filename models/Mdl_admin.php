<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Mdl_admin extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  public function addBadWord($badWord){
    $escapedBadWord=$this->db->escape_str($badWord);
    if ($this->db->query("INSERT INTO badwords (badWord) VALUES('$escapedBadWord')")) {
      return array("message"=>"Bad Word has been added Successfully","result"=>true);
    }
    else{
      return array("message"=>"Database Error","result"=>true);
    }
  }

  public function scan($submittedStuff){
    $decoded=urldecode($submittedStuff);
    $escapedBadWord=$this->db->escape_str($decoded);
    $badWordCount=0;
    $pieces=explode(" ",$escapedBadWord);
    foreach ($pieces as $word) {
      if ($this->db->query("SELECT * FROM badwords WHERE badWord LIKE '$word'")->num_rows()>0) { $badWordCount+=1; }
    }
    if ($badWordCount>0) { return array("result"=>true,"message"=>"மன்னிக்கவும்; நீங்கள் Type செய்ததில் ஏதோ தகாத வார்த்தை(கள்) இருப்பதாக தெரிகின்றது. தயவு செய்து அவற்றை நீக்கி விடுங்கள்."); }
    else{ return array("result"=>false,"message"=>""); }
  }

  public function anyBadWords($submittedStuff){
    $escapedBadWord=$this->db->escape_str($submittedStuff);
    $badWordCount=0;
    $pieces=explode(" ",$escapedBadWord);
    foreach ($pieces as $word) {
      if ($this->db->query("SELECT * FROM badwords WHERE badWord LIKE '$word'")->num_rows()>0) { $badWordCount+=1; }
    }
    if ($badWordCount>0) { return true; }
    else{ return false; }
  }

}