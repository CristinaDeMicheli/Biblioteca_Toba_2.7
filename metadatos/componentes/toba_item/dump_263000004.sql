------------------------------------------------------------
--[263000004]--  Autentificación de Usuarios 
------------------------------------------------------------

------------------------------------------------------------
-- apex_item
------------------------------------------------------------

--- INICIO Grupo de desarrollo 263
INSERT INTO apex_item (item_id, proyecto, item, padre_id, padre_proyecto, padre, carpeta, nivel_acceso, solicitud_tipo, pagina_tipo_proyecto, pagina_tipo, actividad_buffer_proyecto, actividad_buffer, actividad_patron_proyecto, actividad_patron, nombre, descripcion, punto_montaje, actividad_accion, menu, orden, solicitud_registrar, solicitud_obs_tipo_proyecto, solicitud_obs_tipo, solicitud_observacion, solicitud_registrar_cron, prueba_directorios, zona_proyecto, zona, zona_orden, zona_listar, imagen_recurso_origen, imagen, parametro_a, parametro_b, parametro_c, publico, redirecciona, usuario, exportable, creacion, retrasar_headers) VALUES (
	NULL, --item_id
	'cris2', --proyecto
	'263000004', --item
	NULL, --padre_id
	'cris2', --padre_proyecto
	'1', --padre
	'0', --carpeta
	'0', --nivel_acceso
	'web', --solicitud_tipo
	'toba', --pagina_tipo_proyecto
	'logon', --pagina_tipo
	'toba', --actividad_buffer_proyecto
	'0', --actividad_buffer
	'toba', --actividad_patron_proyecto
	'abms_cd_c', --actividad_patron
	'Autentificación de Usuarios', --nombre
	NULL, --descripcion
	'263000002', --punto_montaje
	'', --actividad_accion
	'0', --menu
	NULL, --orden
	'0', --solicitud_registrar
	NULL, --solicitud_obs_tipo_proyecto
	NULL, --solicitud_obs_tipo
	NULL, --solicitud_observacion
	'0', --solicitud_registrar_cron
	NULL, --prueba_directorios
	NULL, --zona_proyecto
	NULL, --zona
	NULL, --zona_orden
	'0', --zona_listar
	NULL, --imagen_recurso_origen
	NULL, --imagen
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	'1', --publico
	'0', --redirecciona
	NULL, --usuario
	'0', --exportable
	'2021-07-06 09:58:13', --creacion
	'1'  --retrasar_headers
);
--- FIN Grupo de desarrollo 263

------------------------------------------------------------
-- apex_item_objeto
------------------------------------------------------------
INSERT INTO apex_item_objeto (item_id, proyecto, item, objeto, orden, inicializar) VALUES (
	NULL, --item_id
	'cris2', --proyecto
	'263000004', --item
	'263000012', --objeto
	'0', --orden
	NULL  --inicializar
);
