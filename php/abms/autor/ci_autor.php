<?php
class ci_autor extends cris2_ci
{
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__cancelar()
	{
		$this->set_pantalla('pant_inicial');
	}

	function evt__eliminar()
	{
		$this->dep('datos_autor')->eliminar_todo();
		$this->set_pantalla('pant_inicial');
	}

	function evt__guardar()
	{
		try {
 			 $this->dep('datos_autor')->sincronizar();
   			 $this->informar_msg('Persona Guardada exitosamente ', 'info');
			$this->set_pantalla('pant_inicial');
			$this->dep('datos_autor')->resetear();
			} catch (Exception $e) {
						
			$this->dep('datos_autor')->resetear();
  		  echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
}
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_autor -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_autor(cris2_ei_formulario $form)
	{
		$datos = $this->dep('datos_autor')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	}

	function evt__formulario_autor__modificacion($datos)
	{
		$this->dep('datos_autor')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_autor -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_autor(cris2_ei_cuadro $cuadro)
	{
		if (isset($this->filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_autor')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_listado_autores2($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	
	}

	function evt__cuadro_autor__seleccion($seleccion)
	{
		//ei_arbol($seleccion);
		$this->dep('datos_autor')->cargar($seleccion);
		$this->set_pantalla('pant_edicion');
	}

	function conf_evt__cuadro_autor__seleccion(toba_evento_usuario $evento, $fila)
	{
		$this->dep('datos_autor')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_autor -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_autor(cris2_ei_filtro $filtro)
	{
		if (isset($this->filtro_data)){
			$filtro->set_datos($this->filtro_data);
		} 
	}

	function evt__filtro_autor__filtrar($datos)
	{
			$this->filtro_data= $datos;
	}

	function evt__filtro_autor__cancelar()
	{
		unset($this->filtro_data); 
	}

	
}
?>