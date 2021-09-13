<?php
class dt_autor extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_autor, nombre FROM autor ORDER BY nombre";
		return toba::db('cris2')->consultar($sql);
	}

}

?>