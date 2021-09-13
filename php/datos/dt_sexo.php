<?php
class dt_sexo extends cris2_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_sexo, descripcion FROM cidig.sexo ORDER BY descripcion";
		return toba::db('cris2')->consultar($sql);
	}

}

?>