<?php
class dt_libro extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_libro, titulo FROM libro ORDER BY titulo";
		return toba::db('cris2')->consultar($sql);
	}

}

?>