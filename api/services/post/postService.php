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
		$sql = "INSERT INTO wp_posts (post_author,post_date, post_date_gmt, post_modified, post_content, post_title, post_status, comment_status, ping_status, post_name, post_parent, post_type, post_mime_type, id_fotocasa, post_modified_gmt, post_excerpt, to_ping, pinged, post_content_filtered) VALUES (1, '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".$data->description."', '".$data->description."', 'publish', 'closed', 'closed', '".substr(str_replace(' ','-',$data->description),0,190)."', 0, 'property', '', '".$data->id."', '".substr($data->date,0,19)."', '', '', '', '');";
		$exe = $db->query($sql);
//		if($exe){
			//return $sql;
			return $this->registrarImagenes($data, $db->insert_id);
/*		}else{
			return array("estatus"=>"500", "mensaje"=>"Error al registrar post");
		}*/
  }

  	public function registrarImagenes($data, $postId){
		  $array = Array();
		  $imagenes = Array();
		  for($i=0;$i<count($data->multimedias);$i++){
			$conn = new Connection;
			$db = $conn->getConnection();
			$sql = "INSERT INTO wp_posts (post_author,post_date, post_date_gmt, post_modified, post_content, post_title, post_status, comment_status, ping_status, post_name, post_parent, post_type, post_mime_type, id_fotocasa, post_modified_gmt, post_excerpt, to_ping, pinged, post_content_filtered, guid) VALUES (1, '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '', '".$data->description."', 'inherit', 'open', 'closed', '".substr(str_replace(' ','-',$data->description),0,190)."', 0, 'attachment', 'image/png', '".$data->id."', '".substr($data->date,0,19)."', '', '', '', '', '".$data->multimedias[$i]->url."');";
			$exe = $db->query($sql);
			array_push($array, $sql);
			array_push($imagenes, $db->insert_id);
		  }
		  return  $this->metaImagenes($imagenes, $postId, $data);

		  //echo $data;
	  }

	  public function metaImagenes($imagenes, $postId, $data){
		  $resul = Array();
		for($i=0;$i<count($imagenes);$i++){
			$conn = new Connection;
			$db = $conn->getConnection();
			$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_images', '".$imagenes[$i]."');";
			$exe = $db->query($sql);
			array_push($resul, $sql);
		}
		return $this->metasUnicos($data, $postId);
	  }

	  public function metasUnicos($data, $postId){
		$resul = Array();
		//precio
		$conn = new Connection;
		$db = $conn->getConnection();
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_price', '".$data->transactions[0]->value[0]."');";
		$exe = $db->query($sql);
		array_push($resul, $sql);
		//dimensiones, habitaciones y baño
		for($i=0;$i<count($data->features);$i++){
			foreach($data->features[$i] as $key){
				if($key == "surface"){
					$real_homes = "REAL_HOMES_property_size";
				}elseif($key=="rooms"){
					$real_homes = "REAL_HOMES_property_bedrooms";
				}elseif($key=="bathrooms"){
					$real_homes = "REAL_HOMES_property_bathrooms";
				}else{
					$real_homes = null;
				}

				if(!is_null($real_homes)){
					
					$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '".$real_homes."', '".$data->features[$i]->value[0]."');";
					$exe = $db->query($sql);
					array_push($resul, $sql);
				}
			}
		}
		//unidad de medida
		
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_size_postfix', 'm2');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		//mapa
		
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_map', '0');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		//Coordenadas
		
		$coordenadas= $data->address->coordinates->latitude.','.$data->address->coordinates->longitude;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_location', '".$coordenadas."');";
		$exe = $db->query($sql);
		array_push($resul, $sql);
		
		//dirección
		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_address', '".$direccion."');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		//agentes
		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_agent_display_option', 'none');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_agents', '-1');";
		$exe = $db->query($sql);
		array_push($resul, $sql);
		
		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_featured', '0');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_add_in_slider', 'no');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '_yoast_wpseo_primary_property', '57');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '_yoast_wpseo_primary_property-city', '54');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '_yoast_wpseo_primary_property-status-city', '55');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '_yoast_wpseo_metadesc', '✅ Estudio Colombia ✅ empresa líder en venta y alquiler de INMUEBLES en San Lorenzo-Hortaleza con transparencia y confianza.');";
		$exe = $db->query($sql);
		array_push($resul, $sql);
	  }
}