<?php
class ci_upload extends cris2_ci
{
    protected $s__path_inicial;
    protected $s__nom_img;
   
   // protected $s__ruta_foto;

  function rel(){
    return $this->dep('datos');
  }
	
	function evt__formulario__modificacion($datos)
	{
    //delcaro el nombre de la variable a guardar
    $foto_guardar = "";
    //declaro la variable a trabajar sobre el archivo temporarl
    $foto_tmp = "";
    //declaro la variable con el arreglo de la foto del upload
    $imagen_a_tratar = ($datos['foto']);

    //declaro la ruta donde voy a guardar las fotos
    $ruta_final = 'img/imagenes/';

    //obtengo la ruta inicial del archivo
    $this->s__path_inicial = toba::proyecto()->get_www($ruta_final);

    //declaro la ruta inicial del archivo
    $ruta_inicial = $this->s__path_inicial['path']; 

    $path = $ruta_inicial;
    
    //pregunto si existe el fichero donde voy a guardar los archivos
    if (!file_exists($path)){
      //si no existe la ruta, la creo y doy permisos de adm
      mkdir($path);
      //doy peromisos de adm
      chmod($path, 0777);
    }
      //guardo el nombre de la imagen
    $nombre_img = basename($datos['foto']['name']);
      //guardo el nombre del archivo temporal
    $foto_tmp = $datos['foto']['tmp_name'];

      //creo un numero aletario para el nombre del archivo
    $num_ram= mt_rand(0,10000);



    $foto_guardar = $ruta_final . $nombre_img;
    //separo la ruta a partir del punto
    $arreglo = explode(".",$foto_guardar);
    //concateno el numero aleatorio y lo guardo en $foto_guardar
    $foto_guardar = $arreglo[0] . $num_ram . "." . $arreglo[1];


      //pregunto si cargo, no es necesario el if
    if (move_uploaded_file($foto_tmp, $foto_guardar)) {
      //echo 'cargo';      
        $this->informar_msg('Se pudo cargar la imagen ','exito'); 

      $datos["foto"]=$foto_guardar;

    
       $this->set_pantalla('pant_inicial');
      //-- Se muestra la imagen temporal
       //$datos['imagen_grafica'] ="<img src='{$foto_guardar['url']}' alt=''>";
      //ei_arbol($datos);

    
    $this->rel()->tabla('foto')->nueva_fila($datos);
     $this->rel()->tabla('foto')->sincronizar();
    $this->rel()->tabla('foto')->resetear(); 
     


    }else{
     // echo 'no cargo';
        $this->informar_msg('No se puedo cargar la foto ','error');
    }
  } 

	

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(cris2_ei_formulario $form)
	{
    
       $datos = $this->rel()->tabla('foto')->get();
       //acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
  
      $form->set_datos($datos);//recuperar y pasar al formulario
      //var_dump($datos['foto']);
      $nom_imagen= $datos['foto'];
      $img = $datos['foto'];
      //var_dump($img);
      //$s__nom_img=$img;
      $form->ef('imagen_grafica')->set_estado("<img src= '$img'>");
        
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(cris2_ei_cuadro $cuadro)
	{

      $datos = toba::consulta_php('consulta')->get_listado_fotos();
      $cuadro->set_datos($datos);
	}

	


	function evt__formulario__ver()
	{
     $this->set_pantalla('pant_edicion');
	}

	function evt__cuadro__seleccion($seleccion)
	{
   //  $aux=$seleccion['nombre'];
    // var_dump($aux);
    // $cadena = "'$aux'";
     // echo $cadena; //Devolverá --> esto' es una " prueba '
    $this->rel()->tabla('foto')->cargar($seleccion);
    $this->set_pantalla('pant_inicial');
    //  $s__nom_img2 =toba::db()->consultar("SELECT foto
     // FROM curlib.foto where nombre=$cadena;");
      // $s__nom_img2=$s__nom_img2[0]['foto'];
    //var_dump($s__nom_img2);
     //exit();
	}

	function evt__formulario__borrar($datos)
	{

  //$foto_borrar = "'" . $s__nom_img . "'" ;
   //var_dump($datos['imagen_grafica']);
  // $ar=$datos['imagen_grafica'];
  // var_dump($datos);
  //exit();
    $aux=$datos['nombre'];
    $cadena = "'$aux'";
     $ar =toba::db()->consultar("SELECT foto
     FROM curlib.foto where nombre= $cadena;");
     $aux=$ar[0]['foto'];
     // var_dump($aux); 
     // unlink($aux);
     // exit();
   
   // echo "Borrado completado";
   
    //var_dump($s__nom_img);
    //exit();
   
    If (unlink($aux)) {
  // file was successfully deleted
        $this->informar_msg('Borrado ','exito');
         $this->dep('datos')->eliminar_todo();
    $this->set_pantalla('pant_inicial');
} else {
  // there was a problem deleting the file
    $this->informar_msg('No se borro ','fallo');
    
}
	}

}
?>