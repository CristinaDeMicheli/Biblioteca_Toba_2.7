<?php
class ci_personas extends sim_ci
{
    protected $s__filtro = null;
    protected $s__where = '1=1';
    
    function rel()
    {
        return $this->dep('rel_persona');
    }
    
    /** ---------------------------------------------------------------------------------
	//---- fi_personas ------------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fi_personas(sim_ei_filtro $filtro)
	{
	   $filtro->set_datos($this->s__filtro);
       $filtro->columna('cuil')->set_condicion_fija('contiene', true);
       $filtro->columna('apyn')->set_condicion_fija('contiene', true);
       $filtro->columna('razon_social')->set_condicion_fija('contiene', true);
       $filtro->columna('nombre_fantasia')->set_condicion_fija('contiene', true);
	}

    #------
	function evt__fi_personas__filtrar($datos)
	{
	   $this->s__filtro = $datos;
       $this->s__where = $this->dep('fi_personas')->get_sql_where();
      
       $search = array('-', '.', ' '); //si ingres con guiones, con puntos o espacios, se los quito
       $datos['cuil']['valor'] = str_replace($search, "", $datos['cuil']['valor']);
       
       if(isset($datos['cuil']) and strlen($datos['cuil']['valor']) == 11 )
       {
           $this->s__where .= " OR COALESCE(pp.cuil_documento,'')::varchar ILIKE '%".trim(substr($datos['cuil']['valor'],2,8))."%'";
       }
	}

    #------
	function evt__fi_personas__cancelar()
	{
	   $this->s__filtro = null;
       $this->s__where = '1=1';
	}
    
	/** --------------------------------------------------------------------------------- 
	//---- cd_personas ------------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__cd_personas(sim_ei_cuadro $cuadro)
	{
	   $limite = ' LIMIT 100 ';
       
       if($this->s__where != '1=1')
       {
            $limite = ' LIMIT 1000 ';
            $cuadro->set_titulo($cuadro->get_titulo().' (Límite máximo 1000 registros)');
       }
     
	   $sql = "SELECT
                    pp.id_persona,
                    pt.descripcion AS id_persona_tipo_descr,
                    CASE
                        WHEN pp.cuil_tipo IS NOT NULL
                        THEN pp.cuil_tipo::text||'-'||pp.cuil_documento::text||'-'||pp.cuil_digito::text
                        ELSE pp.cuil_documento::text
                    END AS cuil_formateado,
                    
                    CASE
                        WHEN pn.id_persona_natural IS NOT NULL
                        THEN pn.apyn
                        ELSE pj.razon_social||' - '||pj.nombre_fantasia 
                    END AS nombre_raz_social,
                    
                    CASE
                        WHEN pn.id_persona_natural IS NOT NULL 
							THEN
									CASE
										WHEN ee.id_estado <> 1 
										THEN ee.descripcion
										ELSE NULL
									END
							ELSE
									CASE
										WHEN ej.id_estado <> 1 
										THEN ej.descripcion
										ELSE NULL
									END	
                    END AS id_estado_descr
               FROM
                    cidig.persona                       pp
                    LEFT JOIN cidig.persona_tipo        pt ON pt.id_persona_tipo = pp.id_persona_tipo
                    LEFT JOIN cidig.persona_juridica    pj ON pj.id_persona = pp.id_persona
                    LEFT JOIN cidig.persona_natural     pn ON pn.id_persona = pp.id_persona
                    LEFT JOIN cidig.estado              ee ON ee.id_estado = pp.id_estado
                    LEFT JOIN cidig.estado              ej ON ej.id_estado = pj.id_estado
               WHERE ".$this->s__where."
               ORDER BY 1 ".$limite;
   
        $rs = toba::db()->consultar($sql);
        
        if(count($rs) > 0)
        {
            $cuadro->set_datos($rs);
        }
	}

    #------
	function evt__cd_personas__seleccion($seleccion)
	{
	   $this->rel()->cargar($seleccion);
       $this->set_pantalla('pant_edicion');
	}  

	#------ Evento a nivel pantalla - Pant_inicial
	function evt__agregar()
	{
	   $this->rel()->resetear();
       $this->set_pantalla('pant_edicion');
	}
}
?>