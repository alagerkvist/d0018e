<?php
  class Person{
    public $id;
    public $fname;
    public $lname;
    public $usertype;

    function __construct($i, $f, $l, $u){
      $this->id = $i;
      $this->fname = $f;
      $this->lname = $l;
      $this->usertype = $u;
    }
  }
?>
