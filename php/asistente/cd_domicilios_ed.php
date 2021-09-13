<?php
class cd_domicilios_ed extends sim_ei_cuadro
{
	//---- Config. EVENTOS sobre fila ---------------------------------------------------

	function conf_evt__ver_ubicacion($evento, $fila)
	{
	   if(!is_null($this->datos[$fila]['punto_geom'])){
	       $evento->mostrar();
           $evento->vinculo()->agregar_parametro('punto_geom', $this->datos[$fila]['punto_geom']);
           $evento->vinculo()->agregar_parametro('nomba', $this->datos[$fila]['id_barrio_nombre']);
           $evento->vinculo()->agregar_parametro('calle', $this->datos[$fila]['id_calle_nombre']);
           $evento->vinculo()->agregar_parametro('altura', $this->datos[$fila]['altura']);
       }
       else{
           $evento->ocultar(); 
       }
	}

}
?>