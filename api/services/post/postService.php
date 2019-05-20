<?php

class Post{
      public function get(){
            $conn = new Connection;
            $db = $conn->getConnection();
            $sql = "SELECT * FROM wp_posts";
            $exe = $db->query($sql);
            $data = $exe->fetch_all(MYSQLI_ASSOC);
            $db = null;
            $utils = new Utils;
            return $utils->utf8_converter($data);
      }

/*      public function getByEstado($id_estado){
            $conn = new Connection;
            $db = $conn->getConnection();
            $sql = "SELECT * FROM bancos WHERE id_estado='".$id_estado."'";
            $exe = $db->query($sql);
            $data = $exe->fetch_all(MYSQLI_ASSOC);
            $db = null;
            $utils = new Utils;
            return $utils->utf8_converter($data);
      }*/

      public function getById($id){
            $conn = new Connection;
            $db = $conn->getConnection();
            $sql = "SELECT * FROM wp_posts WHERE id='".$id."'";
            $exe = $db->query($sql);
            $data = $exe->fetch_all(MYSQLI_ASSOC);
            $db = null;
            $utils = new Utils;
            $data_return = $utils->utf8_converter($data);
            return $data_return;
	  }
	  
	  public function registrar($data){
		$conn = new Connection;
		$db = $conn->getConnection();
		$data['password'] = sha1($data['password']);
		$sql = "INSERT INTO wp_post () VALUES ();";
		$exe = $db->query($sql);
		if($exe){
			return array("estatus"=>"200", "mensaje"=>"post registrado");
		}else{
			return array("estatus"=>"500", "mensaje"=>"Error al registrar post");
		}
  }
}