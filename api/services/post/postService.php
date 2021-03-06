<?php
require_once("../wp-load.php");
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
			$sql = "SELECT * FROM wp_posts WHERE id_fotocasa='".$id."'";
            $exe = $db->query($sql);
            if(!is_null($exe)){
    			$data = $exe->fetch_all(MYSQLI_ASSOC);
            }else{
                $data = [];
            }
            return $data;
	  }

	  function seo_friendly_url($string){
		$string = str_replace(array('[\', \']'), '', $string);
		$string = preg_replace('/\[.*\]/U', '', $string);
		$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
		$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
		return strtolower(trim($string, '-'));
	}

	  public function delete(){
		$conn = new Connection;
		$db = $conn->getConnection();
		$sql = "DELETE FROM wp_posts WHERE id_fotocasa IS NOT NULL";
		$exe = $db->query($sql);
	  }

	  public function save($data, $tipo_p){
		$utils = new Utils;
			//$data = $utils->utf8_converter($data);
		if(count($this->getById($data->id)) == 0 || is_null($this->getById($data->id))){
			$conn = new Connection;
			$db = $conn->getConnection();
			$utils = new Utils;
			if($data->typeId == 5){
				$tipo_inmueble = "Local comercial"; //local comercial
			}elseif($data->typeId == 2){
				if($data->subtypeId == 2){
					$tipo_inmueble = "Apartamento";
				}elseif($data->subtypeId == 1){
					$tipo_inmueble = "Piso";
				}elseif($data->subtypeId == 54){
					$tipo_inmueble = "Estudio";
				}else{
					$tipo_inmueble = "Vivienda";
				}
			}elseif($data->typeId == 8){
				$tipo_inmueble = "Edificio";
			}
			$title = $tipo_inmueble." en ".$data->address->ubication;
			$name = str_replace(' ','-',$title);
			$name = str_replace(',','',$name."-".$data->id);
			$name = $this->seo_friendly_url($name);
			//$data = $utils->utf8_converter($data);
			$sql = "INSERT INTO wp_posts (post_author,post_date, post_date_gmt, post_modified, post_content, post_title, post_status, comment_status, ping_status, post_name, post_parent, post_type, post_mime_type, id_fotocasa, post_modified_gmt, post_excerpt, to_ping, pinged, post_content_filtered) VALUES (1, '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".utf8_decode($data->description)."', '".utf8_decode($title)."', 'publish', 'closed', 'closed', '".$name."', 0, 'property', '', '".$data->id."', '".substr($data->date,0,19)."', '', '', '', '');";
			$exe = $db->query($sql);
			$this->registrarImagenes($data, $db->insert_id, $tipo_p);
		}
	}
	  
	  public function registrar($data, $tipo_p){
	      
			//$exe = $db->query($sql);
			var_dump(count($data->transactions));
			if(count($data->transactions) > 1){
				for($i=0;$i<count($data->transactions);$i++){
					if($data->transactions[$i]->transactionTypeId == 1){
						$tipo_p = "venta";
					}else{
						$tipo_p = "alquiler";
					}
					if($i>0){
						$data->id = $data->id+1000;
						array_shift($data->transactions);
					}
					//var_dump($data->id);
					$this->save($data, $tipo_p);
					//var_dump($data->transactions[$i]->transactionTypeId);
					
					
					//var_dump($data->transactions[$i]->transactionTypeId);
				}
				
			}else{
				$this->save($data, $tipo_p);
			}
			
			/*if($exe){
				//return $sql;
				return $this->registrarImagenes($data, $db->insert_id, $tipo_p);
			}else{
				$link = mysqli_connect('localhost', 'estudio2_it', 'impacto.1309.', 'estudio2_db');
				
				if (!$link) {
					die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
				}
				
				echo 'Connected... ' . mysqli_get_host_info($link) . "\n";
				return array("estatus"=>"500", "mensaje"=>$exe);
			}*/
		}/*else{
			    $this->delete($data->id);
				$conn = new Connection;
				$db = $conn->getConnection();
				$utils = new Utils;
				if($data->typeId == 5){
					$tipo_inmueble = "Local comercial"; //local comercial
				}elseif($data->typeId == 2){
					if($data->subtypeId == 2){
						$tipo_inmueble = "Apartamento";
					}elseif($data->subtypeId == 1){
						$tipo_inmueble = "Piso";
					}elseif($data->subtypeId == 54){
						$tipo_inmueble = "Estudio";
					}else{
						$tipo_inmueble = "Vivienda";
					}
				}elseif($data->typeId == 8){
					$tipo_inmueble = "Edificio";
				}
				$title = $tipo_inmueble." en ".$data->address->ubication;
				$name = str_replace(' ','-',$title);
				$name = str_replace(',','',$name."-".$data->id);
				$name = $this->seo_friendly_url($name);
				//$data = $utils->utf8_converter($data);
				$sql = "INSERT INTO wp_posts (post_author,post_date, post_date_gmt, post_modified, post_content, post_title, post_status, comment_status, ping_status, post_name, post_parent, post_type, post_mime_type, id_fotocasa, post_modified_gmt, post_excerpt, to_ping, pinged, post_content_filtered) VALUES (1, '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".utf8_decode($data->description)."', '".utf8_decode($title)."', 'publish', 'closed', 'closed', '".$name."', 0, 'property', '', '".$data->id."', '".substr($data->date,0,19)."', '', '', '', '');";
				$exe = $db->query($sql);
				
				if($exe){
					//return $sql;
					return $this->registrarImagenes($data, $db->insert_id, $tipo_p);
				}else{
					$link = mysqli_connect('localhost', 'estudio2_it', 'impacto.1309.', 'estudio2_db');
					
					if (!$link) {
						die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
					}
					
					echo 'Connected... ' . mysqli_get_host_info($link) . "\n";
					return array("estatus"=>"500", "mensaje"=>$exe);
				}
		}*/

	function file_get_contents_curl($url, $dest) { 
		$ch = curl_init($url);
		$fp = fopen($dest, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	} 

  	public function registrarImagenes($data, $postId, $tipo_p){
		
		  $array = Array();
		  $imagenes = Array();
		  $img_id = Array();
		  for($i=0;$i<count($data->multimedias);$i++){
			//download
			$img = explode("/", $data->multimedias[$i]->url);
			$x = count($img);
			$name_img = __DIR__.'/../../../wp-content/uploads/2019/05/api-'.$img[$x-1];
			//var_dump($name_img);
			//if(!file_exists($name_img)){
				$this->file_get_contents_curl($data->multimedias[$i]->url, $name_img);
			//}
			//upload
			// full path of image
			/*$IMGFilePath = $name_img;
			//if(file_exists($name_img)){

				//prepare upload image to WordPress Media Library
				$upload = wp_upload_bits($name_img , null, file_get_contents($IMGFilePath, FILE_USE_INCLUDE_PATH));
			
				// check and return file type
				$imageFile = $upload['file'];
				$wpFileType = wp_check_filetype($imageFile, null);
			
				// Attachment attributes for file
				$attachment = array(
					'post_mime_type' => $wpFileType['type'],  // file type
					'post_title' => sanitize_file_name($imageFile),  // sanitize and use image name as file name
					'post_content' => '',  // could use the image description here as the content
					'post_status' => 'inherit'
				);
				// insert and return attachment id
				$attachmentId = wp_insert_attachment( $attachment, $imageFile, $postId );
				
				// insert and return attachment metadata
				$attachmentData = wp_generate_attachment_metadata( $attachmentId, $imageFile);
				
				// update and return attachment metadata
				wp_update_attachment_metadata( $attachmentId, $attachmentData );
				
				// finally, associate attachment id to post id
				if($i == 0){
					$success = set_post_thumbnail( $postId, $attachmentId );
				}
				//array_push($array, $sql);
				array_push($imagenes, $attachmentId);
			//}
		}
		*/
			$conn = new Connection;
			$db = $conn->getConnection();
			$guid = "http://estudiohenseldelavega.es/wp-content/uploads/2019/05/api-".$img[$x-1];
			$permanent = "wp-content/uploads/2019/05/api-".$img[$x-1];
			$name = "api-".$img[$x-1];
			$sql = "INSERT INTO wp_posts (post_author,post_date, post_date_gmt, post_modified, post_content, post_title, post_status, comment_status, ping_status, post_name, post_parent, post_type, post_mime_type, id_fotocasa, post_modified_gmt, post_excerpt, to_ping, pinged, post_content_filtered, guid) VALUES (1, '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '".substr($data->date,0,19)."', '', '".$name."', 'inherit', 'open', 'closed', '".str_replace('.jpg','',$permanent)."', 0, 'attachment', 'image/jpg', '".$data->id."', '".substr($data->date,0,19)."', '', '', '', '', '".$guid."');";
			$exe = $db->query($sql);
			array_push($array, $sql);
			array_push($img_id, $db->insert_id);
			array_push($imagenes, $img[$x-1]);
		  }
		  return  $this->metaImagenes($imagenes, $postId, $data, $tipo_p, $img_id);
		

	  }

	  public function metaImagenes($imagenes, $postId, $data, $tipo_p, $img_id){
		  $resul = Array();
		for($i=0;$i<count($imagenes);$i++){
			$conn = new Connection;
			$db = $conn->getConnection();
			$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_images', '".$img_id[$i]."');";
			$exe = $db->query($sql);
			array_push($resul, $sql);
			if($i==0){
				$conn = new Connection;
				$db = $conn->getConnection();
				$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '_thumbnail_id', '".$img_id[$i]."');";
				$exe = $db->query($sql);
			}
			$conn = new Connection;
			$db = $conn->getConnection();
			$meta_value = "2019/05/api-".$imagenes[$i];
			$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$img_id[$i]."', '_wp_attached_file', '".$meta_value."');";
			$exe = $db->query($sql);
		}
		return $this->metasUnicos($data, $postId, $tipo_p);
	  }

	  public function metasUnicos($data, $postId, $tipo_p){
		$resul = Array();
		//precio
		$conn = new Connection;
		$db = $conn->getConnection();
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_property_price', '".$data->transactions[0]->value[0]."');";
		$exe = $db->query($sql);
		array_push($data, $sql);
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
		$ciudad = 54;
		if(strpos($data->address->ubication, "Torrejón de Ardoz")){
			$ciudad = 58;
		}

		if(strpos($data->address->ubication, "Manzanares El Real")){
			$ciudad = 91;
		}

		if(strpos($data->address->ubication, "Buitrago del Lozoya")){
			$ciudad = 92;
		}
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', '_yoast_wpseo_primary_property-city', '".$ciudad."');";
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
		
		$direccion= $data->address->ubication.', '.$data->address->location->country.', '.$data->address->location->upperLevel;
		$sql = "INSERT INTO wp_postmeta (post_id,meta_key, meta_value) VALUES ('".$postId."', 'REAL_HOMES_gallery_slider_type', 'thumb-on-right');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		if($data->typeId == 5){
			$tipo = 53; //local comercial
		}elseif($data->typeId == 2){
			if($data->subtypeId == 2){
				$tipo = 57; //piso
			}elseif($data->subtypeId == 1){
				$tipo = 57; //piso
			}elseif($data->subtypeId == 54){
				$tipo = 57; //piso
			}else{
				$tipo = 63; //vivienda
			}
		}

		$sql = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id, term_order) VALUES ('".$postId."', '".$tipo."', '0');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		//ciudad madrid capital
		$ciudad = 54;
		if(strpos($data->address->ubication, "Torrejón de Ardoz")){
			$ciudad = 58;
		}

		if(strpos($data->address->ubication, "Manzanares El Real")){
			$ciudad = 91;
		}

		if(strpos($data->address->ubication, "Buitrago del Lozoya")){
			$ciudad = 92;
		}

		$sql = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id, term_order) VALUES ('".$postId."', '".$ciudad."', '0');";
		$exe = $db->query($sql);
		array_push($resul, $sql);

		$tipo_propiedad_id = 55; //venta
		if($tipo_p == "alquiler"){
			$tipo_propiedad_id = 56;
		}
		$sql = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id, term_order) VALUES ('".$postId."', '".$tipo_propiedad_id."', '0');";
		$exe = $db->query($sql);
		array_push($resul, $sql);
	  }
}