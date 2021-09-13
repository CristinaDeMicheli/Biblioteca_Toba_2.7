<?php
class formulario_prestamo extends cris2_ei_formulario
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
		";
	}


}
?>
