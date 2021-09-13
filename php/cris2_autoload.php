<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class cris2_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'cris2_ci' => 'extension_toba/componentes/cris2_ci.php',
		'cris2_cn' => 'extension_toba/componentes/cris2_cn.php',
		'cris2_datos_relacion' => 'extension_toba/componentes/cris2_datos_relacion.php',
		'cris2_datos_tabla' => 'extension_toba/componentes/cris2_datos_tabla.php',
		'cris2_ei_arbol' => 'extension_toba/componentes/cris2_ei_arbol.php',
		'cris2_ei_archivos' => 'extension_toba/componentes/cris2_ei_archivos.php',
		'cris2_ei_calendario' => 'extension_toba/componentes/cris2_ei_calendario.php',
		'cris2_ei_codigo' => 'extension_toba/componentes/cris2_ei_codigo.php',
		'cris2_ei_cuadro' => 'extension_toba/componentes/cris2_ei_cuadro.php',
		'cris2_ei_esquema' => 'extension_toba/componentes/cris2_ei_esquema.php',
		'cris2_ei_filtro' => 'extension_toba/componentes/cris2_ei_filtro.php',
		'cris2_ei_firma' => 'extension_toba/componentes/cris2_ei_firma.php',
		'cris2_ei_formulario' => 'extension_toba/componentes/cris2_ei_formulario.php',
		'cris2_ei_formulario_ml' => 'extension_toba/componentes/cris2_ei_formulario_ml.php',
		'cris2_ei_grafico' => 'extension_toba/componentes/cris2_ei_grafico.php',
		'cris2_ei_mapa' => 'extension_toba/componentes/cris2_ei_mapa.php',
		'cris2_servicio_web' => 'extension_toba/componentes/cris2_servicio_web.php',
		'cris2_comando' => 'extension_toba/cris2_comando.php',
		'cris2_modelo' => 'extension_toba/cris2_modelo.php',
	);
}
?>