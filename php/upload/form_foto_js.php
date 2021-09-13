<?php
class form_foto_js extends cris2_ei_formulario
{
	function generar_layout()
	{
		$this->generar_html_ef('foto');
		$this->generar_html_ef('nombre');
		$this->generar_html_ef('imagen_grafica');
	}

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__imagen_grafica__procesar = function(es_inicial)
		{
			//this.ef('imagen_grafica').set_estado('<img src=img/imagenes/index1287.jpg/>');    
		}
		";
	}

}

?>