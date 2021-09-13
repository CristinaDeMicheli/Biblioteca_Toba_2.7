<?php
class ci_reportes extends cris2_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro_libros ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_libros(cris2_ei_cuadro $cuadro)
	{
		// if (isset($this->s__filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			//  $filtro = $this->dep('filtro')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_listado_Libros2();
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_libros__seleccion($seleccion)
	{
	}

}
?>