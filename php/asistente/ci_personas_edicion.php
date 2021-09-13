<?php
class ci_personas_edicion extends sim_ci
{
    protected $s__carpeta_imagenes = null;
    protected $s__carpeta_imagenes_absoluto = null;
    protected $s__entorno_local = false;
    protected $s__editar = null;
    protected $s__arch_borrar = array();
    protected $s__dni = null;
    
    #------ Se ejecuta cada vez que inicia el CI
    function ini()
    {
        $rs = toba::db()->consultar("SELECT valor FROM cidig.configuracion WHERE parametro = 'CODIGO_CUIL'");
        if(count($rs) > 0){
            if($rs[0]['valor'] > 0){
                define('CODIGO_CUIL', $rs[0]['valor']);
            }
            else{
                define('CODIGO_CUIL', 0);
            }
        }
        else{
            define('CODIGO_CUIL', 0);
        }
        
        $rs = toba::db()->consultar("SELECT valor FROM cidig.configuracion WHERE parametro = 'CODIGO_CUIT'");
        if(count($rs) > 0){
            if($rs[0]['valor'] > 0){
                define('CODIGO_CUIT', $rs[0]['valor']);
            }
            else{
                define('CODIGO_CUIT', 0);
            }
        }
        else{
            define('CODIGO_CUIT', 0);
        }
        
        $rs = toba::db()->consultar("SELECT valor FROM cidig.configuracion WHERE parametro = 'CODIGO_DNI'");
        if(count($rs) > 0){
            if($rs[0]['valor'] > 0){
                define('CODIGO_DNI', $rs[0]['valor']);
            }
            else{
                define('CODIGO_DNI', 0);
            }
        }
        else{
            define('CODIGO_DNI', 0);
        }
    }
    
    #------ Se ejecuta solamente cuando inicia la operación
	function ini__operacion()
	{
	   #- Busco el camino a los documentos de imagen
        if(substr(toba_dir(),0,2) == 'C:'){
            $this->s__entorno_local = true;
        }
        
        $rs = toba::db()->consultar("SELECT valor FROM cidig.configuracion WHERE parametro = 'CARPETA_IMAGENES'");
        $this->s__carpeta_imagenes = $rs[0]['valor'];
        $this->s__carpeta_imagenes_absoluto = '/var/www'.$rs[0]['valor'];
        
        if($this->s__entorno_local){ 
            #- El sistema se está ejecutando desde un entorno local, por ende, de desarrollo
            #- Por este motivo se crean variables que permitan la lectura y escritura en el servidor de desarrollo 
            $this->s__carpeta_imagenes = 'http://192.168.10.200'.$this->s__carpeta_imagenes;    
        }
        
	}    

    #------
    function get_conexion_ssh2()
    {
        $sftp = null;
        if(!($conexion_ssh = ssh2_connect('192.168.10.200', 22))){
            toba::notificacion()->agregar('ATENCION: Ha fallado la conexión SSH con el servidor de Desarrollo.
                                           <br> Las imágenes no se descargarán apropiadamente.');
        }
        else{
            ssh2_auth_password($conexion_ssh, 'root', 'roda1950');
            $sftp = ssh2_sftp($conexion_ssh);
        }
        
        return $sftp;
    }

    #------
    function get_sub_carpeta($cuil)
    {
        #- Verifico que la persona ya no tenga una carpeta asignada ya que el nombre podría cambiar si cambia el tipo de documento.
        $dev = null;
        $ids_filas = $this->rel()->tabla('imagen')->get_id_filas();
        if(count($ids_filas) > 0){
            $nombre_imagen = $this->rel()->tabla('imagen')->get_fila_columna($ids_filas[0], 'nombre_imagen');
            $dev = substr($nombre_imagen,0,15);
        }
        
        if(empty($dev)){
            #- El parámetro recibido puede ser el CUIL o el DNI
            if(empty($cuil)){
                $dev = null;
            }
            else{
                $dev = 'cid'.str_pad($cuil, 11, '0', STR_PAD_LEFT).'/';
            }
        }
        
        return  $dev;
    }
    
    #------
    function get_nombre_imagen($cuil)
    {
        #- El parámetro recibido puede ser el CUIL o el DNI
        $sub_carpeta = $this->get_sub_carpeta($cuil);
        
        #- Busco el  último valor de secuencia para generar el nombre de la nueva imagen imagen 
        $nombres = $this->rel()->tabla('imagen')->get_valores_columna('nombre_imagen');
        $ultima_sec = 0;
        foreach($nombres as $fila){
            list($dummy,$sec_ext) = explode('_', $fila);
            list($sec,$dummy) = explode('.', $sec_ext); 
            if($sec > $ultima_sec){
                $ultima_sec = $sec;
            }
        }
        
        $dev = null;
        if(!empty($sub_carpeta)){
            $dev = $sub_carpeta.'I'.str_pad($cuil, 11, '0', STR_PAD_LEFT).'_'.str_pad($ultima_sec + 1, 5, '0', STR_PAD_LEFT);
        }
        
        return $dev;
    }
    
    #------
    function rel()
    {
        return $this->controlador()->rel();
    }
    
    #-----
   	function evt__volver_lista()
	{
	   $this->rel()->resetear();
       $this->controlador()->set_pantalla('pant_inicial');
	}

    /** ************************************
     *  PANTALLA pant_inicial 
     *  Contiene los datos de las tablas:
     *  - persona
     *  - persona_natural
     *  - persona_juridica
     *  - telefono_persona
     *  - muestra los sistemas en los que la persona tiene algo
     ***************************************/

	/** ---------------------------------------------------------------------------------
	//---- fr_personas_ed ---------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_personas_ed(sim_ei_formulario $form)
	{
	   $mensaje = null;
	   if(!CODIGO_CUIL){
            $mensaje = "El valor del Código del tipo de documento CUIL no está ".
                       "correctamente definido en el parámetro 'CODIGO_CUIL' de la Configuración.<br>".
                       "Pueden ocurrir resultados inesperados para este tipo de documento.<br><br><br>";
       }
       
       if(!CODIGO_CUIT){
            $mensaje .= "El valor del Código del tipo de documento CUIT no está ".
                       "correctamente definido en el parámetro 'CODIGO_CUIT' de la Configuración.<br>".
                       "Pueden ocurrir resultados inesperados para este tipo de documento.<br>";
       }
       
       if(!CODIGO_DNI){
            $mensaje .= "El valor del Código del tipo de documento DNI no está ".
                       "correctamente definido en el parámetro 'CODIGO_DNI' de la Configuración.<br>".
                       "Pueden ocurrir resultados inesperados para este tipo de documento.<br>";
       }
       
       if(!is_null($mensaje)){
            toba::notificacion()->vaciar();
            toba::notificacion()->agregar($mensaje);
       }
       
	   if($this->rel()->tabla('persona')->get_cantidad_filas() > 0)
       {
	       if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- Caso Persona Natural
	           $datos = $this->rel()->tabla('persona')->get() + $this->rel()->tabla('persona_natural')->get();
               list($apellidos, $nombres) = explode(',',$datos['apyn']);
               $datos['apellidos'] = trim($apellidos);
               $datos['nombres'] = trim($nombres);  
	       }
           else{
               $datos = $this->rel()->tabla('persona')->get() + $this->rel()->tabla('juridica')->get();
               $datos['estado_pj'] =  $this->rel()->tabla('juridica')->get_columna('id_estado');
           }
           
           #- Veo si hay foto
           $id_fila = $this->rel()->tabla('imagen')->get_id_fila_condicion(array('id_tipo_contenido_img'=>1));
           if(count($id_fila) > 0){
                $form->ef('imagen')->set_estado("<img src='".$this->s__carpeta_imagenes.
                                                             $this->rel()->tabla('imagen')->get_fila_columna($id_fila[0],'nombre_imagen').
                                                           "' height='100' width='100'>");
           } 
	   }
       
       $form->set_datos($datos);
	}

    #------
	function evt__fr_personas_ed__modificacion($datos)
	{  
       if($datos['id_nacionalidad_d'] <> 1){#En caso de ser nacionalidad extranjera no se guardan los valores del cuil
           $datos['cuil'] = $datos['cuil_tipo'] = $datos['cuil_digito'] = null;
       }

	   if($datos['cuil_tipo'] <> 0 and !is_null($datos['cuil_digito'])){
	       $datos['cuil'] = $datos['cuil_tipo'].str_pad($datos['cuil_documento'], 8, "0", STR_PAD_LEFT).$datos['cuil_digito'];
	   }
       else{
           $datos['cuil'] = $datos['cuil_tipo'] =  $datos['cuil_digito'] = null;
       }
             
       $datos['cuil_documento'] = strtoupper($datos['cuil_documento']);
       
       $datos['control_doc'] = $datos['cuil_tipo'].$datos['cuil_documento'].$datos['cuil_digito'].$datos['id_tipo_documento'].$datos['id_nacionalidad_d']; 
       $this->rel()->tabla('persona')->set($datos);
       
	   if($datos['id_persona_tipo'] == 1){ #- Caso Persona Natural 
	       $datos['apyn'] = trim(strtoupper($datos['apellidos'])).", ".trim(ucwords(strtolower($datos['nombres'])));    
           $this->rel()->tabla('persona_natural')->set($datos);
           $this->rel()->tabla('integrantes_pj')->eliminar_filas();
           $this->rel()->tabla('actividad')->eliminar_filas();
           $this->rel()->tabla('juridica')->set();
       }
       else{ #- Caso Personas Jurídicas
           $this->rel()->tabla('juridica')->set($datos);
           $this->rel()->tabla('juridica')->set(array('id_estado'=>$datos['id_estado_pj']));
           $this->rel()->tabla('ocupacion')->eliminar_filas();
           $this->rel()->tabla('persona_natural')->set();
       }
       
	}

	/** ----------------------------------------------------------------------------------
	//---- cd_telefonos_ed ---------------------------------------------------------------
	//----------------------------------------------------------------------------------*/

	function conf__cd_telefonos_ed(sim_ei_cuadro $cuadro)
	{
	   $cuadro->set_datos($this->rel()->tabla('telefono')->get_filas());
	}

    #------
	function evt__cd_telefonos_ed__seleccion($seleccion)
	{
	   $this->rel()->tabla('telefono')->set_cursor($seleccion);
	}

	/** ---------------------------------------------------------------------------------
	//---- fr_telefonos_ed --------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_telefonos_ed(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('telefono')->hay_cursor()){
	       $form->set_datos($this->rel()->tabla('telefono')->get());
	   }
	}

    #------
	function evt__fr_telefonos_ed__alta($datos)
	{
	   $this->rel()->tabla('telefono')->nueva_fila($datos);
	}

    #------
	function evt__fr_telefonos_ed__baja()
	{
	   $this->rel()->tabla('telefono')->set();
	}

    #------
	function evt__fr_telefonos_ed__modificacion($datos)
	{
	   $this->rel()->tabla('telefono')->set($datos);
       $this->rel()->tabla('telefono')->resetear_cursor();
	}

    #------
	function evt__fr_telefonos_ed__cancelar()
	{
	   $this->rel()->tabla('telefono')->resetear_cursor();
	}

   	/** ---------------------------------------------------------------------------------
	//---- cd_ocupaciones_ed ------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__cd_ocupaciones_ed(sim_ei_cuadro $cuadro)
	{
	   $cuadro->set_datos($this->rel()->tabla('ocupacion')->get_filas());
	}

    #------
	function evt__cd_ocupaciones_ed__seleccion($seleccion)
	{
	   $this->rel()->tabla('ocupacion')->set_cursor($seleccion);
	}

	/** ---------------------------------------------------------------------------------
	//---- fr_ocupaciones_ed ------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_ocupaciones_ed(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('ocupacion')->hay_cursor()){
	       $form->set_datos($this->rel()->tabla('ocupacion')->get());
	   }
	}

    #------
	function evt__fr_ocupaciones_ed__alta($datos)
	{
	   $this->rel()->tabla('ocupacion')->nueva_fila($datos);
	}

    #------
	function evt__fr_ocupaciones_ed__baja()
	{
	   $this->rel()->tabla('ocupacion')->set();
	}

    #------
	function evt__fr_ocupaciones_ed__modificacion($datos)
	{
	   $this->rel()->tabla('ocupacion')->set($datos);
       $this->rel()->tabla('ocupacion')->resetear_cursor();
	}

    #------
	function evt__fr_ocupaciones_ed__cancelar()
	{
	   $this->rel()->tabla('ocupacion')->resetear_cursor();
	}

	/** ---------------------------------------------------------------------------------
	//---- cd_actividades_ed ------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__cd_actividades_ed(sim_ei_cuadro $cuadro)
	{
	   $cuadro->set_datos($this->rel()->tabla('actividad')->get_filas());
	}

    #------
	function evt__cd_actividades_ed__seleccion($seleccion)
	{
	   $this->rel()->tabla('actividad')->set_cursor($seleccion);
	}

	/** ---------------------------------------------------------------------------------
	//---- fr_actividades_ed ------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_actividades_ed(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('actividad')->hay_cursor()){
	       $form->set_datos($this->rel()->tabla('actividad')->get());
	   }
	}

    #------
	function evt__fr_actividades_ed__alta($datos)
	{
	   $this->rel()->tabla('actividad')->nueva_fila($datos);
	}

    #------
	function evt__fr_actividades_ed__baja()
	{
	   $this->rel()->tabla('actividad')->set();
	}

    #------
	function evt__fr_actividades_ed__modificacion($datos)
	{
	   $this->rel()->tabla('actividad')->set($datos);
       $this->rel()->tabla('actividad')->resetear_cursor();
	}

    #------
	function evt__fr_actividades_ed__cancelar()
	{
	   $this->rel()->tabla('actividad')->resetear_cursor();
	}

    /** ************************************
     *  PANTALLA pant_integrantes_pj 
     *  Contiene los datos de las tablas:
     *  - integrantes_pj
     ***************************************/
	/** ---------------------------------------------------------------------------------
	//---- fr_id_persona_integrantes ----------------------------------------------------
	//---------------------------------------------------------------------------------*/
    #- Este Form es solo para mostrar el nombre de la persona que se está editando.
	function conf__fr_id_persona_integrantes(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- Caso persona natural
            $persona = $this->rel()->tabla('persona_natural')->get_columna('apyn');
	   }
       else{ #- Caso persona jurídica
            $persona = $this->rel()->tabla('juridica')->get_columna('razon_social');
       }
	   
       $form->set_datos(array('persona'=>$persona));
	}

	/** ---------------------------------------------------------------------------------
	//---- cd_integrantes_pj ------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__cd_integrantes_pj(sim_ei_cuadro $cuadro)
	{
	   if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- Caso persona natural
	       $cuadro->set_titulo($cuadro->get_titulo().' Lista de Personas Jurídicas de las cuales es miembro');
           $cuadro->eliminar_columnas(array('pj_cuil', 'id_persona_juridica_nombre'));
	   }
       else{ #- Caso persona jurídica
           $cuadro->set_titulo($cuadro->get_titulo().' Lista de Personas Naturales miembros');
           $cuadro->eliminar_columnas(array('pn_cuil', 'id_persona_natural_nombre'));
       }
       
       $cuadro->set_datos($this->rel()->tabla('integrantes_pj')->get_filas());
	}

    #------
	function evt__cd_integrantes_pj__seleccion($seleccion)
	{
	   $this->rel()->tabla('integrantes_pj')->set_cursor($seleccion);
	}

	/** ---------------------------------------------------------------------------------
	//---- fr_integrantes_pj ------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_integrantes_pj(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('integrantes_pj')->hay_cursor()){
	       $datos = $this->rel()->tabla('integrantes_pj')->get();
	       if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- La persona que se está editando es natural
	           $persona = $this->cn()->get_nombre_persona_juridica($datos['id_persona_juridica']);
               $datos['id_persona'] = $persona[0]['id_persona'];
	       }
           else{ #- La persona que se está editando es jurídica
	           $persona = $this->cn()->get_nombre_persona_natural($datos['id_persona_natural']);
               $datos['id_persona'] = $persona[0]['id_persona'];                
           }
           
           $form->set_datos($datos);
	   }
       $form->ef('id_persona')->vinculo()->agregar_parametro('id_persona_tipo',$this->rel()->tabla('persona')->get_columna('id_persona_tipo'));
       
	}

    #------
	function evt__fr_integrantes_pj__alta($datos)
	{
	   $persona = $this->cn()->get_nombre_id_persona($datos['id_persona']);
	   if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- La persona que se está editando es natural
	       $datos['id_persona_juridica'] = $persona[0]['id_persona_juridica'];
	   }
       else{ #- La persona que se está editando es jurídica
           $datos['id_persona_natural'] = $persona[0]['id_persona_natural']; 
       }
       
	   $this->rel()->tabla('integrantes_pj')->nueva_fila($datos);
       
	}

    #------
	function evt__fr_integrantes_pj__baja()
	{
	   $this->rel()->tabla('integrantes_pj')->set();
	}

    #------
	function evt__fr_integrantes_pj__modificacion($datos)
	{
	   $persona = $this->cn()->get_nombre_id_persona($datos['id_persona']);
	   if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- La persona que se está editando es natural
	       $datos['id_persona_juridica'] = $persona[0]['id_persona_juridica'];
	   }
       else{ #- La persona que se está editando es jurídica
           $datos['id_persona_natural'] = $persona[0]['id_persona_natural']; 
       }
       
       $this->rel()->tabla('integrantes_pj')->set($datos);
       $this->rel()->tabla('integrantes_pj')->resetear_cursor();
	}

    #------
	function evt__fr_integrantes_pj__cancelar()
	{
	   $this->rel()->tabla('integrantes_pj')->resetear_cursor();
	}

    /** ************************************
     *  PANTALLA pant_domicilios 
     *  Contiene los datos de las tablas:
     *  - personas_domicilio
     ***************************************/
	/** ---------------------------------------------------------------------------------
	//---- fr_id_persona_domicilios -----------------------------------------------------
	//---------------------------------------------------------------------------------*/
    #- Este Form es solo para mostrar el nombre de la persona que se está editando.
	function conf__fr_id_persona_domicilios(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- Caso persona natural
            $persona = $this->rel()->tabla('persona_natural')->get_columna('apyn');
	   }
       else{ #- Caso persona jurídica
            $persona = $this->rel()->tabla('juridica')->get_columna('razon_social');
       }
	   
       $form->set_datos(array('persona'=>$persona));
	}

	/** ---------------------------------------------------------------------------------
	//---- cd_domicilios_ed -------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__cd_domicilios_ed(sim_ei_cuadro $cuadro)
	{
	   $cuadro->set_datos($this->rel()->tabla('pers_domi')->get_filas());
	}

    #------
	function evt__cd_domicilios_ed__seleccion($seleccion)
	{
	   $this->rel()->tabla('pers_domi')->set_cursor($seleccion);
	}

	/** ---------------------------------------------------------------------------------
	//---- fr_domicilios_ed -------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_domicilios_ed(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('pers_domi')->hay_cursor())
       {
	       $form->set_datos($this->rel()->tabla('pers_domi')->get());
           //envio el parametro al popup asi cuando quiere editar le mando lo que tiene que tener selecionado - JM pidio 12/11/20
           $form->ef('id_domicilio')->vinculo()->agregar_parametro('id',$this->rel()->tabla('pers_domi')->get_columna('id_domicilio'));
	   }
	}

    #------
	function evt__fr_domicilios_ed__alta($datos)
	{
	   $this->rel()->tabla('pers_domi')->nueva_fila($datos);
	}

    #------
	function evt__fr_domicilios_ed__baja()
	{
	   $this->rel()->tabla('pers_domi')->set();
	}

    #------
	function evt__fr_domicilios_ed__modificacion($datos)
	{
	   $this->rel()->tabla('pers_domi')->set($datos);
       $this->rel()->tabla('pers_domi')->resetear_cursor();
	}

    #------
	function evt__fr_domicilios_ed__cancelar()
	{
	   $this->rel()->tabla('pers_domi')->resetear_cursor();
	}

    #------
    
    /** ************************************
     *  PANTALLA pant_imagenes 
     *  Contiene los datos de las tablas:
     *  - imagenes
     ***************************************/
     
	/** ---------------------------------------------------------------------------------
	//---- fr_id_persona_imagenes -------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_id_persona_imagenes(sim_ei_formulario $form)
	{
       if($this->rel()->tabla('persona')->get_columna('id_persona_tipo') == 1){ #- Caso persona natural
            $persona = $this->rel()->tabla('persona_natural')->get_columna('apyn');
	   }
       else{ #- Caso persona jurídica
            $persona = $this->rel()->tabla('juridica')->get_columna('razon_social');
       }
       
       $form->set_datos(array('persona'=>$persona));
	}

	/** ---------------------------------------------------------------------------------
	//---- cd_imagenes_ed ---------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__cd_imagenes_ed(sim_ei_cuadro $cuadro)
	{
	   $cuadro->set_datos($this->rel()->tabla('imagen')->get_filas());
	}

    #------
	function evt__cd_imagenes_ed__seleccion($seleccion)
	{
	   $this->rel()->tabla('imagen')->set_cursor($seleccion);
	}

	/** ---------------------------------------------------------------------------------
	//---- fr_imagenes_ed ---------------------------------------------------------------
	//---------------------------------------------------------------------------------*/

	function conf__fr_imagenes_ed(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('imagen')->hay_cursor()){
	       $form->set_datos($this->rel()->tabla('imagen')->get());
	   }
	}

    #------
	function evt__fr_imagenes_ed__alta($datos)
	{
       $descargar = true;
       $modificar_tamaño = false;
       $crear_fila = false;
       $descarga_ok = false;
       
        #-- Consisto y actualizo el DT
        if (trim($datos['imagen']['name']) == ""){
    	   toba::notificacion()->error('No se ha especificado ningún documento. Se ignora el registro.');
           $descargar = false;
        }
        else{
           $tp = array("jpg", "jpeg", "pjpeg", "gif", "png", "pdf");
           list($tipo,$extension) = explode('/',$datos['imagen']['type']);
           $crear_fila = true;
           switch(strtolower($extension)) 
    	   {
              case $tp[0]:
              case $tp[1]:
              case $tp[2]: #- Casos jpg, jpeg y pjpeg
              case $tp[3]: #- Caso gif
              case $tp[4]: #- Caso png
                   $tam = getimagesize($datos['documento']['tmp_name']);
                   if($tam[0] > 800 or $tam[1] > 800){   
                       $modificar_tamaño = true;
                   }
                   break;
              case $tp[5]: #- Caso pdf
                   break; 
              default:
                   toba::notificacion()->error('El tipo de archivo no pertenece a ninguno de los aceptados. Se ignora el registro.');
                   $descargar = false;
                   $crear_fila = false;
    	   }
        }
        
        if($modificar_tamaño){
            $imagen = $this->modificar_tamanio($datos['imagen']['name'],$datos['imagen']['tmp_name']);
            $temp_nombre = md5(uniqid(time()));
            $temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
      
        	#-- Se pasa el contenido al archivo temporal
            $temp_fp = fopen($temp_archivo['path'], 'wb');
            fwrite($temp_fp,$imagen);
            fclose($temp_fp);
        }
        
        if($descargar){
           $datos['fe_alta'] = date('Y-m-d');
           $cuil_doc = trim($this->rel()->tabla('persona')->get_columna('cuil_tipo').
                            str_pad($this->rel()->tabla('persona')->get_columna('cuil_documento'), 8, '0', STR_PAD_LEFT).
                            $this->rel()->tabla('persona')->get_columna('cuil_digito')
                           );
            
           #- Obtengo los nombres (PATH) de la subcarpeta correspondiente y el archivo SIN la extensión, la que le agrego para completar
           #- en nombre de la imagen
           $nombre_imagen = $this->get_nombre_imagen($cuil_doc).".".$extension;
           $control = ".".$extension;
           
           if($nombre_imagen <> $control){ #-- Para prevenir que venga sin camino y nombre de imagen
               #- Analizo si la subcarpeta destino existe.
               if($this->s__entorno_local){
                    $sftp = $this->get_conexion_ssh2();
                    $carpeta_existe = file_exists('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$this->get_sub_carpeta($cuil_doc));
               }
               else{
                    $carpeta_existe = file_exists($this->s__carpeta_imagenes_absoluto.$this->get_sub_carpeta($cuil_doc));
               }
               
               if(!$carpeta_existe){
                    if($this->s__entorno_local){
                        mkdir('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$this->get_sub_carpeta($cuil_doc));
                        chmod('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$this->get_sub_carpeta($cuil_doc),0777);
                    }
                    else{
                        mkdir($this->s__carpeta_imagenes_absoluto.$this->get_sub_carpeta($cuil_doc));
                        chmod($this->s__carpeta_imagenes_absoluto.$this->get_sub_carpeta($cuil_doc),0777);
                    }
               }
    
              
               $datos['nombre_imagen'] = $nombre_imagen; #- Ya trae adosado el nombre de la subcarpeta.
               if($modificar_tamaño){
                  if($this->s__entorno_local){
                    $descarga_ok =  ssh2_scp_send($sftp , $temp_archivo['path'], $this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                  }
                  else{
                    $descarga_ok = copy($temp_archivo['path'],$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                  } 
                  unlink($temp_archivo['path']);
               }
               else{
                  if($this->s__entorno_local){
                    $descarga_ok = move_uploaded_file($datos['imagen']['tmp_name'],'ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                  }
                  else{
                    $descarga_ok = move_uploaded_file($datos['imagen']['tmp_name'],$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                  } 
                  
               }  
               
               if($descarga_ok){
                   if($crear_fila){
                      if($this->s__entorno_local){
                        chmod('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$nombre_imagen, 0777);
                      }
                      else{
                        chmod($this->s__carpeta_imagenes_absoluto.$nombre_imagen, 0777);
                      } 
                      $this->rel()->tabla('imagen')->nueva_fila($datos);
                   }
               }
               else{ 
                  toba::notificacion()->error($datos['imagen']['name']." - Falló la descarga.");
               }  
            }
            else{
                ei_arbol($nombre_imagen);
                toba::notificacion()->error("Se produjo un error en la configuración del nombre de la imagen. Informar a Sistemas");
            }
        }
	}

    #------ Cambiar el tamaño de una foto
	function modificar_tamanio($nombre,$archivo)
	{
		define("maximo",800); #-- ancho o altura máximas de la imágen en pixeles
		$tipos = array("jpg","jpeg", "pjpeg", "gif", "png");
	    $tmp = explode(".",$nombre);
	    
		switch(strtolower($tmp[1])) 
		{
		   case $tipos[0]:
		   case $tipos[1]:
		   case $tipos[2]:
		    	$img=imagecreatefromjpeg($archivo);
        		break;
		   case $tipos[3]:
	       		$img = imagecreatefromgif($archivo);
       			break;
		   case $tipos[4]:
		      	$img=imagecreatefrompng($archivo);
				break;
		}
		
	    #-- redimensiono la imagen
	    $datos = getimagesize($archivo);
	    if($datos[1] > $datos[0]){#- altura > ancho
	    	$altura = maximo;
	    	$ancho = round(800 * $datos[0]/$datos[1]);
	    }
		else{#- ancho >= alto
	    	$ancho = maximo;
	    	$altura = round(800 * $datos[1]/$datos[0]);
	    }
	    
	    $imagen = imagecreatetruecolor($ancho, $altura);
	    
	    imagecopyresampled($imagen, $img, 0, 0, 0, 0, $ancho, $altura, $datos[0], $datos[1]);
	    
	    ob_start();
		switch(strtolower($tmp[1])) 
		{
		    case $tipos[0]:
		    case $tipos[1]:
		    case $tipos[2]:
		         imagejpeg($imagen);
		         break;
		         
		    case $tipos[3]:
		         imagegif($imagen);
		         break;
		        
		    case $tipos[4]:
		         imagepng($imagen);
		         break;
		}
		    
		$imagen = ob_get_contents();
	    ob_end_clean();
	    
	    return $imagen;
    }
    
    #------
	function evt__fr_imagenes_ed__baja()
	{
	   $this->s__arch_borrar[] = $this->rel()->tabla('imagen')->get_columna('nombre_imagen');
	   $this->rel()->tabla('imagen')->set();
	}

    #------
	function evt__fr_imagenes_ed__modificacion($datos)
	{
	   if($this->s__entorno_local){
	       $sftp = $this->get_conexion_ssh2();
       }
	   $datos_old = $this->rel()->tabla('imagen')->get();
       list($dummy, $extension_old) = explode('.',$datos_old['nombre_imagen']);
       $descargar = true;
       $actualizar = true;
       

        #-- Consisto y actualizo el DT
        if (trim($datos['imagen']['name']) == ""){ #- No se modifica la imagen
           $descargar = false;
        }
        else{ #- Se modifica la imagen
           $tp = array("jpg", "jpeg", "pjpeg", "gif", "png", "pdf");
           list($tipo,$extension) = explode('/',$datos['imagen']['type']);
           
           $crear_fila = true;
           switch(strtolower($extension)) 
    	   {
              case $tp[0]:
              case $tp[1]:
              case $tp[2]: #- Casos jpg, jpeg y pjpeg
              case $tp[3]: #- Caso gif
              case $tp[4]: #- Caso png
                   $tam = getimagesize($datos['documento']['tmp_name']);
                   if($tam[0] > 800 or $tam[1] > 800){   
                       $modificar_tamaño = true;
                   }
                   break;
              case $tp[5]: #- Caso pdf
                   break; 
              default:
                   toba::notificacion()->error('El tipo de archivo no pertenece a ninguno de los aceptados. Se ignora el registro.');
                   $descargar = false;
                   $crear_fila = false;
    	   }
        }

        if($modificar_tamaño){
            $imagen = $this->modificar_tamanio($datos['imagen']['name'],$datos['imagen']['tmp_name']);
            $temp_nombre = md5(uniqid(time()));
            $temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
      
        	#-- Se pasa el contenido al archivo temporal
            $temp_fp = fopen($temp_archivo['path'], 'wb');
            fwrite($temp_fp,$imagen);
            fclose($temp_fp);
        }

        if($descargar){ #- Se modifica imagen y datos
           $datos['fe_alta'] = date('Y-m-d');
       
           if($extension == $extension_old){ #- La nueva imagen es del mismo tipo a la anterior - Se mantiene el nombre
                $nombre_imagen = $datos_old['nombre_imagen'];
           }
           else{ #- Cambio la extensión del nombre de la imagen y elimino la versión anterior
                list($nombre_sin_ext, $dummy) = explode('.',$datos_old['nombre_imagen']);
                $nombre_imagen = $nombre_sin_ext.'.'.$extension;
                if($this->s__entorno_local){
                    unlink('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$datos_old['nombre_imagen']);
                }
                else{
                    unlink($this->s__carpeta_imagenes_absoluto.$datos_old['nombre_imagen']);
                }
           }
          
           $datos['nombre_imagen'] = $nombre_imagen;
           if($modificar_tamaño){
                if($this->s__entorno_local){
                    $descarga_ok = copy($temp_archivo['path'],'ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                }
                else{
                    $descarga_ok = copy($temp_archivo['path'],$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                }           
                unlink($temp_archivo['path']);
           }
           else{
                if($this->s__entorno_local){
                    $descarga_ok = move_uploaded_file($datos['imagen']['tmp_name'],'ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                }
                else{
                    $descarga_ok = move_uploaded_file($datos['imagen']['tmp_name'],$this->s__carpeta_imagenes_absoluto.$nombre_imagen);
                }
           }  
           
           if($descarga_ok){
                if($this->s__entorno_local){
                    chmod('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$nombre_imagen, 0777);
                }
                else{
                    chmod($this->s__carpeta_imagenes_absoluto.$nombre_imagen, 0777);
                }
                $this->rel()->tabla('imagen')->set($datos);
                $this->rel()->tabla('imagen')->resetear_cursor();                
            }
            else{
                toba::notificacion()->error($datos['imagen']['name']." - Falló la descarga.");
            }      
        }
        else{ #- Se modifican solo los datos
            $this->rel()->tabla('imagen')->set($datos);
            $this->rel()->tabla('imagen')->resetear_cursor();
        }
	}

    #------
	function evt__fr_imagenes_ed__cancelar()
	{
	   $this->rel()->tabla('imagen')->resetear_cursor();
	}
    
    /** ---------------------------------------------------------------------------------
	//---- Pantalla Modulos de sistema
	//---------------------------------------------------------------------------------*/
    //-----------------------------------------------------------------------------------
	//---- fr_modulos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__fr_modulos(sim_ei_formulario $form)
	{
	   if($this->rel()->tabla('persona')->hay_cursor())
       {
          $id = $this->rel()->tabla('persona')->get_columna('id_persona'); 
          $sql = "select * 
                  from (select distinct on (p.id_persona)p.id_persona, 
                               case when p.cuil is not null then cuil else p.cuil_documento end as dni, 
                               case when pn.id_persona is not null then apyn 
                                    when pj.id_persona is not null then razon_social end as apyn,
                               case when (d.id_barrio is not null or (d.id_calle is not null and d.altura is not null)) 
                                    then (COALESCE(b.nombre_barrio,'') ||' '|| c.nombre ||' '|| d.altura)
                                    else d.otro_dato_domi end as domicilio, tp.numero, p.email
                        from cidig.persona p
                             left join cidig.persona_natural pn on pn.id_persona = p.id_persona
                             left join cidig.persona_juridica pj on pj.id_persona = p.id_persona
                             left join cidig.persona_domicilio pd on pd.id_persona = p.id_persona
                             left join cidig.domicilio d on d.id_domicilio = pd.id_domicilio
                             left join public.barrios_185 b on b.id_barrio = d.id_barrio
                             left join public.calles_185 c on c.id_calle = d.id_calle
                             left join cidig.telefono_persona tp on tp.id_persona = p.id_persona
                        order by p.id_persona, tp.id_telefono_tipo desc     ) x
                  where id_persona = $id";
          $rs = toba::db()->consultar($sql);
          $form->set_datos($rs[0]);
                    
          if(strlen($rs[0]['dni']) == 11) //era un cuit
          { 
             $this->s__dni = substr($rs[0]['dni'],2,8);
             $form->ef('dni')->set_estado(substr($rs[0]['dni'] ,0,2).'-'.substr($rs[0]['dni'] ,2,8).'-'. substr($rs[0]['dni'],9,1)); 
          }
          else //es un dni
          {
             $this->s__dni = $rs[0]['dni']; //tomo asi como esta
          }
        
       }
	}
    
    
	//-----------------------------------------------------------------------------------
	//---- cd_modulos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cd_modulos(sim_ei_cuadro $cuadro)
	{
	   $sql = "with persona as (
               select * 
               from (select distinct on (p.id_persona)p.id_persona, case when p.cuil is not null then cuil else p.cuil_documento end as dni, 
                            case when pn.id_persona is not null then apyn 
                                 when pj.id_persona is not null then razon_social end as apyn,
                            case when (d.id_barrio is not null or (d.id_calle is not null and d.altura is not null)) 
                                 then (COALESCE(b.nombre_barrio,'') ||' '|| c.nombre ||' '|| d.altura)
                                 else d.otro_dato_domi end as domicilio, tp.numero, p.email
                     from cidig.persona p
                          left join cidig.persona_natural pn on pn.id_persona = p.id_persona
                          left join cidig.persona_juridica pj on pj.id_persona = p.id_persona
                          left join cidig.persona_domicilio pd on pd.id_persona = p.id_persona
                          left join cidig.domicilio d on d.id_domicilio = pd.id_domicilio
                          left join public.barrios_185 b on b.id_barrio = d.id_barrio
                          left join public.calles_185 c on c.id_calle = d.id_calle
                          left join cidig.telefono_persona tp on tp.id_persona = p.id_persona
                     order by p.id_persona, tp.id_telefono_tipo desc) x
               where dni ilike '%$this->s__dni%'),

               libreta as (
               select 'Libreta Sanitaria'::text as sistema, h.nro_hab::text as nro, h.fe_emision::date as fecha, 
                      h.fe_vencimiento::text as vencimiento, p.id_persona
               from hab.habilitaciones h
                    inner join persona p on p.id_persona::text = h.id_entidad
               where h.id_habilitacion_tipo in (3,4)
               limit 1),
 
               comercial as (
               select 'Habilitación Comercial'::text as sistema, h.nro_hab::text as nro, h.fe_emision::date as fecha, 
                      h.fe_vencimiento::text as vencimiento, p.id_persona
               from hab.habilitaciones h
                    inner join persona p on p.id_persona::text = h.id_entidad
               where h.id_habilitacion_tipo = 11
               limit 1), 

               reservado as (
               select 'Espacio Reservado'::text as sistema, h.nro_hab::text as nro, h.fe_emision::date as fecha, 
                      h.fe_vencimiento::text as vencimiento, p.id_persona
               from hab.habilitaciones h
                    inner join persona p on p.id_persona::text = h.id_entidad
               where h.id_habilitacion_tipo = 12
               limit 1),

               hab_x as (
               select descripcion::text as sistema, h.nro_hab::text as nro, h.fe_emision::date as fecha, 
                      h.fe_vencimiento::text as vencimiento, p.id_persona
               from hab.habilitaciones h
                    inner join persona p on p.id_persona::text = h.id_entidad
                    inner join hab.habilitacion_tipo ht on ht.id_habilitacion_tipo = h.id_habilitacion_tipo
               where h.id_habilitacion_tipo not in (3,4,11,12)
               limit 1),

               expedientes as (
               select 'Expedientes'::text as sistema, (substring(m.expe_num::text,1,1) ||'/'|| substring(m.expe_num::text,2,4) ||'-'|| 
                      substring(m.expe_num::text,6,1) ||'-'|| substring(m.expe_num::text,7,5)) as nro, 
                      m.fecha::date as fecha, ''::text as vencimiento, p.id_persona
               from expedientes.ex_maestro m
                    inner join persona p on p.id_persona = m.id_persona
               order by m.fecha desc
               ),

               sac as (
               select 'SAC'::text as sistema, c.id_contacto::text as nro, substr(c.fecha_hora_contacto::text,0,11)::date as fecha, 
                      ''::text as vencimiento, p.id_persona
               from sac.contactos c
                     inner join persona p on p.id_persona = c.id_persona
               order by c.id_contacto desc
               ),
               
               turnos as (
               select 'Turnos'::text as sistema, s.id_solicitud::text as nro, s.fecha_turno::date as fecha, ''::text as vencimiento, p.id_persona
               from turnos.solicitudes s
                    inner join persona p on p.id_persona = s.id_persona
               ),

               sube as (
               SELECT 'Sube'::text as sistema, g.nro::text, g.fecha::date as fecha, ''::text as vencimiento, g.dni::int
               FROM persona p
                    inner join (SELECT * 
                                FROM dblink('dbname=bdSistemas host=192.168.10.185 user=postgres password=09dgc06mcc',
               		                        'SELECT b.id_per, p.dni, p.apellido, p.nombres, b.id_bene, e.descripcion_be, numero_tarjeta as nro, 
                                                    b.fecha_alta as fecha, rcopia
                	                         FROM sube.personas_benefi b
                	                              inner join sube.personas p on p.id_per=b.id_per 
                	                              inner join sube.beneficio e ON e.id_bene=b.id_bene
                	                         WHERE p.activo and b.fecha_baja is null AND  b.imprimir IS NOT NULL') 
                			                 AS personas (id_per integer, dni varchar(10), apellido varchar(70), nombres varchar(70), 
                                             id_bene integer, descripcion_be varchar(50), nro varchar(32), fecha date, rcopia integer)) 
                                g on g.dni = p.dni
                order by g.fecha DESC
                limit 1)              

                select *
                from (select * from expedientes
                      union
                      select * from sac
                      UNION
                      select * from turnos
                      union
                      select * from hab_x
                      union
                      select * from libreta 
                      UNION
                      select * from comercial
                      UNION
                      select * from reservado
                      UNION
                      select * from sube) x
                order by x.sistema, x.fecha desc";
       $datos = toba::db()->consultar($sql);
       $cuadro->set_datos($datos);
	}

	/** ---------------------------------------------------------------------------------
	//---- Eventos comunes atodas las pantallas del CI
	//---------------------------------------------------------------------------------*/
	function evt__guardar()
	{
		#- Primero elimino físicamente todos los archivos de imágenes marcadas para borrar
		if(count($this->s__arch_borrar) > 0){
		  if($this->s__entorno_local){
            $sftp = $this->get_conexion_ssh2();
          }
			foreach($this->s__arch_borrar as $archivo){
				if($this->s__entorno_local){
					unlink('ssh2.sftp://'.$sftp.$this->s__carpeta_imagenes_absoluto.$archivo);
				}
				else{
					unlink($this->s__carpeta_imagenes_absoluto.$archivo);
				}
			}
		}
       
       try{
	       $this->rel()->sincronizar();
           $this->rel()->resetear();
           $this->controlador()->set_pantalla('pant_inicial');
	   }
       catch(toba_error $e){
            $ok = false;
            $sql = "SELECT descripcion FROM public.errores_sql WHERE id_sqlstate = '".$e->get_sqlstate()."'";
            $rs = toba::db()->consultar($sql);
            
            if(count($rs) > 0){
                $mensaje = $rs[0]['descripcion'];
            }
            else{
                $mensaje = $e->get_mensaje_motor();
            }
		  	toba::notificacion()->agregar($mensaje);
       }
	}
    }
?>