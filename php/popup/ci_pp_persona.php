<?php
class ci_pp_persona extends cris2_ci
{
	//-----------------------------------------------------------------------------------
	//---- cuadro_popup -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_popup(cris2_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		
		//$datos = toba::consulta_php('consulta')->get_persona10($filtro);
			//ei_arbol($datos);
		//	$cuadro->set_datos($datos);

				if (isset($this->filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_pp')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_persona10($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_popup__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);
	}

static function get_persona_pp($id=0){
	$rs = toba::db()->consultar("SELECT id_persona FROM cidig.persona WHERE id_persona = ".$id);
	$valor = "No se pudo identificar el Id. de la persona: ".$id;
	if (count($rs) > 0){
		$valor = $rs[0]['id_persona'];
	}
	return $valor;
}


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