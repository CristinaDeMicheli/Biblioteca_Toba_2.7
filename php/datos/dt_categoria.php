<?php
class dt_categoria extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_categoria, nombre FROM categoria ORDER BY nombre";
		return toba::db('cris2')->consultar($sql);
	}

}

?>