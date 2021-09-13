	<?php
class consulta extends cris2_ei_cuadro
{
				
//where  es.descripcion like '%$where%'';	 filtro de toba



	public function get_listado_estados($where = null){

	$sql = 'SELECT  id_estado,
					descripcion	as estado
			FROM curlib.estado';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}
	public function get_listado_fotos(){

	$sql = 'SELECT  *
			FROM curlib.foto';
			return toba::db()->consultar($sql);

	}
	

public function get_editorial(){

	$sql = 'SELECT  nombre as autor			
			FROM curlib.editorial';
				
			return toba::db()->consultar($sql);

	}
	
		//metodo para recuperar el string de un numero
		static function get_nombre_autor($id=null){
			$where=null;
		if (!is_null($id)){
			$where = "id_autor = ". quote($id);
			}
			$datos = self::get_listado_autores($where);
			if (isset($datos['0'])){
				return $datos['0']['autor'];
			}
		return '';
	}

	static function get_busqueda_autor($texto=null)
	{
		$datos = array();
		if (!is_null($texto) && trim($texto) != ''){
			$where = 'nombre ILIKE ' . quote("$texto%");
			$datos = self::get_listado_autores($where);
		}
		return $datos;
	}
		
		public function get_listado_editorial($where = null){

	$sql = 'SELECT  id_editorial,
					nombre as editorial				
			FROM curlib.editorial';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

	public function get_listado_autores($where = null){

	$sql = 'SELECT  id_autor,
					nombre				
			FROM curlib.autor';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

 public function get_listado_Libros2($where = null){
	 	
		
		$sql = 'SELECT  id_libro,
						titulo,
						resumen,
						au.nombre as autor,
						ed.nombre as editorial,
						estante,
						li.id_estado,
						li.adquicision_id,
						ad.descripcion as adquisicion,
						li.id_genero,
						ge.descripcion as genero,
						isbn,
						anio,
						ejemplar,
						es.descripcion as estado,
						foto
						
			FROM curlib.libro as li
			JOIN curlib.autor as au ON li.id_autor = au.id_autor
		JOIN curlib.estado as es ON li.id_estado = es.id_estado
			JOIN curlib.editorial as ed ON li.id_editorial = ed.id_editorial
			left JOIN curlib.adquisicion as ad ON li.adquicision_id = ad.id_adquisicion
			 left JOIN curlib.genero as ge ON li.id_genero = ge.id_genero ';
			if (!is_null($where)){
				$sql .="WHERE $where";
			}
					
			return toba::db()->consultar($sql);
			}
			 public function get_listado_Libros4($where = null){
	 	
		
		$sql = 'SELECT  id_libro,
						titulo,
						resumen,
						au.nombre as autor,
						ed.nombre as editorial,
						estante,
						li.id_estado,
						li.adquicision_id,
						ad.descripcion as adquisicion,
						li.id_genero,
						ge.descripcion as genero,
						isbn,
						anio,
						ejemplar,
						es.descripcion as estado 
						
			FROM curlib.libro as li
			JOIN curlib.autor as au ON li.id_autor = au.id_autor
		JOIN curlib.estado as es ON li.id_estado = es.id_estado
			JOIN curlib.editorial as ed ON li.id_editorial = ed.id_editorial
			left JOIN curlib.adquisicion as ad ON li.adquicision_id = ad.id_adquisicion
			 left JOIN curlib.genero as ge ON li.id_genero = ge.id_genero ';
			if (!is_null($where)){
				$sql .="WHERE ($where) and li.id_estado=1";
			} else
			{
				$sql .="WHERE li.id_estado=1";
			}
					
			return toba::db()->consultar($sql);
			}

			public function get_listado_estados3($where = null){
	 	
		
		$sql = 'SELECT  *						
			FROM curlib.estado ';
			if (!is_null($where)){
				$sql .="WHERE $where";
			}
					
			return toba::db()->consultar($sql);
			}

			public function get_listado_estados2($where = null){

	$sql = 'SELECT  *
			FROM curlib.estado';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

	public function get_listado_generos2($where = null){

	$sql = 'SELECT  
	                   id_genero,
						ge.descripcion as genero, 
						ge.id_estado,
						es.descripcion as estado
			FROM curlib.genero as ge
			JOIN curlib.estado as es ON ge.id_estado = es.id_estado ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

		public function get_listado_autores2($where = null){

	$sql = 'SELECT  
	                   id_autor,
	                   nombre,
						au.id_estado,
						es.descripcion as estado
			FROM curlib.autor as au
			JOIN curlib.estado as es ON au.id_estado = es.id_estado ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

		public function get_listado_categoria($where = null){

	$sql = 'SELECT  
	          ca.id_categoria,
	          ca.descripcion as descripcion,
						ca.id_estado as id_e,
						es.descripcion as estado
			FROM curlib.categoria as ca
			JOIN curlib.estado as es ON ca.id_estado = es.id_estado ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

	public function get_listado_editorial2($where = null){

	$sql = 'SELECT  id_editorial,
					nombre,
					domicilio,
					persona_contacto,
					telefonos,
					ed.id_estado,
						es.descripcion as estado
			FROM curlib.editorial as ed
			JOIN curlib.estado as es ON ed.id_estado = es.id_estado ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}


   public function get_persona(){

   	$sql = 'SELECT persona_natural.id_persona, 
   				   apyn,
   				   fe_nac,
   				   persona.cuil_tipo as cuil_tipo,
   				   persona.cuil_documento,
   				   persona.email as email
   				   FROM cidig.persona_natural
   				   JOIN cidig.persona ON cidig.persona_natural.id_persona = cidig.persona.id_persona limit 50';
   				   return toba::db()->consultar($sql);
   }

  public function get_persona10($where = null){

   	$sql = 'SELECT persona.id_persona,
   	persona.id_persona_tipo,
   	cidig.persona_tipo.descripcion as persona_tipo,
   	cuil_documento,
   	email,
   	persona.id_estado,
   	es.descripcion as estado,
   	persona.id_tipo_documento,
   	ti.descripcion as tipo,
   	persona.id_nacionalidad_d,
   	na.descripcion as nacionalidad,
   		persona_natural.id_persona_natural,
   	persona_natural.apyn,
   	control_doc
   				   FROM cidig.persona
   				    JOIN cidig.persona_tipo ON cidig.persona_tipo.id_persona_tipo = cidig.persona.id_persona_tipo
   				    JOIN curlib.estado as es ON persona.id_estado = es.id_estado
   				    JOIN cidig.tipo_documento as ti ON persona.id_tipo_documento = ti.id_tipo_documento
   				    JOIN cidig.nacionalidad as na ON persona.id_nacionalidad_d = na.id_nacionalidad
   				     JOIN cidig.persona_natural ON cidig.persona_natural.id_persona = cidig.persona.id_persona
   				  limit 50 ';
				if (!is_null($where)){
					 	$sql = 'SELECT persona.id_persona,
   	persona.id_persona_tipo,
   	cidig.persona_tipo.descripcion as persona_tipo,
   	cuil_documento,
   	email,
   	persona.id_estado,
   	es.descripcion as estado,
   	persona.id_tipo_documento,
   	ti.descripcion as tipo,
   	persona.id_nacionalidad_d,
   	na.descripcion as nacionalidad,
   		persona_natural.id_persona_natural,
   	persona_natural.apyn,
   	control_doc
   				   FROM cidig.persona
   				    JOIN cidig.persona_tipo ON cidig.persona_tipo.id_persona_tipo = cidig.persona.id_persona_tipo
   				    JOIN curlib.estado as es ON persona.id_estado = es.id_estado
   				    JOIN cidig.tipo_documento as ti ON persona.id_tipo_documento = ti.id_tipo_documento
   				    JOIN cidig.nacionalidad as na ON persona.id_nacionalidad_d = na.id_nacionalidad
   				     JOIN cidig.persona_natural ON cidig.persona_natural.id_persona = cidig.persona.id_persona
   				    ';
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}
    public function get_persona11($where = null){

   	$sql = 'SELECT 
   	          id_persona_natural,
   	          persona.id_persona as id_persona,
   						persona_natural.id_persona, 
   				   persona_natural.apyn as apyn,
   				   persona_natural.fe_nac,
   				   persona_natural.id_nacionalidad,
   				   na.descripcion as nacionalidad,
   				  persona_natural.ciudad_nac,
   				  persona_natural.provincia_nac,
   				  persona_natural.id_sexo,
   				  se.descripcion as sexo,
   				  persona.cuil_documento as documento
   				   FROM cidig.persona_natural
   				   left JOIN cidig.persona ON cidig.persona_natural.id_persona = cidig.persona.id_persona
   				   JOIN cidig.nacionalidad as na ON persona.id_nacionalidad_d = na.id_nacionalidad
   				   JOIN cidig.sexo as se ON persona_natural.id_sexo = se.id_sexo limit 50 ';
				if (!is_null($where)){

   	$sql = 'SELECT 
				
				persona.id_persona,
				persona_natural.id_persona_natural,
				persona_natural.apyn as apyn,
   				   persona_natural.fe_nac,
   				   persona_natural.id_nacionalidad,
   				   na.descripcion as nacionalidad,
   				  persona_natural.ciudad_nac,
   				  persona_natural.provincia_nac,
   				  persona_natural.id_sexo,
   				  se.descripcion as sexo,
   				  persona.cuil_documento as documento
   				FROM cidig.persona
   				left JOIN cidig.persona_natural ON cidig.persona_natural.id_persona = cidig.persona.id_persona
   				 JOIN cidig.nacionalidad as na ON persona.id_nacionalidad_d = na.id_nacionalidad
   				   JOIN cidig.sexo as se ON persona_natural.id_sexo = se.id_sexo
   				 ';
				$sql .="WHERE $where";

			}
			return toba::db()->consultar($sql);

	}

   //no funciona el filtro con el limit
     public function get_persona3($where = null){

   	$sql = 'SELECT persona_natural.id_persona, 
   				   apyn,
   				   persona.cuil_documento,
   				    persona.email as email
   				   FROM cidig.persona_natural
   				   JOIN cidig.persona ON cidig.persona_natural.id_persona = cidig.persona.id_persona limit 50 ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

	public function get_persona2($where = null){

   	$sql = 'SELECT persona.id_persona as id_persona, 
   				   persona_natural.apyn as apyn,
   				   persona.cuil_documento as cuil_documento,
   				    persona.email as email
   				   FROM cidig.persona
   				   JOIN cidig.persona_natural ON cidig.persona.id_persona = cidig.persona_natural.id_persona limit 50 ';
				if (!is_null($where)){
						$sql = 'SELECT persona.id_persona as id_persona, 
   				   persona_natural.apyn as apyn,
   				   persona.cuil_documento as cuil_documento,
   				    persona.email as email
   				   FROM cidig.persona
   				   JOIN cidig.persona_natural ON cidig.persona.id_persona = cidig.persona_natural.id_persona ';
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}


	public function get_listado_adquisicion($where = null){

	$sql = 'SELECT id_adquisicion,
	descripcion
			FROM curlib.adquisicion ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}

								//id_prestamo,
   							//	pre.libro_id,
   							//	li.titulo as titulo,
   							//	pre.persona_id,
   							//	pe.cuil_documento as persona,
   							//	fecha_alta,
  							// 	plazo,
  							// 	fecha_venc
 public function get_prestamo(){

   	$sql = 'SELECT 
   								id_prestamo,
   								pre.libro_id,
   								li.titulo as titulo,
   								pre.persona_id,
   							 per.apyn as persona,
   							 fecha_alta,
  							plazo,
  							fecha_venc
   				   FROM curlib.prestamo as pre
   				   JOIN curlib.libro as li ON pre.libro_id = li.id_libro
   				   JOIN cidig.persona_natural as per ON pre.persona_id = per.id_persona';
   				   return toba::db()->consultar($sql);
   }

   public function get_prestamo2($where = null){

   	$sql = 'SELECT 
   								id_prestamo,
   								pre.libro_id,
   								pre.persona_id,
   								per.apyn as nombre,
   								li.titulo as titulo,
   								fecha_alta,
  							 	plazo,
  							 	fecha_venc,
  							 	devolucion,
  							 	fecha_devolucion,
  							 	dias_retraso,
  							 	cantidad
   				   FROM curlib.prestamo as pre
   				   left JOIN curlib.libro as li ON pre.libro_id = li.id_libro
   				    left JOIN cidig.persona_natural as per ON pre.persona_id = per.id_persona ';
			if (!is_null($where)){
				$sql .="WHERE $where";
			}
					
			return toba::db()->consultar($sql);
			}

		
					
		//	function libro_disponible($id_libro){
		//	$sql ='UPDATE curlib.libro SET id_estado=1 WHERE id_libro=$id_libro';
		//	return toba::db()->consultar($sql);
		//}

		public function get_libro2($where = null){
	 	
		
		$sql = 'SELECT  id_libro,
						titulo										
			FROM curlib.libro';
			if (!is_null($where)){
				$sql .="WHERE $where";
			}
					
			return toba::db()->consultar($sql);
			}

 public function get_persona12($where = null){

   	$sql = 'SELECT pe.id_persona,
   	id_persona_tipo,
   	cuil_tipo,
   	cuil_documento,
   	cuil_digito,
   	cuil,
   	id_cond_fiscal,
   	email,
   	id_estado,
   	id_tipo_documento,
   	id_nacionalidad,
   	control_doc,
   	pn.apyn as apyn,
   	pn.id_persona_natural as id_persona_natural
   FROM cidig.persona as pe
    left JOIN cidig.persona_natural as pn ON pe.id_persona = pn.id_persona
   				  limit 100 ';
				if (!is_null($where)){
					$sql = 'SELECT pe.id_persona,
   	id_persona_tipo,
   	cuil_tipo,
   	cuil_documento,
   	cuil_digito,
   	cuil,
   	id_cond_fiscal,
   	email,
   	id_estado,
   	id_tipo_documento,
   	id_nacionalidad,
   	control_doc,
   	pn.apyn as apyn,
   		pn.id_persona_natural as id_persona_natural
   FROM cidig.persona as pe
    left JOIN cidig.persona_natural as pn ON pe.id_persona = pn.id_persona ';
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

}

   	public function listado_persona2($where = null){

   	$sql = 'SELECT persona.id_persona as id_persona, 
   				   persona_natural.apyn as nombre  				   
   				   FROM cidig.persona
   				   JOIN cidig.persona_natural ON cidig.persona.id_persona = cidig.persona_natural.id_persona limit 50 ';
				if (!is_null($where)){
				$sql .="WHERE $where";
			}
			return toba::db()->consultar($sql);

	}
}
 ?>
