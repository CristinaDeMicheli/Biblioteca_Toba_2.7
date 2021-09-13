<?php
class ci_reporte_libro extends cris2_ci
{
	protected $s__filtro_data;
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__imprimir()
	{
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

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
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

}

?>