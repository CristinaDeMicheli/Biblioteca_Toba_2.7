<?php
class dt_genero extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_genero, descripcion FROM genero ORDER BY descripcion";
		return toba::db('cris2')->consultar($sql);
	}

}

?>