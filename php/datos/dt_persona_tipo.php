<?php
class dt_persona_tipo extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_persona_tipo, descripcion FROM cidig.persona_tipo ORDER BY descripcion";
		return toba::db('cris2')->consultar($sql);
	}

}

?>