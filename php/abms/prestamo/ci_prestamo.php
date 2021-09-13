<?php
class ci_prestamo extends cris2_ci
{
	protected $s__filtro_data;

	//-----------------------------------------------------------------------------------
	//---- Metodos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
//-----------Gestion de Prestamo
	#------ ajax para calcular la fecha de vencimiento -------
	function ajax__get_calcula_vto($dts, toba_ajax_respuesta $respuesta)
	{      
		$rs = null;
		$fe = date($dts['fecha_alta']);
		$year = substr($fe,6,4); 
		$month = substr($fe,3,2); 
		$day = substr($fe,0,2); 
		$fecha_aux = $year."-".$month."-".$day;
		$fecha_final = strtotime('+'.$dts['plazo'].'day', strtotime($fecha_aux)); 
		$rs = date('d-m-Y', $fecha_final);  
		$respuesta->set($rs);
	}
	function es_moroso($id=0){
			$rs =toba::db()->consultar("SELECT id_prestamo
			FROM curlib.prestamo as t_p
			where persona_id = $id AND ((t_p.devolucion is null) OR t_p.devolucion = 'No' ) AND t_p.fecha_venc < current_date;");
			$valor=0;
			if(count($rs) > 0 ){
				$valor = $rs[0]['id_prestamo'];
			}
	
			return $valor;
	
		}
		
		function cantidad_lib_prestado($id=0){
			$rs =toba::db()->consultar("SELECT count(id_prestamo)
			FROM curlib.prestamo 
				where persona_id = $id AND (devolucion is null OR devolucion = 'No' ) ");
				if(count($rs) > 0 ){
					$valor = $rs[0]['count'];
				}
		
				return $valor;
			

			
		}

	//-----------------------------------------------------------------------------------
	//---- cuadro_prestamo --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_prestamo(cris2_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro_data)){ //preguntar si la variable esta seteada (tiene valores)
			$filtro = $this->dep('filtro_devolucion')->get_sql_where();//dame el objeto que representa el filtro  y pasalo por where
			//var_dump($filtro);exit();
		//ei_arbol($filtro);
			}
			$cuadro->desactivar_modo_clave_segura();
			$datos = toba::consulta_php('consulta')->get_prestamo2($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);

	}

function rel(){
		return $this->dep('datos');
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_prestamo ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario_prestamo(formulario_prestamo $form)
	{
		$datos = $this->rel()->tabla('prestamo')->get();

		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario

	}

	function evt__formulario_prestamo__alta($datos)
	{
		try {
      	//agregar validacion para que tire error si no estan cargados los datos
		//if (empty($datos)){ 
		//$this->informar_msg('Vacio ', 'info');
		//PARA OBTENER VALOR DE ARRAY $DATOS
		$id_persona =$datos['persona_id'];
		$id_libro=$datos['libro_id'];
		$cant_libros=$this->cantidad_lib_prestado($id_persona);
		$this->cantidad_lib_prestado($id_persona);


		//$this->dep("datos")->cargar(array('libro_id'=>$id_libro));
		//$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro));
		//$dt_l=$this->rel()->tabla('libro')->get_filas();
		//$dt_l=$this->rel()->tabla('libro')->get();
		//var_dump($dt_l);

		//exit();

		if ($cant_libros >= 3){ 
			
			$this->informar_msg('No se ha podido realizar el prestamo ','error');
		}
		
		elseif ($this->es_moroso($id_persona)!=0){ 
			
			$this->informar_msg('No se ha podido realizar el prestamo porque es moroso','error');
		}else{
		$datos['devolucion']="No";
		$datos['fecha_devolucion']=null;
		$datos['dias_retraso']=null;
		
		//var_dump($datos);
		//exit();
		
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=2;

        $this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear(); 
		
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 




		// $this->set_pantalla('pant_edicion');
		$this->informar_msg('Prestamo Guardada exitosamente ', 'info');
		

		}
		 } catch (Exception $e) {

    			$this->informar_msg('Completar datos ', 'info');
    			 $this->set_pantalla('pant_inicial');
}
	}
 

	function evt__formulario_prestamo__limpiar()
	{
		$this->rel()->tabla('prestamo')->resetear();
	}

}
?>