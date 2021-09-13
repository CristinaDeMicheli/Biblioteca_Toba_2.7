<?php
class dt_estado extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_estado, descripcion FROM estado ORDER BY descripcion";
		return toba::db('cris2')->consultar($sql);
	}

}

?>