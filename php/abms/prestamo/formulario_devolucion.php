<?php
class formulario_devolucion extends cris2_ei_formulario
{


	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		
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
		//---- Procesamiento de EFs --------------------------------
		
		
		";
	}



}
?>