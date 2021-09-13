<?php
class ci_ver_prestamos extends cris2_ci
{
	protected $s__filtro_data;
	//protected $s__prestamo;
	function ajax__get_calcula_retraso($dts, toba_ajax_respuesta $respuesta)
	{      
		$rs = 0;
		//$fe1 = date($dts['fecha_venc']); 
		//$diaVenc = substr($fe1,0,2); 
		//$fe2 = date($dts['fecha_devolucion']); 
		//$diaDev = substr($fe2,0,2); 
		//$dia_retraso=($diaDev - $diaVenc);
			//if($dia_retraso >0){
				//$dia_retraso=abs($diaDev - $diaVenc);
				//$rs=$dia_retraso;
			//}  else{
			//	$rs = 0;
			//}
		$respuesta->set($rs);
	}
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
function rel(){
		return $this->dep('datos');
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
			$cuadro->desactivar_modo_clave_segura();
			$datos = toba::consulta_php('consulta')->get_prestamo2($filtro);
			//ei_arbol($datos);
			$cuadro->set_datos($datos);
	}

	function evt__cuadro__seleccion($seleccion)
	{
		
		//var_dump($seleccion);
		$s=$seleccion['id_prestamo'];
		//$s__prestamo=$seleccion['id_prestamo'];

		//ei_arbol($s__prestamo);
		//exit();
		$rs =toba::db()->consultar("SELECT libro_id
			FROM curlib.prestamo
			where id_prestamo =$s;");
		$rs2=($rs['0']);
		
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$rs2['libro_id']));
  		$this->rel()->tabla('prestamo')->cargar(array('id_prestamo'=>$s));
		$this->set_pantalla('pant_edicion');
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(cris2_ei_formulario $form)
	{
		$datos = $this->rel()->tabla('prestamo')->get();
		//acceder al objeto de persistencia, y pedir los registros - para un registro get, para varios getfilas
	
		$form->set_datos($datos);//recuperar y pasar al formulario
	}

	function evt__formulario__modificacion($datos)
	{
	
	$id_prestamo =intval($datos['id_prestamo']);
	$id_libro_viejo=toba::db()->consultar("SELECT libro_id
			FROM curlib.prestamo
			where id_prestamo =$id_prestamo;");
		
		$rs=($id_libro_viejo[0]);
	
		$id_libro_viejo=$rs['libro_id'];
			
		//ei_arbol($id_libro_viejo);
		//exit();
		
		//if (empty($datos)){ 
		//$this->informar_msg('Vacio ', 'info');
		//PARA OBTENER VALOR DE ARRAY $DATOS
		$id_persona =$datos['persona_id'];
		$id_libro=intval($datos['libro_id']);
		$devolucion= $datos['devolucion'];
		$cant_libros=$this->cantidad_lib_prestado($id_persona);
		$this->cantidad_lib_prestado($id_persona);

		if (($datos['fecha_devolucion'] < $datos['fecha_alta']) && ($devolucion == 'Si')) 
		{ 
			
			$this->informar_msg('No se puede elegir una fecha de devolucion menor a la fecha del alta del prestamo ','error');

		}elseif ($cant_libros >= 4)
		{ 
			
			$this->informar_msg('No se ha podido realizar el prestamo ','error');
		}
		
		elseif ($this->es_moroso($id_persona)!=0)
		{ 
			
			$this->informar_msg('No se ha podido realizar el prestamo porque es moroso','error');
		}
		else{
		
if (($devolucion == 'No') && ($id_libro !== $id_libro_viejo))
		{
		$this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear();
		//libro nuevo
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=2;
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 

		//Libro viejo
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro_viejo));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=1;
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 

		 $this->set_pantalla('pant_inicial');
		$this->informar_msg('exito ', 'info');
		}

		elseif (($devolucion == 'No') && ($id_libro == $id_libro_viejo))
		{
		$this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear();
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro_viejo));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=2;
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 
		 $this->set_pantalla('pant_inicial');
		$this->informar_msg('exito ', 'info');
		
		}
		elseif (($devolucion == 'Si') && ($id_libro == $id_libro_viejo))
		{
		$this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear();
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro_viejo));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=1;
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 
		 $this->set_pantalla('pant_inicial');
		$this->informar_msg('exito ', 'info');
		
		}
		elseif (($devolucion == 'Si') && ($id_libro !== $id_libro_viejo))
		{
		$this->rel()->tabla('prestamo')->set($datos);
		$this->rel()->tabla('prestamo')->sincronizar();
		$this->rel()->tabla('prestamo')->resetear();
		$this->rel()->tabla('libro')->cargar(array('id_libro'=>$id_libro_viejo));
		$dt_l=$this->rel()->tabla('libro')->get_filas();
        $dt_l[0]['id_estado']=1;
		$this->rel()->tabla('libro')->set($dt_l[0]);   
		$this->rel()->tabla('libro')->sincronizar();
		$this->rel()->tabla('libro')->resetear(); 
		 $this->set_pantalla('pant_inicial');
		$this->informar_msg('exito ', 'info');
		
		}
		
         }
		
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

	function evt__formulario__limpiar()
	{
	
	$this->rel()->tabla('prestamo')->resetear();
	 $this->set_pantalla('pant_inicial');
		
	}

}
?>