------------------------------------------------------------
--[263000158]--  DT - persona 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 263
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'cris2', --proyecto
	'263000158', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'263000002', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'DT - persona', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'cris2', --fuente_datos_proyecto
	'cris2', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2021-08-06 11:18:28', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 263

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'263000002', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'persona', --tabla
	NULL, --tabla_ext
	NULL, --alias
	'0', --modificar_claves
	'cris2', --fuente_datos_proyecto
	'cris2', --fuente_datos
	'1', --permite_actualizacion_automatica
	'cidig', --esquema
	'cidig'  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 263
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000217', --col_id
	'id_persona', --columna
	'E', --tipo
	'1', --pk
	'persona_id_persona_seq', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000218', --col_id
	'id_persona_tipo', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000219', --col_id
	'cuil_tipo', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000220', --col_id
	'cuil_documento', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'40', --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000221', --col_id
	'cuil_digito', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000222', --col_id
	'cuil', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'11', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000223', --col_id
	'id_cond_fiscal', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000224', --col_id
	'email', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'200', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000225', --col_id
	'id_estado', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000226', --col_id
	'id_tipo_documento', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000227', --col_id
	'id_nacionalidad_d', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'cris2', --objeto_proyecto
	'263000158', --objeto
	'263000228', --col_id
	'control_doc', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'40', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'persona'  --tabla
);
--- FIN Grupo de desarrollo 263
