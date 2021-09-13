<?php
class dt_editorial extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_editorial, nombre FROM editorial ORDER BY nombre";
		return toba::db('cris2')->consultar($sql);
	}

}

?>