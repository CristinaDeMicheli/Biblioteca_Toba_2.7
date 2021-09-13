<?php
class ci_popup_libro extends cris2_ci
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

	//-----------------------------------------------------------------------------------
	//---- filtro_popup_libro -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_popup_libro(cris2_ei_filtro $filtro)
	{
		if (isset($this->filtro_data)){
			$filtro->set_datos($this->filtro_data);
		}
	}

	function evt__filtro_popup_libro__filtrar($datos)
	{
			$this->filtro_data= $datos;
	}

	function evt__filtro_popup_libro__cancelar()
	{
		unset($this->filtro_data); 
	}


		function get_libro_pp()
	{
		
	}
	//-----------------------------------------------------------------------------------
	//---- cuadro_popup_libro -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_popup_libro(cris2_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if (isset($this->filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_popup_libro')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_listado_Libros4($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_popup_libro__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);
	}

	//get_libro_pp

}
?>