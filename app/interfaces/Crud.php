<?php 

namespace m4\m4cms\interfaces;

interface Crud 
{
  
  public function create ();
  public function update ();
  public function save ();
  public function delete ();
  public function id ($id);
  public function list ();

}