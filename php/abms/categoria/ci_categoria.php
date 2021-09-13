<?php
class ci_categoria extends cris2_ci
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
		$this->dep('datos')->eliminar_todo();
		$this->set_pantalla('pant_inicial');
	}

	function evt__guardar()
	{
		try {
  $this->dep('datos')->sincronizar();
   $this->informar_msg('Persona Guardada exitosamente ', 'info');
   	$this->dep('datos')->resetear();
		$this->set_pantalla('pant_inicial');
} catch (Exception $e) {
						
	$this->dep('datos')->resetear();
    echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
}
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_categoria ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_categoria(cris2_ei_formulario $form)
	{
		$datos = $this->dep('datos')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	}

	function evt__formulario_categoria__modificacion($datos)
	{
		$this->dep('datos')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_categoria -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_categoria(cris2_ei_filtro $filtro)
	{
		if (isset($this->filtro_data)){
			$filtro->set_datos($this->filtro_data);
		} 
	}

	function evt__filtro_categoria__filtrar($datos)
	{
			$this->filtro_data= $datos;
	}

	function evt__filtro_categoria__cancelar()
	{
		unset($this->filtro_data);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_categoria -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_categoria(cris2_ei_cuadro $cuadro)
	{
		if (isset($this->filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_categoria')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_listado_categoria($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_categoria__seleccion($seleccion)
	{
		
		$this->dep('datos')->cargar($seleccion);
		$this->set_pantalla('pant_edicion');
	}

	function conf_evt__cuadro_categoria__seleccion(toba_evento_usuario $evento, $fila)
	{
			$this->dep('datos')->set($datos);
	}

}
?>