<?php
class dt_persona_natural extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_persona_natural, apyn FROM cidig.persona_natural limit 50";
		return toba::db('cris2')->consultar($sql);
	}

}

?>