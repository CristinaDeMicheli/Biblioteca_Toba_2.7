<?php
class dt_nacionalidad extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_nacionalidad, descripcion FROM cidig.nacionalidad ORDER BY descripcion";
		return toba::db('cris2')->consultar($sql);
	}

}

?>