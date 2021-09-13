<?php
class ci_libros extends cris2_ci
{
	
	
	}class ci_estados extends cris2_ci
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
		$this->dep('datos_estado')->eliminar_todo();
		$this->set_pantalla('pant_inicial');
	}

	function evt__guardar()
	{
		try {
 		    $this->dep('datos_estado')->sincronizar();
		    $this->informar_msg('Persona Guardada exitosamente ', 'info');
  		 	$this->dep('datos_estado')->resetear();
			$this->set_pantalla('pant_inicial');
    } catch (Exception $e) {
						
	$this->dep('datos_estado')->resetear();
    echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
}
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_estado ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_estado(cris2_ei_formulario $form)
	{
		$datos = $this->dep('datos_estado')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	
	}

	function evt__formulario_estado__modificacion($datos)
	{
		$this->dep('datos_estado')->set($datos);
		//var_dump($datos);
		//exit();
		 // $this->dep('datos')->tabla('libro')->procesar_filas($datos); CN

	}

	//-----------------------------------------------------------------------------------
	//---- filtro_estado ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_estado(cris2_ei_filtro $filtro)
	{
		if (isset($this->filtro_data)){
			$filtro->set_datos($this->filtro_data);
		} 
	}

	function evt__filtro_estado__filtrar($datos)
	{
		$this->filtro_data= $datos;
	}

	function evt__filtro_estado__cancelar()
	{
		unset($this->filtro_data); 
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_estado ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_estado(cris2_ei_cuadro $cuadro)
	{
		if (isset($this->filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_estado')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_listado_estados3($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_estado__seleccion($seleccion)
	{
		//ei_arbol($seleccion);
		$this->dep('datos_estado')->cargar($seleccion);
		$this->set_pantalla('pant_edicion');
	}

	function conf_evt__cuadro_estado__seleccion(toba_evento_usuario $evento, $fila)
	{
		$this->dep('datos_estado')->set($datos);
	}

}

?>