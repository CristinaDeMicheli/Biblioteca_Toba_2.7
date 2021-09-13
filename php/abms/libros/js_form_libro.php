<?php
class js_form_libro extends cris2_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__id_autor__procesar = function(es_inicial)
		{
			//if (this.ef('ejemplar').get_estado() == '1')
			//{
			//this.ef('estante').mostrar(false);
			//}
		}
		";
	}

}

?>