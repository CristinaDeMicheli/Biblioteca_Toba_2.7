<?php
class ci_adquisicion extends cris2_ci
{
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		//cambiar de pantalla
		$this->set_pantalla('pant_edicion');
	}

	function evt__cancelar()
	{
		//cambiar de pantalla
		$this->set_pantalla('pant_inicial');
	}

	function evt__eliminar()
	{	
		 //borrar los datos de la dependencia DT-adquisicion 
		$this->dep('datos')->eliminar_todo();
		 //cambiar de pantalla
		$this->set_pantalla('pant_inicial');
	}

	function evt__guardar()
	{
		//Try para capturar un error durante el evento Guardar
		try {
			 //Sincronizar la dependencia DT-adquisicion con la BD
 			 $this->dep('datos')->sincronizar();
 			 //MSJ
 		     $this->informar_msg('Persona Guardada exitosamente ', 'info');
 		      //cambiar de pantalla
			 $this->set_pantalla('pant_inicial');
			  //resetear la dependencia de DT-adquisicion (durante ejecucion en memoria interna )
			 $this->dep('datos')->resetear();
			}
			//Capturar la exepcion si ocurre algun error
 		catch (Exception $e) 
 			{
		 	//resetear la dependencia de DT-adquisicion (durante ejecucion en memoria interna )				
			$this->dep('datos')->resetear();
			//Msje + Error
    		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
			}
			}

	//-----------------------------------------------------------------------------------
	//---- formulario_adquisicion -------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_adquisicion(cris2_ei_formulario $form)
	{
		$datos = $this->dep('datos')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	}

	function evt__formulario_adquisicion__modificacion($datos)
	{
		//setear la dependencia datos (actualizar la DT-adquisicion)
		$this->dep('datos')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_adquisicion -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_adquisicion(cris2_ei_filtro $filtro)
	{
		//La función isset() nos permite evaluar si una variable está definida o no
		if (isset($this->filtro_data)){
			//en una variable filtro guardo los datos que se capturan al usar el filtro
			$filtro->set_datos($this->filtro_data);
		} 
	}

	function evt__filtro_adquisicion__filtrar($datos)
	{
			//Guardar las condiciones para poder usarla en la configuracion del cuadro
			$this->filtro_data= $datos;
	}

	function evt__filtro_adquisicion__cancelar()
	{
		//La función unset() nos permite eliminar variables en PHP, es como si nunca la hubiésemos creado y se comportará de tal manera.
		unset($this->filtro_data);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_adquisicion -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_adquisicion(cris2_ei_cuadro $cuadro)
	{
		if (isset($this->filtro_data))
		{ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_adquisicion')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit(); var_dump proporciona información sobre el tamaño y tipo de datos de la variable y, en el caso de arrays y objetos, de los elementos que la componen exit() termina la ejecucion
			//ei_arbol($filtro); muestra la estructura de una variable
		}
			//llamar al metodo get_listado_adquisicion($filtro) pasando su respectivo parametro
			$datos = toba::consulta_php('consulta')->get_listado_adquisicion($filtro);
			//ei_arbol($datos);
			//seteo el cuadro con la consulta generada y guardada en datos
			$cuadro->set_datos($datos);
	}

	function evt__cuadro_adquisicion__seleccion($seleccion)
	{
		//cargar la dependencia DT-adquisicion con el parametro $seleccion el cual trae el id del registro que se captura.
		$this->dep('datos')->cargar($seleccion);
		//pasar de pantalla
		$this->set_pantalla('pant_edicion');
	}

	function conf_evt__cuadro_adquisicion__seleccion(toba_evento_usuario $evento, $fila)
	{
		$this->dep('datos')->set($datos);
	}

}

?>