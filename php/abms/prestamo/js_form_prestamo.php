<?php
class ci_formulario_ver_prestamo extends cris2_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__plazo__procesar = function(es_inicial)
		{
			//Preguntar si los campos del form no estan vacios
			if(this.ef('plazo').get_estado() != '' && this.ef('fecha_alta').get_estado() != '')
				{
					//creo el array
					dts = new Array;
					//datos para calcular la fecha de vencimiento
					dts['plazo'] = this.ef('plazo').get_estado();
					dts['fecha_alta'] = this.ef('fecha_alta').get_estado();
					//Llama a la function de ajax
					this.controlador.ajax('get_calcula_vto', dts, this, this.datos_vto);
				}
		
		}
		
		{$this->objeto_js}.datos_vto = function(rs)
		{ 
			if(rs != '') 
			{
				this.ef('fecha_venc').set_estado(rs);    
			}
		}
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__fecha_alta__procesar = function(es_inicial)
		{
			//Preguntar si los campos del form no estan vacios
			if(this.ef('plazo').get_estado() != '' && this.ef('fecha_alta').get_estado() != '')
				{
					//creo el array
					dts = new Array;
					//datos para calcular la fecha de vencimiento
					dts['plazo'] = this.ef('plazo').get_estado();
					dts['fecha_alta'] = this.ef('fecha_alta').get_estado();
					//Llama a la function de ajax
					this.controlador.ajax('get_calcula_vto', dts, this, this.datos_vto);
				}
		}
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__dias_retraso__procesar = function(es_inicial)
		{
			var f1 = this.ef('fecha_venc').get_estado();
		                  var f2 = this.ef('fecha_devolucion').get_estado();
		                 var aFecha1 = f1.split('/');
		                 var aFecha2 = f2.split('/');
		                 var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
		                  var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
		                  var dif = fFecha2 - fFecha1;
		                  var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
		                  if(dias > 0)
		                  {
		                  this.ef('dias_retraso').set_estado(dias)
		                  }else{
		                      this.ef('dias_retraso').set_estado(0)
		                  }
		}
		";
	}



}
?>