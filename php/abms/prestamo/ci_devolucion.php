<?php
class ci_devolucion extends cris2_ci
{
	

	//-----------------------------------------------------------------------------------
	//---- cuadro_devolucion ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_devolucion(cris2_ei_cuadro $cuadro)
	{

		if (isset($this->s__filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_devolucion')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$cuadro->desactivar_modo_clave_segura();
			$datos = toba::consulta_php('consulta')->get_prestamo2($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);

	}

	function evt__cuadro_devolucion__seleccion($seleccion)
	{
		//var_dump($seleccion);
		$s=$seleccion['id_prestamo'];
		//ei_arbol($s);
		//exit();
		$rs =toba::db()->consultar("SELECT libro_id
			FROM curlib.prestamo
			where id_prestamo =$s;");
		$rs2=($rs['0']);
		
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$rs2['libro_id']));
  		$this->rel()->tabla('prestamo')->cargar(array('id_prestamo'=>$s));
		  $this->set_pantalla('pant_edicion');

	}

	function rel(){
		return $this->dep('datos');
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_devolucion ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_devolucion(cris2_ei_filtro $filtro)
	{
		if (isset($this->s__filtro_data)){
			$filtro->set_datos($this->s__filtro_data);
		} 

	}

	function evt__filtro_devolucion__filtrar($datos)
	{
		$this->s__filtro_data= $datos;
	}

	function evt__filtro_devolucion__cancelar()
	{
		unset($this->s__filtro_data); 
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_devolucion --------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_devolucion(formulario_devolucion $form)
	{
		$datos = $this->rel()->tabla('prestamo')->get();

		//$datos2 = $this->rel()->tabla('libro')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
		//$form->set_datos($datos2);//recuperar y pasar al formulario

	}

	function evt__formulario_devolucion__alta($datos)
	{
		//$id_libro=$datos['libro_id'];
		//ei_arbol($datos['libro_id']);
		//exit();
		//toba::db()->consulta_php('consulta')->libro_disponible($id_libro);
		//exit();
		$devolucion= $datos['devolucion'];
		$id_libro= $datos['libro_id'];
	//	$fecha=date("d-m-Y", strtotime($datos['fecha_devolucion']));
	//	$datos['fecha_devolucion']=$fecha;
		//ei_arbol($fecha);
		//exit();

		//if ($devolucion == 'No'){
		//$this->rel()->tabla('prestamo')->set($datos);
		//$this->rel()->tabla('prestamo')->sincronizar();        
		//$this->rel()->tabla('prestamo')->resetear();
		//$this->informar_msg('Datos modificados exitosamente ', 'info');
		//exit();
		//}elseif ($devolucion == 'Si'){
		//}

		//$datos['dias_retraso']=Se llama al metodo de dias de retraso;
        //toba::db()->consultar("UPDATE curlib.libro SET id_estado = 1 WHERE id_libro =$id_libro");
		if (($datos['fecha_devolucion'] < $datos['fecha_alta']) && ($devolucion == 'Si')) 
		{ 
			
			$this->informar_msg('No se puede elegir una fecha de devolucion menor a la fecha del alta del prestamo ','error');
		}
		else{
		if ($devolucion == 'Si'){
		
        $this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear();
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=1;
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 
		$this->informar_msg('Libro devuelto con exito ', 'info'); 
		}
		else{
			  $this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear();
			$this->informar_msg('Datos modificados exitosamente ', 'info');
		}
		}
 $this->set_pantalla('pant_inicial');

	}

	function evt__formulario_devolucion__limpiar()
	{
		 $this->set_pantalla('pant_inicial');
	}

}
?>