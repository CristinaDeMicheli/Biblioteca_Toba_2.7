<?php
class ci_popup_persona extends cris2_ci
{
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
	}

	function evt__cancelar()
	{
	}

	function evt__eliminar()
	{
	}

	function evt__guardar()
	{
	}

	function get_persona_pp()
	{
		
	}
	//-----------------------------------------------------------------------------------
	//---- filtro_popup_persona ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	//function conf__filtro_popup_persona(cris2_ei_filtro $filtro)
	//{
		//if (isset($this->filtro_data)){
		//	$filtro->set_datos($this->filtro_data);
		//}
	//}

	//function evt__filtro_popup_persona__filtrar($datos)
	//{
		//$this->filtro_data= $datos;
	//}

	//function evt__filtro_popup_persona__cancelar()
	//{
	//	unset($this->filtro_data); 
	//}

	//-----------------------------------------------------------------------------------
	//---- cuadro_popup_persona ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_popup_persona(cris2_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if (isset($this->filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_pp')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_persona2($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_popup_persona__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);
	}

	//get_persona_pp

	//-----------------------------------------------------------------------------------
	//---- filtro_pp --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_pp(cris2_ei_filtro $filtro)
	{
		if (isset($this->filtro_data)){
			$filtro->set_datos($this->filtro_data);
		} 
	}

	function evt__filtro_pp__filtrar($datos)
	{
			$this->filtro_data= $datos;
	}

	function evt__filtro_pp__cancelar()
	{
		unset($this->filtro_data);
	}

}
?>