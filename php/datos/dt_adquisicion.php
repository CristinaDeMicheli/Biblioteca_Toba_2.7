<?php
class dt_adquisicion extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_adquisicion, descripcion FROM adquisicion ORDER BY descripcion";
		return toba::db('cris2')->consultar($sql);
	}

}

?>