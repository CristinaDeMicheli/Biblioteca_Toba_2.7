<?php
class ci_personas extends cris2_ci
{

protected $s__filtro_data;

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(cris2_ei_formulario $form)
	{
		$datos = $this->rel()->tabla('personas')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	}

	function evt__formulario__modificacion($datos)
	{
		//    ei_arbol($datos);
	$this->rel()->tabla('personas')->set($datos);
	$this->rel()->tabla('personas')->sincronizar();        
		$this->rel()->tabla('personas')->resetear();
		$this->informar_msg('Carga exitosamente ', 'info');
	}

	

	//-----------------------------------------------------------------------------------
	//---- formulario_n -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_n(cris2_ei_formulario $form)
	{
		$datos = $this->rel()->tabla('personas_natural')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	}

	function evt__formulario_n__modificacion_n($datos)
	{
			//    ei_arbol($datos);
	$this->rel()->tabla('personas_natural')->set($datos);
	$this->rel()->tabla('personas_natural')->sincronizar();        
		$this->rel()->tabla('personas_natural')->resetear();
		$this->informar_msg('Carga exitosamente ', 'info');
	}

	function evt__formulario_n__eliminar()
	{
	}
function rel(){
		return $this->dep('datos');
	}
	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(cris2_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		
		//$datos = toba::consulta_php('consulta')->get_persona10($filtro);
			//ei_arbol($datos);
		//    $cuadro->set_datos($datos);

				if (isset($this->s__filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_natural')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_persona10($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro__seleccion($seleccion)
	{
			//ei_arbol($seleccion);
		$this->rel()->tabla('personas')->cargar($seleccion);
	}


	//-----------------------------------------------------------------------------------
	//---- cuadro_n ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_n(cris2_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		
		//$datos = toba::consulta_php('consulta')->get_persona11($filtro);
			//ei_arbol($datos);
			//$cuadro->set_datos($datos);
			if (isset($this->s__filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_natural')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_persona11($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_n__seleccion_n($seleccion)
	{
		//ei_arbol($seleccion);
		$this->rel()->tabla('personas_natural')->cargar($seleccion);
	}

	

	//-----------------------------------------------------------------------------------
	//---- filtro_natural ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_natural(cris2_ei_filtro $filtro)
	{
		if (isset($this->s__filtro_data)){
			$filtro->set_datos($this->s__filtro_data);
		} 
	}

	function evt__filtro_natural__filtrar($datos)
	{
		$this->s__filtro_data= $datos;
	}

	function evt__filtro_natural__cancelar()
	{
		unset($this->s__filtro_data);
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

	function evt__formulario__eliminar()
	{
		$this->dep('datos')->eliminar();
		
		$this->informar_msg('Persona Borrada exitosamente ', 'info');
	}

	function evt__formulario_n__eliminar_n()
	{
		$this->dep('datos')->eliminar();
		
		$this->informar_msg('Persona Borrada exitosamente ', 'info');
	}

	function evt__formulario__limpiar()
	{
			
		$this->rel()->resetear();
	}

	function evt__formulario_n__limpiar_n()
	{
			
		$this->rel()->resetear();
	}

}
?>