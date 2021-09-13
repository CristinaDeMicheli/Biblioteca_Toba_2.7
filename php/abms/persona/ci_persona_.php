<?php
class ci_persona_ extends cris2_ci
{
	
	protected $s__filtro_data;
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
			$filtro = $this->dep('filtro')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$datos = toba::consulta_php('consulta')->get_persona10($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);

	}
function rel(){
		return $this->dep('datos');
	}
	
	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(cris2_ei_formulario $form)
	{
		$datos = $this->rel()->tabla('persona')->get();
		$datosnatural = $this->rel()->tabla('persona_natural')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
		$form->set_datos($datos);//recuperar y pasar al formulario
		$form->set_datos($datosnatural);//recuperar y pasar al formulario
	}

	function evt__formulario__modificacion($datos)
	{
		//var_dump($datosnatural);
		//exit();
		//$id_persona= $datos['id_persona'];	
		//var_dump($id_persona);
		//exit();
		try {
		
		$this->rel()->tabla('persona_natural')->set($datos);
		$this->rel()->tabla('persona')->set($datos);	
						
		$this->rel()->tabla('persona_natural')->sincronizar();
		$this->rel()->tabla('persona')->sincronizar();
	
		$this->rel()->tabla('persona_natural')->resetear();
		$this->rel()->tabla('persona')->resetear();
		$this->informar_msg('Datos modificados exitosamente ', 'info');

		  $this->set_pantalla('pant_inicial');
		  } catch (Exception $e) {

    			$this->informar_msg('Completar datos ', 'info');
    			 $this->set_pantalla('pant_inicial');
}
		
	}

	function evt__cuadro__seleccion($seleccion)
	{
		     $this->set_pantalla('pant_edicion');
		  $this->rel()->cargar($seleccion);

	}



	function evt__formulario__agregar($datos)
	{
		
			try {
			
	   		 
		 	 
		 //$va=toba::db()->consultar("select MAX(id_persona) + 1 from cidig.persona;");
		//ei_arbol($datos);
		//exit();
	    
			$cursor=$this->rel()->tabla('persona')->nueva_fila($datos);		
			$this->rel()->tabla('persona')->set_cursor($cursor);
			//$this->

			//$this->rel()->tabla('persona')->sincronizar();

			$this->rel()->tabla('persona_natural')->nueva_fila($datos);		
			//$this->rel()->tabla('persona_natural')->set_cursor($cursor);
			$this->rel()->sincronizar();
			$this->rel()->resetear();
			//$this->rel()->tabla('persona_natural')->set($datos);		
			//$this->rel()->tabla('persona_natural')->sincronizar();	
			//$this->rel()->tabla('persona')->resetear();
			//$this->rel()->tabla('persona_natural')->resetear();
			//$this->informar_msg('Datos modificados exitosamente ', 'info');
			  $this->set_pantalla('pant_inicial');
			  } catch (Exception $e) {

    			$this->informar_msg('Completar datos ', 'info');
    			 $this->set_pantalla('pant_inicial');
}
		  
	}

	function evt__formulario__limpiar()
	{
		$this->rel()->resetear();
		  $this->set_pantalla('pant_inicial');

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



	

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__Siguiente()
	{
		    $this->set_pantalla('pant_edicion');
	}

}
?>