<?php
class ci_libros extends cris2_ci
{
	protected $s__filtro_data;
	 protected $s__path_inicial;
    protected $s__nom_img;
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		if (!$this->dep('datos')->esta_cargada()){
		 $this->dep('formulario')->evento('modif')->ocultar(); 
		}
		$this->set_pantalla('pant_edicion');
	}

	function evt__cancelar()
	{
		$this->set_pantalla('pant_inicial');
		$this->dep('datos')->resetear();
	}

	function evt__eliminar()
	{
		$this->dep('datos')->eliminar_todo();
		$this->set_pantalla('pant_inicial');
	}

	function evt__guardar()
	{
		
  $this->dep('datos')->sincronizar();
   $this->informar_msg('Persona Guardada exitosamente ', 'info');
		$this->set_pantalla('pant_inicial');
		$this->dep('datos')->resetear();

	}
	//-----------------------------------------------------------------------------------
	//---- filtro --------------------------- --------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(cris2_ei_filtro $filtro)
		{
			
		if (isset($this->s__filtro_data)){
			$filtro->set_datos($this->s__filtro_data);
		} 
	}

	function evt__filtro__filtrar($datos)
	{
		 
		$this->s__filtro_data= $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro_data);  
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(cris2_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_listado_Libros2($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
		
	}

	function evt__cuadro__seleccion($seleccion)
	{
		//ei_arbol($seleccion);
		$this->dep('datos')->cargar($seleccion);
		$this->set_pantalla('pant_edicion');
		if ($this->dep('datos')->esta_cargada()){
		 $this->dep('formulario')->evento('Guardar')->ocultar(); 
		}
	}


	function evt__formulario__modificacion($datos){
	//	ei_arbol($datos);
		$this->dep('datos')->set($datos);
		//$cuadro->set_datos($datos);
		//var_dump($datos);
		//exit();
		 // $this->dep('datos')->tabla('libro')->procesar_filas($datos); CN

	}

	function conf__formulario($form)
	{

		$img ="";
		$datos = $this->dep('datos')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
		$img = $datos['foto'];
      //var_dump($img);
      //$s__nom_img=$img;
		//var_dump($img);
		if ($img == '')
		{
			$form->ef('imagen_grafica')->set_estado("No tiene cargada una Foto");
		}else{
      $form->ef('imagen_grafica')->set_estado("<img src= '$img' width=150px height=auto>");

      }
	
	
	}
	
	

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	//function conf__pant_edicion(toba_ei_pantalla $pantalla)
	//{
	//	if (!$this->dep('datos')->esta_cargada()){
	//		$pantalla->eliminar_evento('eliminar');
	//	}
	//}



	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
		
	function evt__formulario__Guardar($datos)
	{

	//empieza el guardar
		if ($this->conexion()!=true)
		{
			echo "localhost";
		
		if ((is_null($datos['foto'])))
				{
			$datos['foto']="Sin Foto";
				}
		else{
			 	//delcaro el nombre de la variable a guardar
   	  	 $foto_guardar = "";
  	    //declaro la variable a trabajar sobre el archivo temporarl
      	$foto_tmp = "";
      	//declaro la variable con el arreglo de la foto del upload
      	$imagen_a_tratar = ($datos['foto']);
      
    		//declaro la ruta donde voy a guardar las fotos
    		//$ruta_final = 'img/imagenes/';

    		//obtengo la ruta inicial del archivo
    		//$this->s__path_inicial = toba::proyecto()->get_www($ruta_final);

   		 //declaro la ruta inicial del archivo
   		 //$ruta_inicial = $this->s__path_inicial['path']; 


    		//$path = $ruta_inicial;
    
    		//pregunto si existe el fichero donde voy a guardar los archivos
    	//	if (!file_exists($path))
    	//	{
     		 //si no existe la ruta, la creo y doy permisos de adm
     		// mkdir($path);
     		 //doy peromisos de adm
     		// chmod($path, 0777);
    	//	}
      //guardo el nombre de la imagen
    	$nombre_img = basename($datos['foto']['name']);
      //guardo el nombre del archivo temporal
   	 $foto_tmp = $datos['foto']['tmp_name'];


      //creo un numero aletario para el nombre del archivo
    	$num_ram= mt_rand(0,10000);
    	$ruta_final="/var/www/documentos/libros/";
    	$foto_guardar = $ruta_final . $nombre_img;
    //separo la ruta a partir del punto
    $arreglo = explode(".",$foto_guardar);
    //concateno el numero aleatorio y lo guardo en $foto_guardar
   $foto_guardar = $arreglo[0] . $num_ram . "." . $arreglo[1];
   //$datos["foto"]=$foto_guardar;
     // $foto_guardar = $arreglo[0] . "." . $arreglo[1];
  $sftp=$this->conexion_ssh();
  //var_dump($sftp);
  //exit();

      //pregunto si cargo, no es necesario el if
    if (move_uploaded_file($foto_tmp, 'ssh2.sftp://'.$sftp.$foto_guardar)) 
  	  {
      echo 'cargo';      
      //  $this->informar_msg('Se pudo cargar la imagen ','exito'); 

      $datos["foto"]=$foto_guardar;

    
       
     
   	 }
   // else
   // {
     // echo 'no cargo';
    //    $this->informar_msg('No se puedo cargar la foto ','error');
    //}
	}
}

else{


	echo "servidor desarrollo";
	//delcaro el nombre de la variable a guardar
   	  	 $foto_guardar = "";
  	    //declaro la variable a trabajar sobre el archivo temporarl
      	$foto_tmp = "";
      	//declaro la variable con el arreglo de la foto del upload
      	$imagen_a_tratar = ($datos['foto']);
      
    		//declaro la ruta donde voy a guardar las fotos
    		//$ruta_final = 'img/imagenes/';
      		$ruta_final="/var/www/documentos/libros/";
    		//obtengo la ruta inicial del archivo
    		//$this->s__path_inicial = toba::proyecto()->get_www($ruta_final);

   		 //declaro la ruta inicial del archivo
   		 //$ruta_inicial = $this->s__path_inicial['path']; 


    		//$path = $ruta_inicial;
    
    		//pregunto si existe el fichero donde voy a guardar los archivos
    		if (!file_exists($ruta_final))
    		{
     		 //si no existe la ruta, la creo y doy permisos de adm
     		 mkdir($ruta_final);
     		 //doy peromisos de adm
     		 chmod($ruta_final, 0777);
    	  }
      //guardo el nombre de la imagen
    	$nombre_img = basename($datos['foto']['name']);
      //guardo el nombre del archivo temporal
   	 $foto_tmp = $datos['foto']['tmp_name'];


      //creo un numero aletario para el nombre del archivo
    	$num_ram= mt_rand(0,10000);
    	$ruta_final="/var/www/documentos/libros/";
    	$foto_guardar = $ruta_final . $nombre_img;
    //separo la ruta a partir del punto
    $arreglo = explode(".",$foto_guardar);
    //concateno el numero aleatorio y lo guardo en $foto_guardar
   $foto_guardar = $arreglo[0] . $num_ram . "." . $arreglo[1];
   //$datos["foto"]=$foto_guardar;
     // $foto_guardar = $arreglo[0] . "." . $arreglo[1];
  //$sftp=$this->conexion_ssh();
  //var_dump($sftp);
  //exit();

      //pregunto si cargo, no es necesario el if
    if (move_uploaded_file($foto_tmp,$foto_guardar)) 
  	  {
      echo 'cargo';      
      //  $this->informar_msg('Se pudo cargar la imagen ','exito'); 

      $datos["foto"]=$foto_guardar;

    
       
     
   	 }
}
	$this->set_pantalla('pant_inicial');
       $this->dep('datos')->set($datos);
     	 $this->dep('datos')->sincronizar();
  		 $this->informar_msg('Libro Guardado exitosamente ', 'info');
		   $this->set_pantalla('pant_inicial');
		   $this->dep('datos')->resetear(); 
	}

	function evt__formulario__Borrar($datos)
	{
		$aux=$datos['id_libro'];
   // $cadena = "'$aux'";
     $ar =toba::db()->consultar("SELECT foto
     FROM curlib.libro where id_libro= $aux;");
     $aux=$ar[0]['foto'];
		//$aux=$datos['foto'];
      //var_dump($aux); 
     // unlink($aux);
    // exit();
   
   // echo "Borrado completado";
   
    //var_dump($s__nom_img);
    //exit();
   
		if ($this->conexion()!=true)
		{
			//echo "localhost";
			 $sftp=$this->conexion_ssh();
			 If (unlink('ssh2.sftp://'.$sftp.$aux)) {
  // file was successfully deleted
        $this->informar_msg('Borrado ','exito');
         $this->dep('datos')->eliminar_todo();
    $this->set_pantalla('pant_inicial');
} //termina if
//else {
  // there was a problem deleting the file
  //  $this->informar_msg('No se borro ','fallo');
    
//}//termina else
		}
		else
		{
 If (unlink($aux)) {
  // file was successfully deleted
        $this->informar_msg('Borrado ','exito');
         $this->dep('datos')->eliminar_todo();
    $this->set_pantalla('pant_inicial');
} //termina if
//else {
  // there was a problem deleting the file
 //   $this->informar_msg('No se borro ','fallo');
    
//}//termina else
		}
	  

	}//termina funcion

	function conexion()
	{
		if($_SERVER['SERVER_NAME'] == "desarrollo.ciudaddecorrientes.gov.ar")
		{
			$valor=true;
			return $valor;
		}
		elseif($_SERVER['SERVER_NAME'] == "localhost"){ 
			$valor=false;
			return $valor;
		}
	}

function conexion_ssh()
	{
		$sftp= null;
		if(!($conexion_ssh = ssh2_connect('192.168.10.200',22)))
		{
			toba::notificacion()->vaciar();
			toba::notificacion()->set_titulo('Biblioteca');
			toba::notificacion()->agregar('ATENCION: Ha fallado la conexion SSH con el servidor de Desarrollo.<br> Las imágenes no se descargaran apropiadamente.');
		}
		else{ 
			ssh2_auth_password($conexion_ssh, 'root', 'roda1950');
			$sftp = ssh2_sftp($conexion_ssh);
		}
		return $sftp;
	}


	function evt__formulario__modif($datos)
	{
		//var_dump($this->conexion());
		if ($this->conexion()!=true)
		{
			echo "localhost";
		$aux=$datos['id_libro'];  
     $ar =toba::db()->consultar("SELECT foto
     FROM curlib.libro where id_libro= $aux;");
     $aux=$ar[0]['foto'];
				
	if (is_null($datos['foto']))
	{
		//echo("Es nulo");
	//	exit();
		$datos['foto']=$aux;
			 $this->dep('datos')->set($datos);
     	  $this->dep('datos')->sincronizar();
  		  $this->informar_msg('Libro Guardado exitosamente ', 'info');
			  $this->set_pantalla('pant_inicial');
			  $this->dep('datos')->resetear();
	}
	else{
		unlink($aux);
//delcaro el nombre de la variable a guardar
   	  	 $foto_guardar = "";
  	    //declaro la variable a trabajar sobre el archivo temporarl
      	$foto_tmp = "";
      	//declaro la variable con el arreglo de la foto del upload
      	$imagen_a_tratar = ($datos['foto']);
      
    		//declaro la ruta donde voy a guardar las fotos
    		//$ruta_final = 'img/imagenes/';

    		//obtengo la ruta inicial del archivo
    		//$this->s__path_inicial = toba::proyecto()->get_www($ruta_final);

   		 //declaro la ruta inicial del archivo
   		 //$ruta_inicial = $this->s__path_inicial['path']; 


    		//$path = $ruta_inicial;
    
    		//pregunto si existe el fichero donde voy a guardar los archivos
    	//	if (!file_exists($path))
    	//	{
     		 //si no existe la ruta, la creo y doy permisos de adm
     		// mkdir($path);
     		 //doy peromisos de adm
     		// chmod($path, 0777);
    	//	}
      //guardo el nombre de la imagen
    	$nombre_img = basename($datos['foto']['name']);
      //guardo el nombre del archivo temporal
   	 $foto_tmp = $datos['foto']['tmp_name'];


      //creo un numero aletario para el nombre del archivo
    	$num_ram= mt_rand(0,10000);
    	$ruta_final="/var/www/documentos/libros/";
    	$foto_guardar = $ruta_final . $nombre_img;
    //separo la ruta a partir del punto
    $arreglo = explode(".",$foto_guardar);
    //concateno el numero aleatorio y lo guardo en $foto_guardar
   $foto_guardar = $arreglo[0] . $num_ram . "." . $arreglo[1];
   //$datos["foto"]=$foto_guardar;
     // $foto_guardar = $arreglo[0] . "." . $arreglo[1];
  $sftp=$this->conexion_ssh();
  //var_dump($sftp);
  //exit();

      //pregunto si cargo, no es necesario el if
    if (move_uploaded_file($foto_tmp, 'ssh2.sftp://'.$sftp.$foto_guardar)) 
  	  {
      echo 'cargo';      
      //  $this->informar_msg('Se pudo cargar la imagen ','exito'); 

      $datos["foto"]=$foto_guardar;

   	 }
	}
				
 	}
 	else
 	{
 		echo "DesarrolloCiudad";
 		$destino = $_SERVER[DOCUMENT_ROOT] . 'documentos/libros/';
			unlink($aux);
			//delcaro el nombre de la variable a guardar
   	  	 $foto_guardar = "";
  	    //declaro la variable a trabajar sobre el archivo temporarl
      	$foto_tmp = "";
      	//declaro la variable con el arreglo de la foto del upload
      	$imagen_a_tratar = ($datos['foto']);

    		if (!file_exists($destino)) {
			//si no existe la ruta, la creo y doy permisos de adm
			
			mkdir($destino);
			//doy peromisos de adm
			chmod($destino, 0777);
			}
      //guardo el nombre de la imagen
    	$nombre_img = basename($datos['foto']['name']);
      //guardo el nombre del archivo temporal
   	 $foto_tmp = $datos['foto']['tmp_name'];

      //creo un numero aletario para el nombre del archivo
    	$num_ram= mt_rand(0,10000);

     $foto_guardar = $destino . $nombre_img;
    //separo la ruta a partir del punto
    $arreglo = explode(".",$foto_guardar);
    //concateno el numero aleatorio y lo guardo en $foto_guardar
   $foto_guardar = $arreglo[0] . $num_ram . "." . $arreglo[1];
     // $foto_guardar = $arreglo[0] . "." . $arreglo[1];


      //pregunto si cargo, no es necesario el if
    if (move_uploaded_file($foto_tmp, $foto_guardar)) 
  	  {
      //echo 'cargo';      
        $this->informar_msg('Se pudo cargar la imagen ','exito'); 

      $datos["foto"]=$foto_guardar;
 
   	 }

	$this->set_pantalla('pant_inicial');
       $this->dep('datos')->set($datos);
     	 $this->dep('datos')->sincronizar();
  		 $this->informar_msg('Libro Guardado exitosamente ', 'info');
		   $this->set_pantalla('pant_inicial');
		   $this->dep('datos')->resetear(); 


 	}
}

}
?>