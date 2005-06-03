<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Spanish/lan_content.php,v $
|     $Revision: 1.2 $
|     $Date: 2005-06-03 22:34:18 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/

define("CONTENT_ICON_LAN_0", "Editar");
define("CONTENT_ICON_LAN_1", "Eliminar");
define("CONTENT_ICON_LAN_2", "Opciones");
define("CONTENT_ICON_LAN_3", "Detalles de usuario");
define("CONTENT_ICON_LAN_4", "Descargar adjunto");
define("CONTENT_ICON_LAN_5", "Nuevo");
define("CONTENT_ICON_LAN_6", "Enviar contenido");
define("CONTENT_ICON_LAN_7", "Lista del autor");
define("CONTENT_ICON_LAN_8", "Atención");
define("CONTENT_ICON_LAN_9", "ok");
define("CONTENT_ICON_LAN_10", "error");
define("CONTENT_ICON_LAN_11", "ordenar por categoría");
define("CONTENT_ICON_LAN_12", "ordenar por padre principal");
define("CONTENT_ICON_LAN_13", "Admin personal");
define("CONTENT_ICON_LAN_14", "Conf. contenidos personal");

if (!defined('CONTENT_ICON_EDIT')) { define("CONTENT_ICON_EDIT", "<img src='".e_PLUGIN."content/images/maintain_16.png' alt='".CONTENT_ICON_LAN_0."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_DELETE')) { define("CONTENT_ICON_DELETE", "<img src='".e_PLUGIN."content/images/banlist_16.png' alt='".CONTENT_ICON_LAN_1."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_DELETE_BASE')) { define("CONTENT_ICON_DELETE_BASE", e_PLUGIN."content/images/banlist_16.png"); }
if (!defined('CONTENT_ICON_OPTIONS')) { define("CONTENT_ICON_OPTIONS", "<img src='".e_PLUGIN."content/images/cat_settings_16.png' alt='".CONTENT_ICON_LAN_2."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_USER')) { define("CONTENT_ICON_USER", "<img src='".e_PLUGIN."content/images/users_16.png' alt='".CONTENT_ICON_LAN_3."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_FILE')) { define("CONTENT_ICON_FILE", "<img src='".e_PLUGIN."content/images/file_16.png' alt='".CONTENT_ICON_LAN_4."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_NEW')) { define("CONTENT_ICON_NEW", "<img src='".e_PLUGIN."content/images/articles_16.png' alt='".CONTENT_ICON_LAN_5."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_SUBMIT')) { define("CONTENT_ICON_SUBMIT", "<img src='".e_PLUGIN."content/images/redo.png' alt='".CONTENT_ICON_LAN_6."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_AUTHORLIST')) { define("CONTENT_ICON_AUTHORLIST", "<img src='".e_PLUGIN."content/images/personal.png' alt='".CONTENT_ICON_LAN_7."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_WARNING')) { define("CONTENT_ICON_WARNING", "<img src='".e_PLUGIN."content/images/warning_16.png' alt='".CONTENT_ICON_LAN_8."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_OK')) { define("CONTENT_ICON_OK", "<img src='".e_PLUGIN."content/images/ok_16.png' alt='".CONTENT_ICON_LAN_9."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_ERROR')) { define("CONTENT_ICON_ERROR", "<img src='".e_PLUGIN."content/images/error_16.png' alt='".CONTENT_ICON_LAN_10."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_ORDERCAT')) { define("CONTENT_ICON_ORDERCAT", "<img src='".e_PLUGIN."content/images/view_remove.png' alt='".CONTENT_ICON_LAN_11."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_ORDERALL')) { define("CONTENT_ICON_ORDERALL", "<img src='".e_PLUGIN."content/images/window_new.png' alt='".CONTENT_ICON_LAN_12."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_CONTENTMANAGER')) { define("CONTENT_ICON_CONTENTMANAGER", "<img src='".e_PLUGIN."content/images/manager_48.png' alt='".CONTENT_ICON_LAN_14."' style='border:0; cursor:pointer;' />"); }
if (!defined('CONTENT_ICON_CONTENTMANAGER_SMALL')) { define("CONTENT_ICON_CONTENTMANAGER_SMALL", "<img src='".e_PLUGIN."content/images/manager_16.png' alt='".CONTENT_ICON_LAN_13."' style='border:0; cursor:pointer;' />"); }

define("LAN_38", "votar");
define("LAN_39", "votos");
define("LAN_40", "¿Como valoraría este contenido?");
define("LAN_41", "Gracias por su voto");

define("LAN_65", "Sin valorar");

define("CONTENT_ADMIN_CAT_LAN_0", "Crear categoría de contenido");
define("CONTENT_ADMIN_CAT_LAN_1", "Editar categoría de contenido");
define("CONTENT_ADMIN_CAT_LAN_2", "Cabecera");
define("CONTENT_ADMIN_CAT_LAN_3", "Subcabecera");
define("CONTENT_ADMIN_CAT_LAN_4", "Texto");
define("CONTENT_ADMIN_CAT_LAN_5", "Icono");
define("CONTENT_ADMIN_CAT_LAN_6", "Enviar");
define("CONTENT_ADMIN_CAT_LAN_7", "Actualizar");
define("CONTENT_ADMIN_CAT_LAN_8", "Ver iconos");
define("CONTENT_ADMIN_CAT_LAN_9", "No hay categorías de contenidos existentes");
define("CONTENT_ADMIN_CAT_LAN_10", "Categorías de contenido");
define("CONTENT_ADMIN_CAT_LAN_11", "Categoría de contenido creada");
define("CONTENT_ADMIN_CAT_LAN_12", "Categoría de contenido actualizada");
define("CONTENT_ADMIN_CAT_LAN_13", "Hay campos obligatorios en blanco");
define("CONTENT_ADMIN_CAT_LAN_14", "Comentarios");
define("CONTENT_ADMIN_CAT_LAN_15", "Valorarión");
define("CONTENT_ADMIN_CAT_LAN_16", "Iconos de email e imprimir");
define("CONTENT_ADMIN_CAT_LAN_17", "Ver");
define("CONTENT_ADMIN_CAT_LAN_18", "autor");
define("CONTENT_ADMIN_CAT_LAN_19", "Categoría de contenido");
define("CONTENT_ADMIN_CAT_LAN_20", "opciones");
define("CONTENT_ADMIN_CAT_LAN_21", "limpiar formulario");
define("CONTENT_ADMIN_CAT_LAN_22", "Opciones actualizadas");
define("CONTENT_ADMIN_CAT_LAN_23", "Categoría de contenido eliminada");
define("CONTENT_ADMIN_CAT_LAN_24", "id");
define("CONTENT_ADMIN_CAT_LAN_25", "icono");
define("CONTENT_ADMIN_CAT_LAN_26", "Nueva categoría principal");
define("CONTENT_ADMIN_CAT_LAN_27", "Categoría");
define("CONTENT_ADMIN_CAT_LAN_28", "Asignar Admins a la configuación de la categoría");
define("CONTENT_ADMIN_CAT_LAN_29", "Admins - click para mover ... ");
define("CONTENT_ADMIN_CAT_LAN_30", "Personal Admins of this category ...");
define("CONTENT_ADMIN_CAT_LAN_31", "Eliminar");
define("CONTENT_ADMIN_CAT_LAN_32", "Limpiar clase");
define("CONTENT_ADMIN_CAT_LAN_33", "Asignar Admins a la categoría");
define("CONTENT_ADMIN_CAT_LAN_34", "Admins asignados a la categoría con éxito");
define("CONTENT_ADMIN_CAT_LAN_35", "eliminada la subcategoría del contenido");
define("CONTENT_ADMIN_CAT_LAN_36", "Comprobación categoría: existen subcategorías, la categoría no puede ser eliminada. Primero elimine las subcategorías y pruebe de nuevo.");
define("CONTENT_ADMIN_CAT_LAN_37", "Comprobación contenidos: existen contenidos, la categoría no puede ser eliminada. Primero elimine los contenidos y pruebe de nuevo.");
define("CONTENT_ADMIN_CAT_LAN_38", "Comprobación contenidos: no hay contenidos");
define("CONTENT_ADMIN_CAT_LAN_39", "Comprobación categoría: no hay categorías");
define("CONTENT_ADMIN_CAT_LAN_40", "Debajo verá una lista de las categorías principales y sus subcategorías, si esxisten.<br />");
define("CONTENT_ADMIN_CAT_LAN_41", "El manejador personal de las categorías de contenidos le permite asignar otros admins a una categoría. Con este privilegio, estos administradores su/s propio/s, y solo su/s propio/s contenido/s en su categoría correspondiente, sin la necesidad de tener control sobre el plugin de contenidos. Desde la página de contenidos fuera del área de administración, verá un icono que redirijirá a su página personal de configuración.");
define("CONTENT_ADMIN_CAT_LAN_41", "");
define("CONTENT_ADMIN_CAT_LAN_42", "para editar una categoría de la última categoría principal seleccionada");
define("CONTENT_ADMIN_CAT_LAN_43", "pulsar aquí");
define("CONTENT_ADMIN_CAT_LAN_44", "para añadir otra categoría en la última categoría principal seleccionada");
define("CONTENT_ADMIN_CAT_LAN_45", "Defina si se permitirá comentarios");
define("CONTENT_ADMIN_CAT_LAN_46", "Defina si se permitirá valorar");
define("CONTENT_ADMIN_CAT_LAN_47", "Defina si los iconos imprimir/email se mostrarán");
define("CONTENT_ADMIN_CAT_LAN_48", "Escoja que usuarios verán este elemento");
define("CONTENT_ADMIN_CAT_LAN_49", "Escoja un icono para esta categoría");
define("CONTENT_ADMIN_CAT_LAN_50", "Solo si ha creado una categoría Padre Principal, se creará un archivo menú<br />
  	 Esta archivo se ha creado en la carpeta /menus.<br />
  	 Para ver el menu en acción, necesita activar este menú en <a href='".e_ADMIN."menus.php'>area admin menú</a>.
  	 ");
define("CONTENT_ADMIN_CAT_LAN_51", "error; archivo menú no creado");
define("CONTENT_ADMIN_CAT_LAN_52", "Escoja SIEMPRE una categoría antes completar otros campos!");
define("CONTENT_ADMIN_CAT_LAN_53", "Categoría padre principal");

define("CONTENT_ADMIN_OPT_LAN_0", "Opciones");
define("CONTENT_ADMIN_OPT_LAN_1", "Opciones del Admin : creación de contenidos");
define("CONTENT_ADMIN_OPT_LAN_2", "Secciones");
define("CONTENT_ADMIN_OPT_LAN_3", "Escoja que secciones deben estar disponibles en la creación de contenidos desde el Admin");
define("CONTENT_ADMIN_OPT_LAN_4", "Icono");
define("CONTENT_ADMIN_OPT_LAN_5", "Adjuntos");
define("CONTENT_ADMIN_OPT_LAN_6", "Imágenes");
define("CONTENT_ADMIN_OPT_LAN_7", "Comentario");
define("CONTENT_ADMIN_OPT_LAN_8", "Valoración");
define("CONTENT_ADMIN_OPT_LAN_9", "Puntuación");
define("CONTENT_ADMIN_OPT_LAN_10", "Icono imprimir/email/pdf");
define("CONTENT_ADMIN_OPT_LAN_11", "Ver");
define("CONTENT_ADMIN_OPT_LAN_12", "Meta definición");
define("CONTENT_ADMIN_OPT_LAN_13", "Tags de datos personalizados");
define("CONTENT_ADMIN_OPT_LAN_14", "Defina un número adicional de tags de datos");
define("CONTENT_ADMIN_OPT_LAN_15", "Con tags personalizados puede crear una clave => par de valores de datos adicionales para almacenar en cada elemento. Por ejempl, si quiere añadir 'fotografía: por admin', fotografía es la clave, y por admin el valor.");
define("CONTENT_ADMIN_OPT_LAN_16", "Imágenes");
define("CONTENT_ADMIN_OPT_LAN_17", "Fije la cantidad de imágenes que puede transferir con un elemento");
define("CONTENT_ADMIN_OPT_LAN_18", "Solo se usará si las imágenes están activas en la sección de encima");
define("CONTENT_ADMIN_OPT_LAN_19", "Adjuntos");
define("CONTENT_ADMIN_OPT_LAN_20", "Fije la cantidad de adjuntos que puede transferir con un elemento");
define("CONTENT_ADMIN_OPT_LAN_21", "Solo se usará si los adjuntos están activos en la sección de encima");
define("CONTENT_ADMIN_OPT_LAN_22", "Envíos : opciones del formulario y página de envíos de contenidos");
define("CONTENT_ADMIN_OPT_LAN_23", "Enviados");
define("CONTENT_ADMIN_OPT_LAN_24", "Permitir enviar contenidos");
define("CONTENT_ADMIN_OPT_LAN_25", "Activado, permitirá a los invitados enviar contenidos a su sitio");
define("CONTENT_ADMIN_OPT_LAN_26", "Clases de envíos");
define("CONTENT_ADMIN_OPT_LAN_27", "Escoja que usuarios pueden enviar contenidos");
define("CONTENT_ADMIN_OPT_LAN_28", "Envíos directos");
define("CONTENT_ADMIN_OPT_LAN_29", "Permitir envios directos de contenidos");
define("CONTENT_ADMIN_OPT_LAN_30", "Activando los envíos directos, el envío se añadirá directamente en la base de datos y se mostrará inmediatamente. Desactivo, el admin deberá aprovar el contenido tras su revisión.");
define("CONTENT_ADMIN_OPT_LAN_31", "Defina que secciones estarán disponibles para nuevos envios");
define("CONTENT_ADMIN_OPT_LAN_32", "Escoja que áreas quiere permitir a los envíos de usuario");
define("CONTENT_ADMIN_OPT_LAN_33", "Rutas");
define("CONTENT_ADMIN_OPT_LAN_34", "Aquí definirá donde estarán sus imagenes o se almacenarán. Use paréntesis ( { } ) para las variables de ruta relacionadas con e107 (como ( {e_PLUGIN} o {e_IMAGE} ). Para los iconos de categorías de contenidos se necesitan 2 versiones, un set de iconos pequeños y otra grande.");
define("CONTENT_ADMIN_OPT_LAN_35", "ruta : iconos de categorías grandes");
define("CONTENT_ADMIN_OPT_LAN_36", "Defina una ruta para iconos de categoría (grandes)");
define("CONTENT_ADMIN_OPT_LAN_37", "ruta : iconos categoría pequeños");
define("CONTENT_ADMIN_OPT_LAN_38", "Defina una ruta para iconos de categoría (pequeños)");
define("CONTENT_ADMIN_OPT_LAN_39", "ruta : iconos");
define("CONTENT_ADMIN_OPT_LAN_40", "Defina una ruta para los iconos de contenidos");
define("CONTENT_ADMIN_OPT_LAN_41", "ruta : imágenes");
define("CONTENT_ADMIN_OPT_LAN_42", "Defina una ruta para las imágenes de contenidos");
define("CONTENT_ADMIN_OPT_LAN_43", "ruta : adjuntos");
define("CONTENT_ADMIN_OPT_LAN_44", "Defina la ruta para los adjuntos de contenidos");
define("CONTENT_ADMIN_OPT_LAN_45", "Tema");
define("CONTENT_ADMIN_OPT_LAN_46", "Defina un tema para esta categoría principal");
define("CONTENT_ADMIN_OPT_LAN_47", "Escoja un tema para esta categoría principal de contenido. Puede crear un nuevo tema añadiendo una carpeta en templates del plugin de contenidos.");
define("CONTENT_ADMIN_OPT_LAN_48", "General");
define("CONTENT_ADMIN_OPT_LAN_49", "Cuenta de referencia");
define("CONTENT_ADMIN_OPT_LAN_50", "Activar registro de cuenta de referencia");
define("CONTENT_ADMIN_OPT_LAN_51", "Activado, una cuenta de referencia se almacenará para contenido basado en una IP única.");
define("CONTENT_ADMIN_OPT_LAN_52", "Icono en blanco");
define("CONTENT_ADMIN_OPT_LAN_53", "Mostrar icono en blanco si no existe");
define("CONTENT_ADMIN_OPT_LAN_54", "Icono de cat en blanco");
define("CONTENT_ADMIN_OPT_LAN_55", "Mostrar icono de categoría en blanco si no existe");
define("CONTENT_ADMIN_OPT_LAN_56", "señuelo");
define("CONTENT_ADMIN_OPT_LAN_57", "Defina si se mostrará un señuelo");
define("CONTENT_ADMIN_OPT_LAN_58", "Separador del señuelo");
define("CONTENT_ADMIN_OPT_LAN_59", "Carácter separador del señuelo ( >> or > or - or ...)");
define("CONTENT_ADMIN_OPT_LAN_60", "Escoja un carácter a usar como separador entra cada nivel del señuelo");
define("CONTENT_ADMIN_OPT_LAN_61", "Renderizado del señuelo");
define("CONTENT_ADMIN_OPT_LAN_62", "Defina como mostrar el menú señuelo");
define("CONTENT_ADMIN_OPT_LAN_63", "Defina como renderizar la info de señuelo. Tiene 3 opciones: una enseñarla arriab de la página, otra en un menú separado y otra incorporarla en los menús a los que pertenezca.");
define("CONTENT_ADMIN_OPT_LAN_64", "Enseñar");
define("CONTENT_ADMIN_OPT_LAN_65", "Cada uno en menús diferentes");
define("CONTENT_ADMIN_OPT_LAN_66", "Combinar en un menú");
define("CONTENT_ADMIN_OPT_LAN_67", "Menú buscar");
define("CONTENT_ADMIN_OPT_LAN_68", "¿Debe mostrarse el menú búscar?");
define("CONTENT_ADMIN_OPT_LAN_69", "Activo, un menú de navegación y búsqueda se mostrará para buscar en contenidos o navegar en otras páginas así como una opción para ordenar contenidos en las páginas recientes");
define("CONTENT_ADMIN_OPT_LAN_70", "Páginas recientes (reciente (content.php?type.X), contenidos por categoría (content.php?type.X.cat.Y), contenidos por autor (content.php?type.X.author.Y))");
define("CONTENT_ADMIN_OPT_LAN_71", "Escoja que secciones se mostrarán cuando vea un contenido en las páginas recientes");
define("CONTENT_ADMIN_OPT_LAN_72", "Subcabecera");
define("CONTENT_ADMIN_OPT_LAN_73", "Sumario");
define("CONTENT_ADMIN_OPT_LAN_74", "Fecha");
define("CONTENT_ADMIN_OPT_LAN_75", "Autordetalles");
define("CONTENT_ADMIN_OPT_LAN_76", "Autoremail");
define("CONTENT_ADMIN_OPT_LAN_77", "Valoración");
define("CONTENT_ADMIN_OPT_LAN_78", "Icono email/imprimir/pdf");
define("CONTENT_ADMIN_OPT_LAN_79", "Señuelo padre");
define("CONTENT_ADMIN_OPT_LAN_80", "Refer (solo con el registro activo)");
define("CONTENT_ADMIN_OPT_LAN_81", "Carácteres de la subcabecera");
define("CONTENT_ADMIN_OPT_LAN_82", "Defina la cantidad de carácteres de la subcabecera");
define("CONTENT_ADMIN_OPT_LAN_83", "¿Cuantos carácteres de la subcabecera se mostrarán? En blanco se mostrará toda la subcabecera");
define("CONTENT_ADMIN_OPT_LAN_84", "Sufijo de subcabecera");
define("CONTENT_ADMIN_OPT_LAN_85", "Defina un sufijo para subcabeceras muy largas");
define("CONTENT_ADMIN_OPT_LAN_86", "Carácteres del sumario");
define("CONTENT_ADMIN_OPT_LAN_87", "Defina la cantidad de carácteres del sumario");
define("CONTENT_ADMIN_OPT_LAN_88", "¿Cuantos carácteres del sumario de mostrarán? En blanco para mostrar todo el sumario");
define("CONTENT_ADMIN_OPT_LAN_89", "Sufijo del sumario");
define("CONTENT_ADMIN_OPT_LAN_90", "Defina un sufijo para sumarios muy largos");
define("CONTENT_ADMIN_OPT_LAN_91", "Email no-miembro");
define("CONTENT_ADMIN_OPT_LAN_92", "Mostrar email de autores no-miembros");
define("CONTENT_ADMIN_OPT_LAN_93", "Defina si se mostrará el autor de un no-miembro. Solo  si el emailautor se fija en la sección de encima.");
define("CONTENT_ADMIN_OPT_LAN_94", "Previos");
define("CONTENT_ADMIN_OPT_LAN_95", "Mostrar botones previos");
define("CONTENT_ADMIN_OPT_LAN_96", "Activado, solo un límitado número de elementos se mostrarán en la lista de página, y Vd puede navegar a través de un número de páginas para mostrar otros contenidos.");
define("CONTENT_ADMIN_OPT_LAN_97", "Elementos por página");
define("CONTENT_ADMIN_OPT_LAN_98", "¿Cuantos contenidosse mostrarán en una página?");
define("CONTENT_ADMIN_OPT_LAN_99", "Solo se usarán si las limitaciones previas están chequeadas");
define("CONTENT_ADMIN_OPT_LAN_100", "Sobreescribir imprimir/email/pdf");
define("CONTENT_ADMIN_OPT_LAN_101", "Mostrar iconos imprimir/email/pdf para todos los elementos");
define("CONTENT_ADMIN_OPT_LAN_102", "Activado, muestra los iconos de todos los contenidos y padres, sin contar con su ajuste personal");
define("CONTENT_ADMIN_OPT_LAN_103", "sobreescribir sistema de valoración");
define("CONTENT_ADMIN_OPT_LAN_104", "Mostrar sistema de valoración para todos los elementos");
define("CONTENT_ADMIN_OPT_LAN_105", "Activado, muestra el sistema de valoración para todos los contenidos y padres, sin contar con su ajuste personal");
define("CONTENT_ADMIN_OPT_LAN_106", "Orden página");
define("CONTENT_ADMIN_OPT_LAN_107", "Escoja el método de ordenación");
define("CONTENT_ADMIN_OPT_LAN_108", "Ordenar por 'orden' usará el número de orden dado en Área de Gestión de Orden");
define("CONTENT_ADMIN_OPT_LAN_109", "Cabecera_ASC");
define("CONTENT_ADMIN_OPT_LAN_110", "Cabecera_DES");
define("CONTENT_ADMIN_OPT_LAN_111", "Fecha_ASC");
define("CONTENT_ADMIN_OPT_LAN_112", "Fecha_DES");
define("CONTENT_ADMIN_OPT_LAN_113", "Refer_ASC");
define("CONTENT_ADMIN_OPT_LAN_114", "Refer_DES");
define("CONTENT_ADMIN_OPT_LAN_115", "Padre_ASC");
define("CONTENT_ADMIN_OPT_LAN_116", "Padre_DES");
define("CONTENT_ADMIN_OPT_LAN_117", "Orden_ASC");
define("CONTENT_ADMIN_OPT_LAN_118", "Orden_DES");
define("CONTENT_ADMIN_OPT_LAN_119", "Página de catergoría de contenido (content.php?type.X.cat.Y)");
define("CONTENT_ADMIN_OPT_LAN_120", "Padre");
define("CONTENT_ADMIN_OPT_LAN_121", "¿Debe mostrar el padre?");
define("CONTENT_ADMIN_OPT_LAN_122", "Subcategorías padre");
define("CONTENT_ADMIN_OPT_LAN_123", "¿Deben mostrarse las subcategorías padre?");
define("CONTENT_ADMIN_OPT_LAN_124", "Activado, se mostrarán todas las subcategorías subrayadas con su categoría padre. Desactivado, se mostrará solo el padre");
define("CONTENT_ADMIN_OPT_LAN_125", "Subcategoría padre");
define("CONTENT_ADMIN_OPT_LAN_126", "¿Deben mostrarse los elementos de subcategorías padres?");
define("CONTENT_ADMIN_OPT_LAN_127", "Activado, se mostrarán los elementos de las categorías seleccionadas o subrayadas. Desactivado, se mostrarán solo los elementos de las categorías seleccionadas");
define("CONTENT_ADMIN_OPT_LAN_128", "Orden padres-hijos");
define("CONTENT_ADMIN_OPT_LAN_129", "Defina el orden de padres e hijos");
define("CONTENT_ADMIN_OPT_LAN_130", "Escoja el orden que se mostrarán padres e hijos");
define("CONTENT_ADMIN_OPT_LAN_131", "Padres por encima de hijos");
define("CONTENT_ADMIN_OPT_LAN_132", "Hijos por encima de padres");
define("CONTENT_ADMIN_OPT_LAN_133", "Tiporender de menús");
define("CONTENT_ADMIN_OPT_LAN_134", "Escoja método de renderizado para los menús");
define("CONTENT_ADMIN_OPT_LAN_135", "Puede renderizar el padre, sub e hijos de cada menú, o puede combinarlos juntos en un solo menú");
define("CONTENT_ADMIN_OPT_LAN_136", "Cada uno en menús separados");
define("CONTENT_ADMIN_OPT_LAN_137", "Combinar en un menú");
define("CONTENT_ADMIN_OPT_LAN_138", "Página de contenido (content.php?type.X.content.Y)");
define("CONTENT_ADMIN_OPT_LAN_139", "Escoja que secciones se mostrarán cuando vea un contenido");
define("CONTENT_ADMIN_OPT_LAN_140", "Propiedades de menú");
define("CONTENT_ADMIN_OPT_LAN_141", "Título");
define("CONTENT_ADMIN_OPT_LAN_142", "Defina un título al menú");
define("CONTENT_ADMIN_OPT_LAN_143", "Buscar");
define("CONTENT_ADMIN_OPT_LAN_144", "¿Necesita mostrar el menú de búsqueda?");
define("CONTENT_ADMIN_OPT_LAN_145", "Ordenación");
define("CONTENT_ADMIN_OPT_LAN_146", "¿Necesita mostrar una selección para opciones de ordenación?");
define("CONTENT_ADMIN_OPT_LAN_147", "Enlaces a páginas");
define("CONTENT_ADMIN_OPT_LAN_148", "Enlace : todas las categorías");
define("CONTENT_ADMIN_OPT_LAN_149", "¿Necesita mostrar el enlace 'todos las categorías' en la página?");
define("CONTENT_ADMIN_OPT_LAN_150", "Enlace: todos los autores");
define("CONTENT_ADMIN_OPT_LAN_151", "¿Necesita mostrar el enlace 'todos los autores' en la página?");
define("CONTENT_ADMIN_OPT_LAN_152", "Enlace : los mas valorados");
define("CONTENT_ADMIN_OPT_LAN_153", "¿Necesita mostrar el enlace 'los más valorados' en la página?");
define("CONTENT_ADMIN_OPT_LAN_154", "Enlace : elementos recientes");
define("CONTENT_ADMIN_OPT_LAN_155", "¿Necesita mostrar el enlace 'contenidos recientes' en la página?");
define("CONTENT_ADMIN_OPT_LAN_156", "Enlace : enviar elemento");
define("CONTENT_ADMIN_OPT_LAN_157", "¿necesita mostrar el enlace 'enviar contenido' en la página?");
define("CONTENT_ADMIN_OPT_LAN_158", "Icono : enlaces");
define("CONTENT_ADMIN_OPT_LAN_159", "Defina el icono a mostrar");
define("CONTENT_ADMIN_OPT_LAN_160", "none (), bullet (), middot (&middot;), white bullet (º), arrow (&raquo;)");
define("CONTENT_ADMIN_OPT_LAN_161", "Categorías");
define("CONTENT_ADMIN_OPT_LAN_162", "Subcategorías");
define("CONTENT_ADMIN_OPT_LAN_163", "¿Necesita mostrar las (sub) categorías si existen?");
define("CONTENT_ADMIN_OPT_LAN_164", "Cantidad de elementos");
define("CONTENT_ADMIN_OPT_LAN_165", "¿Necesita mostrar el nº total de elementos de cada categoría?");
define("CONTENT_ADMIN_OPT_LAN_166", "Icono : categoría");
define("CONTENT_ADMIN_OPT_LAN_167", "none (), bullet (), middot (&middot;), white bullet (º), arrow (&raquo;), category_icon()");
define("CONTENT_ADMIN_OPT_LAN_168", "Nada");
define("CONTENT_ADMIN_OPT_LAN_169", "Bullet");
define("CONTENT_ADMIN_OPT_LAN_170", "Middot");
define("CONTENT_ADMIN_OPT_LAN_171", "White bullet");
define("CONTENT_ADMIN_OPT_LAN_172", "Flecha");
define("CONTENT_ADMIN_OPT_LAN_173", "Icono de categoría");
define("CONTENT_ADMIN_OPT_LAN_174", "Lista reciente");
define("CONTENT_ADMIN_OPT_LAN_175", "Elementos recientes");
define("CONTENT_ADMIN_OPT_LAN_176", "¿Necesita mostrar una lista de contenidos recientes?");
define("CONTENT_ADMIN_OPT_LAN_177", "Título : lista areciente");
define("CONTENT_ADMIN_OPT_LAN_178", "Defina el título de la lista reciente");
define("CONTENT_ADMIN_OPT_LAN_179", "Cantidad de elementos recientes");
define("CONTENT_ADMIN_OPT_LAN_180", "¿Cuantos elementos recientes deben mostrarse?");
define("CONTENT_ADMIN_OPT_LAN_181", "Fecha");
define("CONTENT_ADMIN_OPT_LAN_182", "¿Necesita mostrar la fecha?");
define("CONTENT_ADMIN_OPT_LAN_183", "Autor");
define("CONTENT_ADMIN_OPT_LAN_184", "¿Necesitar mostrar el autor?");
define("CONTENT_ADMIN_OPT_LAN_185", "Subcabecera");
define("CONTENT_ADMIN_OPT_LAN_186", "¿Necesita mostrar la subcabecera?");
define("CONTENT_ADMIN_OPT_LAN_187", "Subcabecera : caracteres");
define("CONTENT_ADMIN_OPT_LAN_188", "¿Cuantos carácteres se mostrarán en la subcabecera?");
define("CONTENT_ADMIN_OPT_LAN_189", "Deje en blanco para mostrar la subcabecera completa");
define("CONTENT_ADMIN_OPT_LAN_190", "subcabecera : sufijo");
define("CONTENT_ADMIN_OPT_LAN_191", "Defina un sifujo para subcabeceras largas");
define("CONTENT_ADMIN_OPT_LAN_192", "Icono : recientes");
define("CONTENT_ADMIN_OPT_LAN_193", "none (), bullet (), middot (·), white bullet (º), arrow (»), content_icon()");
define("CONTENT_ADMIN_OPT_LAN_194", "Icono de contenido");
define("CONTENT_ADMIN_OPT_LAN_195", "Icono : ancho");
define("CONTENT_ADMIN_OPT_LAN_196", "Defina el ancho del icono");
define("CONTENT_ADMIN_OPT_LAN_197", "Si ha escogido mostrar 'icono de contenido', especifique el ancho del icono de contenido a mostrar. Solo valores numéricos de la cantidad de pixels que quiera. No añada 'px' al número.");
define("CONTENT_ADMIN_OPT_LAN_198", "");
define("CONTENT_ADMIN_OPT_LAN_199", "");
define("CONTENT_ADMIN_OPT_LAN_200", "Actualizar opciones");
define("CONTENT_ADMIN_OPT_LAN_201", "Sobreescribir el sistema de comentarios");
define("CONTENT_ADMIN_OPT_LAN_202", "Permitir comentarios en todos los elementos");
define("CONTENT_ADMIN_OPT_LAN_203", "Activado, permite enviar comentarios en todos los contenidos, sin contar con su configuración individual");
define("CONTENT_ADMIN_OPT_LAN_204", "Editar icono : mostrar un icono con un enlace al admin para editar el contenido");
define("CONTENT_ADMIN_OPT_LAN_205", "Plantillas");
define("CONTENT_ADMIN_OPT_LAN_206", "Datos personalizados");
define("CONTENT_ADMIN_OPT_LAN_207", "Categorías de renderizado");
define("CONTENT_ADMIN_OPT_LAN_208", "Defina como necesita mostrar las categorías");
define("CONTENT_ADMIN_OPT_LAN_209", "Puede unir las categorías con otros enlaces en la caja de selección, o mostrarlo como enlaces normales");
define("CONTENT_ADMIN_OPT_LAN_210", "Cajaselección");
define("CONTENT_ADMIN_OPT_LAN_211", "Enlacesnormales");
define("CONTENT_ADMIN_OPT_LAN_212", "Enlace : todos los contenidos");
define("CONTENT_ADMIN_OPT_LAN_213", "Necesita la página del enlace 'todos los contenidos' (la página archivo) mostrarse?");
define("CONTENT_ADMIN_OPT_LAN_214", "Estilo de fecha");
define("CONTENT_ADMIN_OPT_LAN_215", "Escoja un estilo de fecha para mostrarla");
define("CONTENT_ADMIN_OPT_LAN_216", "Para más información sobre formatos de fecha mire en <a href='http://www.php.net/manual/en/function.strftime.php' rel='external'>función strftime en php.net</a>");
define("CONTENT_ADMIN_OPT_LAN_217", "");
define("CONTENT_ADMIN_OPT_LAN_218", "");
define("CONTENT_ADMIN_OPT_LAN_219", "");

define("CONTENT_ADMIN_ITEM_LAN_0", "campos obligatorios en blanco");
define("CONTENT_ADMIN_ITEM_LAN_1", "contenido creado");
define("CONTENT_ADMIN_ITEM_LAN_2", "contenido actualizado");
define("CONTENT_ADMIN_ITEM_LAN_3", "contenido eliminado");
define("CONTENT_ADMIN_ITEM_LAN_4", "sin contenidos");
define("CONTENT_ADMIN_ITEM_LAN_5", "contenidos existentes");
define("CONTENT_ADMIN_ITEM_LAN_6", "primeras letras");
define("CONTENT_ADMIN_ITEM_LAN_7", "Seleccione una letra de arriba.");
define("CONTENT_ADMIN_ITEM_LAN_8", "id");
define("CONTENT_ADMIN_ITEM_LAN_9", "icono");
define("CONTENT_ADMIN_ITEM_LAN_10", "autor");
define("CONTENT_ADMIN_ITEM_LAN_11", "cabecera");
define("CONTENT_ADMIN_ITEM_LAN_12", "opciones");
define("CONTENT_ADMIN_ITEM_LAN_13", "escoja categoría padre");
define("CONTENT_ADMIN_ITEM_LAN_14", "nombre autor");
define("CONTENT_ADMIN_ITEM_LAN_15", "dirección email autor");
define("CONTENT_ADMIN_ITEM_LAN_16", "subcabecera");
define("CONTENT_ADMIN_ITEM_LAN_17", "sumario");
define("CONTENT_ADMIN_ITEM_LAN_18", "texto");
define("CONTENT_ADMIN_ITEM_LAN_19", "transferir icono");
define("CONTENT_ADMIN_ITEM_LAN_20", "Icono del contenido");
define("CONTENT_ADMIN_ITEM_LAN_21", "esta opción estará desactivada si las subidas estan desactivadas en el servidor");
define("CONTENT_ADMIN_ITEM_LAN_22", "La carpeta");
define("CONTENT_ADMIN_ITEM_LAN_23", "no es escribible, necesita CHMOD 777 a la carpeta antes de transferir");
define("CONTENT_ADMIN_ITEM_LAN_24", "Transferir adjuntos");
define("CONTENT_ADMIN_ITEM_LAN_25", "Transferir nuevo icono");
define("CONTENT_ADMIN_ITEM_LAN_26", "Eliminar");
define("CONTENT_ADMIN_ITEM_LAN_27", "Archivos del contenido");
define("CONTENT_ADMIN_ITEM_LAN_28", "Transferir nuevo archivo");
define("CONTENT_ADMIN_ITEM_LAN_29", "no hay archivos");
define("CONTENT_ADMIN_ITEM_LAN_30", "Archivo de contenido");
define("CONTENT_ADMIN_ITEM_LAN_31", "Transferir imágenes");
define("CONTENT_ADMIN_ITEM_LAN_32", "Imagenes del contenido");
define("CONTENT_ADMIN_ITEM_LAN_33", "Suir nueva imagen");
define("CONTENT_ADMIN_ITEM_LAN_34", "Imagen del contenido");
define("CONTENT_ADMIN_ITEM_LAN_35", "Fijar ajustes para este contenido");
define("CONTENT_ADMIN_ITEM_LAN_36", "Permitir comentarios");
define("CONTENT_ADMIN_ITEM_LAN_37", "Permitir valorar");
define("CONTENT_ADMIN_ITEM_LAN_38", "Mostrar iconos imprimir/email");
define("CONTENT_ADMIN_ITEM_LAN_39", "ver");
define("CONTENT_ADMIN_ITEM_LAN_40", "Puntuación");
define("CONTENT_ADMIN_ITEM_LAN_41", "Seleccionar puntuación ...");
define("CONTENT_ADMIN_ITEM_LAN_42", "Active para ajustar la hora/fecha a la actual");
define("CONTENT_ADMIN_ITEM_LAN_43", "contenidos enviados por usuarios");
define("CONTENT_ADMIN_ITEM_LAN_44", "crear contenido");
define("CONTENT_ADMIN_ITEM_LAN_45", "actualizar contenido");
define("CONTENT_ADMIN_ITEM_LAN_46", "Previsualizar");
define("CONTENT_ADMIN_ITEM_LAN_47", "Previsualizar de nuevo");
define("CONTENT_ADMIN_ITEM_LAN_48", "padre principal");
define("CONTENT_ADMIN_ITEM_LAN_49", "contenidos enviados");
define("CONTENT_ADMIN_ITEM_LAN_50", "no hay contenidos enviados");
define("CONTENT_ADMIN_ITEM_LAN_51", "detalles autor");
define("CONTENT_ADMIN_ITEM_LAN_52", "Enviar contenido");
define("CONTENT_ADMIN_ITEM_LAN_53", "meta keywords");
define("CONTENT_ADMIN_ITEM_LAN_54", "Datos adicionales");
define("CONTENT_ADMIN_ITEM_LAN_55", "Vuelva a  <a href='".e_SELF."'>página conf de contenidos</a> para configurar mejor su contenido personal<br />or<br />vaya a <a href='".e_PLUGIN."content/content.php'>página principal de contenidos</a> para ver los contenidos.");
define("CONTENT_ADMIN_ITEM_LAN_56", "Conf personal de contenidos");
define("CONTENT_ADMIN_ITEM_LAN_57", "categoría");
define("CONTENT_ADMIN_ITEM_LAN_58", "contenidos");
define("CONTENT_ADMIN_ITEM_LAN_59", "mover");
define("CONTENT_ADMIN_ITEM_LAN_60", "order");
define("CONTENT_ADMIN_ITEM_LAN_61", "actualizar orden");
define("CONTENT_ADMIN_ITEM_LAN_62", "oprdenar categorías");
define("CONTENT_ADMIN_ITEM_LAN_63", "inc");
define("CONTENT_ADMIN_ITEM_LAN_64", "dec");
define("CONTENT_ADMIN_ITEM_LAN_65", "ordenar contenidos");
define("CONTENT_ADMIN_ITEM_LAN_66", "Debajo verá idstintas letras de las cabeceras de los contenidos en esta categoría.<br />Haciendo click en una de las letras verá una lista de los contenidos que empiezans por esa letra. También puede escoger TODOS los botones para mostrar los contenidos de esta categoría.");
define("CONTENT_ADMIN_ITEM_LAN_67", "Debajo verá los contenidos listados porla categoría seleccionada o encogido con la letra seleccionada.<br />Puede eliminar o editar un elemento haciendo click en el botón apropiado de la derecha.");
define("CONTENT_ADMIN_ITEM_LAN_68", "Debajo tendrá la disponibilidad de añadir datos personalizados para el elemento. Cada dato personalizado necesita tener su clave y valor. Puede especificar la clave en el campo izquierdo y su valor en el derecho.<br />(por ejemplo, clave='fotografía' y valor='todas las fotos están hechas por mi'.");
define("CONTENT_ADMIN_ITEM_LAN_69", "Aquí puede transferir iconos, adjuntos y/o imágenes con el contenido. Los tipos permitidos son : ");
define("CONTENT_ADMIN_ITEM_LAN_70", "En la caja siguiente podrá especificar meta keywords para ir al contenido. Estos meta keywords se muestran en la cabecera de la página. Separe cada palabra con comas, LOS ESPACIOS NO ESTÁN PERMITIDOS !");
define("CONTENT_ADMIN_ITEM_LAN_71", "dejarlo si lo ha escrito usted");
define("CONTENT_ADMIN_ITEM_LAN_72", "Defina detalles de autor");
define("CONTENT_ADMIN_ITEM_LAN_73", "Defina una fecha de inicio (déjelo si no necesita)");
define("CONTENT_ADMIN_ITEM_LAN_74", "Defina una fecha fín (déjelo si no necesita)");
define("CONTENT_ADMIN_ITEM_LAN_75", "Transfiera y asígnale un icono");
define("CONTENT_ADMIN_ITEM_LAN_76", "Transfiera y asígnale un archivo adjunto");
define("CONTENT_ADMIN_ITEM_LAN_77", "Transfiera y asígnale imágenes");
define("CONTENT_ADMIN_ITEM_LAN_78", "Defina si permitirá comentarios");
define("CONTENT_ADMIN_ITEM_LAN_79", "Defina si permitirá valorar");
define("CONTENT_ADMIN_ITEM_LAN_80", "Defina si se mostrarán los iconos de email e imprimir");
define("CONTENT_ADMIN_ITEM_LAN_81", "Escoja que usuarios lo podrán ver");
define("CONTENT_ADMIN_ITEM_LAN_82", "Defina una puntuación");
define("CONTENT_ADMIN_ITEM_LAN_83", "Defina meta keywords");
define("CONTENT_ADMIN_ITEM_LAN_84", "Defina campos de datos personalizados (clave + valor)");
define("CONTENT_ADMIN_ITEM_LAN_85", "Activado");
define("CONTENT_ADMIN_ITEM_LAN_86", "Desactivado");
define("CONTENT_ADMIN_ITEM_LAN_87", "Escoja un icono para esta categoría");
define("CONTENT_ADMIN_ITEM_LAN_88", "para crear un elemento en la última categoría principal seleccionada");
define("CONTENT_ADMIN_ITEM_LAN_89", "para editar un elemento en la última categoría principal seleccionada");
define("CONTENT_ADMIN_ITEM_LAN_90", "click aquí");
define("CONTENT_ADMIN_ITEM_LAN_91", "para re-editar el mismo elemento");
define("CONTENT_ADMIN_ITEM_LAN_92", "Plantilla");
define("CONTENT_ADMIN_ITEM_LAN_93", "Escoja una plantilla");
define("CONTENT_ADMIN_ITEM_LAN_94", "Seleccione una plantilla");
define("CONTENT_ADMIN_ITEM_LAN_95", "");

define("CONTENT_ADMIN_ORDER_LAN_0", "orden incrementado");
define("CONTENT_ADMIN_ORDER_LAN_1", "orden decrementado");
define("CONTENT_ADMIN_ORDER_LAN_2", "guardado el orden de los contenidos");

define("CONTENT_ADMIN_MAIN_LAN_0", "Categorías de contenidos existentes");
define("CONTENT_ADMIN_MAIN_LAN_1", "No hay categorías de contenidos");
define("CONTENT_ADMIN_MAIN_LAN_2", "Principales categorías de contenidos");
define("CONTENT_ADMIN_MAIN_LAN_3", "Contenido eliminado");
define("CONTENT_ADMIN_MAIN_LAN_4", "Texto padre");
define("CONTENT_ADMIN_MAIN_LAN_5", "Icono padre");
define("CONTENT_ADMIN_MAIN_LAN_6", "");
define("CONTENT_ADMIN_MAIN_LAN_7", "WBienvenido al CMS !");
define("CONTENT_ADMIN_MAIN_LAN_8", "Lea con atención la siguiente información y escoja lo que quiera hacer");
define("CONTENT_ADMIN_MAIN_LAN_9", "Esta información se muestra porque la tabla del plugin del CMS no contiene registros.");
define("CONTENT_ADMIN_MAIN_LAN_10", "Puede organizar los elementos en esta página. Primero decida la categoría de contenido a configurar. Seleccione una categoría en la caja de selección para empezar a manejar contenidos para esa categoría.");
define("CONTENT_ADMIN_MAIN_LAN_11", "
<b>La vieja tabla de contenidos contiene registros</b><br />
Debido a esta situación puede hacer dos cosas:<br />
<br />
<b>a) convertir registros</b><br />
 Lo primero que necesita hacer es crear una copia de seguridad de las tablas de contenido existentes así como los comenatrios y la tabla de valoraciones.<br />
  	 Use un programa para realizar una copia de sus tablas de contenido como phpmyadmin.<br />
  	 Después de crear la copia de su vieja tabla de contenido, puede empezar convirtiendo los registros al nuevo plugin de Manejador de Contenidos.<br />
  	 Después de convertir su viejo contenido, ya no debería ver esta información, y poder manejar su contenido existente.<br />
  	 Por favor, vaya a la página <a href='".e_PLUGIN."content/admin_content_convert.php'>Script de conversión de contenidos</a><br />
  	 <br />
  	 <b>b) no converir los registros y comenzar un nuevo contenido</b><br />
  	 Si no va a necesitar los registros de su viejo contenido,<br />
  	 y simplemente quiere empezar con una nueva tabla de Manejador de Contenidos,<br />
  	 puede empezar creando una nueva categoría.<br />
  	 Por favor, vaya a la página <a href='".e_SELF."?type.0.cat.create'>Crear nueva categoría</a>.<br />
  	 ");
define("CONTENT_ADMIN_MAIN_LAN_12", "
  	 <b>Esta es una instalación limpia / La tabla de viejo contenido no contiene registros</b><br />
  	 Con esta situación puede comenzar a crear nuevos contenidos.<br />
  	 Lo primero que necesita es crear una nueva categoría.<br />
  	 Por favor, vaya a la página <a href='".e_SELF."?type.0.cat.create'>Crear nueva categoría</a>.<br />
  	 ");
define("CONTENT_ADMIN_MAIN_LAN_13", "Puede crear nuevos contenidos en esta página. Primero decida la categoría a la que quiere manejar contenido. Click en el botón de los padres principales listado debajo para crear nuevo contenido en la categoría principal.");
define("CONTENT_ADMIN_MAIN_LAN_14", "Puede fijar el orden de los contenidos en esta página. Click en el botón de los padres principales mostrados debajo para comenzar a ordenar contenidos de la categoría principal seleccionada.");
define("CONTENT_ADMIN_MAIN_LAN_15", "Puede manejar categorías en esta página. Escoja la categoría principal en los botones listados debajo para mostrar una vista de todas la categorías y subcategorías de esta categoría principal.");
define("CONTENT_ADMIN_MAIN_LAN_16", "Puede crear nuevas categorías en esta página. Por defecto, el formulario de creación para una nueva categoría principal se mostrará. Si quiere crear una subcategoría para una categoría principal existente, haga click en uno de los botones listados debajo para mostrar el formulario de creación de una subcategoría de la categoría principal seleccionada.");
define("CONTENT_ADMIN_MAIN_LAN_18", "Convertir registros");
define("CONTENT_ADMIN_MAIN_LAN_19", "
Lo primero que debe hacer es una copia de su tabla de contenidos existentes así como su tabla de valoraciones y comentarios.<br />
Use un programa para su copia, como phpmyadmin.<br />
Después de ésto, puede comenzar con la conversión de registros al nuevo plugin de gestor de contenidos.<br />
Después de convertir su contenido, ya no debería ver esta información, y debería poder gestionar su contenido existente.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_20", "Comenzar con una tabla vacía de contenido");
define("CONTENT_ADMIN_MAIN_LAN_21", "
Si no necesitara sus viejos registros de tablas de contenidos,<br />
y deseara comenzar con una nueva tabla limpia de egstor de contenidos,<br />
sin querer crear un ajuste por defecto de categorías,<br />
puede comenzar creando una nueva categoría.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_22", "Crear un ajuste por defecto de categorías");
define("CONTENT_ADMIN_MAIN_LAN_23", "
Si quiere comenzar con una instalación limpia, primero debe crear un ajuste por defecto de categorías de contenidos.<br />
Con este ajuste, 3 categorías padres principales se crearán, Contenido, Análisis y Artículo.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_24", "Es una instalación limpia / La vieja tabla de contenidos no contiene registros");
define("CONTENT_ADMIN_MAIN_LAN_25", "
Como no contiene registros, puede comenzar a crear contenidos.<br />
Pulsando en el botón siguiente, automáticamente creará un ajuste por defecto de categorías, Contenido, Análisi y Artículos.<br />
");
define("CONTENT_ADMIN_MAIN_LAN_26", "");
define("CONTENT_ADMIN_MAIN_LAN_27", "");
define("CONTENT_ADMIN_MAIN_LAN_28", "");
define("CONTENT_ADMIN_MAIN_LAN_29", "");

define("CONTENT_ADMIN_MENU_LAN_0", "Configurar contenidos");
define("CONTENT_ADMIN_MENU_LAN_1", "Crear nuevo contenido");
define("CONTENT_ADMIN_MENU_LAN_2", "Configurar categorías");
define("CONTENT_ADMIN_MENU_LAN_3", "Crear nueva categoría");
define("CONTENT_ADMIN_MENU_LAN_4", "Contenidos enviados");
define("CONTENT_ADMIN_MENU_LAN_5", "Categoría");
define("CONTENT_ADMIN_MENU_LAN_6", "Opciones");
define("CONTENT_ADMIN_MENU_LAN_7", "Crear");
define("CONTENT_ADMIN_MENU_LAN_8", "Enviar");
define("CONTENT_ADMIN_MENU_LAN_9", "Ruta y tema");
define("CONTENT_ADMIN_MENU_LAN_10", "General");
define("CONTENT_ADMIN_MENU_LAN_11", "Páginas recientes");
define("CONTENT_ADMIN_MENU_LAN_12", "Páginas de categorías");
define("CONTENT_ADMIN_MENU_LAN_13", "Páginas de contenidos");
define("CONTENT_ADMIN_MENU_LAN_14", "Menú");
define("CONTENT_ADMIN_MENU_LAN_15", "Configurar orden");
define("CONTENT_ADMIN_MENU_LAN_16", "Página archivo");

define("CONTENT_ADMIN_JS_LAN_0", "¿Está seguro de eliminar esta categoría?");
define("CONTENT_ADMIN_JS_LAN_1", "¿Está seguro de eliminar este contenido?");
define("CONTENT_ADMIN_JS_LAN_2", "¿Está seguro de eliminar la imagen actual?");
define("CONTENT_ADMIN_JS_LAN_3", "¿Está seguro de eliminar el archivo actual?");
define("CONTENT_ADMIN_JS_LAN_4", "imagen");
define("CONTENT_ADMIN_JS_LAN_5", "Archivo");
define("CONTENT_ADMIN_JS_LAN_6", "ID");
define("CONTENT_ADMIN_JS_LAN_7", "¿Está seguro de eliminar el icono actual ?");
define("CONTENT_ADMIN_JS_LAN_8", "icono");
define("CONTENT_ADMIN_JS_LAN_9", "ATENCIÓN :\\nsolo las categorías vacías se podrán eliminar.\\n La categoría esta vacía cuando no contiene subcategorías y\\n no contiene contenidos en éstas!");
define("CONTENT_ADMIN_JS_LAN_10", "¿Está seguro de eliminar el contenido enviado antes de enviarlo?");

define("CONTENT_ADMIN_DATE_LAN_0", "Enero");
define("CONTENT_ADMIN_DATE_LAN_1", "Febrero");
define("CONTENT_ADMIN_DATE_LAN_2", "Marzo");
define("CONTENT_ADMIN_DATE_LAN_3", "Abril");
define("CONTENT_ADMIN_DATE_LAN_4", "Mayo");
define("CONTENT_ADMIN_DATE_LAN_5", "Junio");
define("CONTENT_ADMIN_DATE_LAN_6", "Julio");
define("CONTENT_ADMIN_DATE_LAN_7", "Agosto");
define("CONTENT_ADMIN_DATE_LAN_8", "Septiembre");
define("CONTENT_ADMIN_DATE_LAN_9", "Octubre");
define("CONTENT_ADMIN_DATE_LAN_10", "Noviembre");
define("CONTENT_ADMIN_DATE_LAN_11", "Diciembre");
define("CONTENT_ADMIN_DATE_LAN_12", "Día");
define("CONTENT_ADMIN_DATE_LAN_13", "Mes");
define("CONTENT_ADMIN_DATE_LAN_14", "Año");
define("CONTENT_ADMIN_DATE_LAN_15", "Fecha inicio");
define("CONTENT_ADMIN_DATE_LAN_16", "Fecha fin");
define("CONTENT_ADMIN_DATE_LAN_17", "Puede especificar una fecha de inicio para esta categoría. Si usa una fecha en el futuro, el contenido se mostrará desde ese punto. Si no necesita una fecha inicial, lo puede dejar en blanco.");
define("CONTENT_ADMIN_DATE_LAN_18", "Puede especificar una fecha fín para el contenido. El contenido dejará de mostrarse a partir de la fecha indicada aquí. Si no desea finalizar el contenido, dejelo como está.");

define("CONTENT_LAN_0", "Contenido");
define("CONTENT_LAN_1", "Lista recientes");
define("CONTENT_LAN_2", "Lista categoría");
define("CONTENT_LAN_3", "categoría");
define("CONTENT_LAN_4", "Lista autor");
define("CONTENT_LAN_5", "Autor");
define("CONTENT_LAN_6", "Todas las categorías");
define("CONTENT_LAN_7", "Todos los autores");
define("CONTENT_LAN_8", "los más valorados");
define("CONTENT_LAN_9", "ordenar por ...");
define("CONTENT_LAN_10", "cabecera_asc");
define("CONTENT_LAN_11", "cabecera_desc");
define("CONTENT_LAN_12", "fecha_asc");
define("CONTENT_LAN_13", "fecha_desc");
define("CONTENT_LAN_14", "refer_asc");
define("CONTENT_LAN_15", "refer_desc");
define("CONTENT_LAN_16", "padre_asc");
define("CONTENT_LAN_17", "padre_desc");
define("CONTENT_LAN_18", "buscar por clave");
define("CONTENT_LAN_19", "buscar");
define("CONTENT_LAN_20", "resultados de la búsqueda de contenidos");
define("CONTENT_LAN_21", "sin tipos de contenidos.");
define("CONTENT_LAN_22", "tipos de contenidos");
define("CONTENT_LAN_23", "lista contenidos recientes");
define("CONTENT_LAN_24", "señuelo");
define("CONTENT_LAN_25", "catgorías de contenido");
define("CONTENT_LAN_26", "padre");
define("CONTENT_LAN_27", "subcategorías");
define("CONTENT_LAN_28", "padre subcategorías");
define("CONTENT_LAN_29", "desconocido");
define("CONTENT_LAN_30", "contenido");
define("CONTENT_LAN_31", "contenidos");
define("CONTENT_LAN_32", "lista autor contenido");
define("CONTENT_LAN_33", "Ir a la página");
define("CONTENT_LAN_34", "contenido");
define("CONTENT_LAN_35", "comentarios");
define("CONTENT_LAN_36", "moderar comentarios");
define("CONTENT_LAN_37", "no hay contenidos valorados aún");
define("CONTENT_LAN_38", "loc ontenidos más valorados");
define("CONTENT_LAN_39", "lista autor");
define("CONTENT_LAN_40", "detalles del autor");
define("CONTENT_LAN_41", "descargar adjunto");
define("CONTENT_LAN_42", "archivo");
define("CONTENT_LAN_43", "archivos");
define("CONTENT_LAN_44", "clicks:");
define("CONTENT_LAN_45", "puntuación obtenida del autor:");
define("CONTENT_LAN_46", "índice articulos");
define("CONTENT_LAN_47", "autor");
define("CONTENT_LAN_48", "contenidos");
define("CONTENT_LAN_49", "último contenido");
define("CONTENT_LAN_50", "fecha");
define("CONTENT_LAN_51", "lista tipos");
define("CONTENT_LAN_52", "no se encontraron autores válidos");
define("CONTENT_LAN_53", "artículo");
define("CONTENT_LAN_54", "artículos");
define("CONTENT_LAN_55", "último artículo de");
define("CONTENT_LAN_56", "Mostrar por");
define("CONTENT_LAN_57", "comentarios:");
define("CONTENT_LAN_58", "Inicio");
define("CONTENT_LAN_59", "contenido");
define("CONTENT_LAN_60", "reciente");
define("CONTENT_LAN_61", "ver artículos recientes");
define("CONTENT_LAN_62", "ver todas las categorías");
define("CONTENT_LAN_63", "ver todos loa autores");
define("CONTENT_LAN_64", "ver los más valorados");
define("CONTENT_LAN_65", "enviar contenido");
define("CONTENT_LAN_66", "Haga click para enviar un contenido, puede escoger la categoría en la página de envios.");
define("CONTENT_LAN_67", "Conf personal de contenidos");
define("CONTENT_LAN_68", "Haga click para configurar sus contenidos.");
define("CONTENT_LAN_69", "email");
define("CONTENT_LAN_70", "imprimir");
define("CONTENT_LAN_71", "contenido");
define("CONTENT_LAN_72", "categoría");
define("CONTENT_LAN_73", "orden_asc");
define("CONTENT_LAN_74", "orden_desc");
define("CONTENT_LAN_75", "enviar contenido");
define("CONTENT_LAN_76", "crear archivo pdf de");
define("CONTENT_LAN_77", "buscar contenido");
define("CONTENT_LAN_78", "página sin título");
define("CONTENT_LAN_79", "página");
define("CONTENT_LAN_80", "elementos recientes : ");
define("CONTENT_LAN_81", "categorías");
define("CONTENT_LAN_82", "sin elementos todavía");
define("CONTENT_LAN_83", "Elemento archivo");
define("CONTENT_LAN_84", "Contenido archivo");
define("CONTENT_LAN_85", "");
define("CONTENT_LAN_86", "");
define("CONTENT_LAN_87", "");
define("CONTENT_LAN_88", "");
define("CONTENT_LAN_89", "");

define("CONTENT_ADMIN_SUBMIT_LAN_0", "En este punto no se permiten sumisiones de categorías de contenido");
define("CONTENT_ADMIN_SUBMIT_LAN_1", "tipos de contenidos enviados");
define("CONTENT_ADMIN_SUBMIT_LAN_2", "Gracias, su contenido ha sido enviado.");
define("CONTENT_ADMIN_SUBMIT_LAN_3", "Gracias, su contenido ha sido enviado y será revisado por un administrador para ser aceptado.");
define("CONTENT_ADMIN_SUBMIT_LAN_4", "campos obligatorios en blanco");
define("CONTENT_ADMIN_SUBMIT_LAN_5", "Vuelva a <a href='".e_SELF."'>página principal de envios</a> para enviar más contenidos<br />or<br />Vaya a <a href='".e_PLUGIN."content/content.php'>página principal de contenidos</a> para ver los contenidos.");
define("CONTENT_ADMIN_SUBMIT_LAN_6", "Lista de tipos de contenidos");
define("CONTENT_ADMIN_SUBMIT_LAN_7", "Sumisiones de tipos de contenidos");
define("CONTENT_ADMIN_SUBMIT_LAN_8", "Contenido enviado borrado");
define("CONTENT_ADMIN_SUBMIT_LAN_9", "");
define("CONTENT_ADMIN_SUBMIT_LAN_10", "");
define("CONTENT_ADMIN_SUBMIT_LAN_11", "");
define("CONTENT_ADMIN_SUBMIT_LAN_12", "");
define("CONTENT_ADMIN_SUBMIT_LAN_13", "");
define("CONTENT_ADMIN_SUBMIT_LAN_14", "");
define("CONTENT_ADMIN_SUBMIT_LAN_15", "");
define("CONTENT_ADMIN_SUBMIT_LAN_16", "");
define("CONTENT_ADMIN_SUBMIT_LAN_17", "");
define("CONTENT_ADMIN_SUBMIT_LAN_18", "");
define("CONTENT_ADMIN_SUBMIT_LAN_19", "");


define("CONTENT_ADMIN_CONVERSION_LAN_0", "Contenido");
define("CONTENT_ADMIN_CONVERSION_LAN_1", "Análisis");
define("CONTENT_ADMIN_CONVERSION_LAN_2", "Artículo");
define("CONTENT_ADMIN_CONVERSION_LAN_3", "Categoría");
define("CONTENT_ADMIN_CONVERSION_LAN_4", "Categorías");
define("CONTENT_ADMIN_CONVERSION_LAN_5", "página");
define("CONTENT_ADMIN_CONVERSION_LAN_6", "páginas");
define("CONTENT_ADMIN_CONVERSION_LAN_7", "padre principal insertada");
define("CONTENT_ADMIN_CONVERSION_LAN_8", "Prefs de padre principal insertadas");
define("CONTENT_ADMIN_CONVERSION_LAN_9", "no");
define("CONTENT_ADMIN_CONVERSION_LAN_10", "necesaria padre principal");
define("CONTENT_ADMIN_CONVERSION_LAN_11", "ANALISIS DE CONVERSIÓN");
define("CONTENT_ADMIN_CONVERSION_LAN_12", "Filas totales a convertir");
define("CONTENT_ADMIN_CONVERSION_LAN_13", "filas totales convertidas");
define("CONTENT_ADMIN_CONVERSION_LAN_14", "Advertencias totales");
define("CONTENT_ADMIN_CONVERSION_LAN_15", "Fallos totales");
define("CONTENT_ADMIN_CONVERSION_LAN_16", "VIEJA TABLA DE CONTENIDO : ANALISIS");
define("CONTENT_ADMIN_CONVERSION_LAN_17", "filas totales");
define("CONTENT_ADMIN_CONVERSION_LAN_18", "filas desconocidas");
define("CONTENT_ADMIN_CONVERSION_LAN_19", "todas las filas son familiares");
define("CONTENT_ADMIN_CONVERSION_LAN_20", "padre PRINCIPAL DE CONTENIDO");
define("CONTENT_ADMIN_CONVERSION_LAN_21", "ANALIZAR padre PRINCIPAL");
define("CONTENT_ADMIN_CONVERSION_LAN_22", "ARTICULO padre PRINCIPAL");
define("CONTENT_ADMIN_CONVERSION_LAN_23", "Falló la inserción");
define("CONTENT_ADMIN_CONVERSION_LAN_24", "NO HAY PÁGINAS DE CONTENIDO");
define("CONTENT_ADMIN_CONVERSION_LAN_25", "PÁGINAS DE CONTENIDO PRESENTES");
define("CONTENT_ADMIN_CONVERSION_LAN_26", "insertado");
define("CONTENT_ADMIN_CONVERSION_LAN_27", "análisis de conversión");
define("CONTENT_ADMIN_CONVERSION_LAN_28", "filas viejas totales");
define("CONTENT_ADMIN_CONVERSION_LAN_29", "filas nuevas totales");
define("CONTENT_ADMIN_CONVERSION_LAN_30", "Fallo");
define("CONTENT_ADMIN_CONVERSION_LAN_31", "Advertencia");
define("CONTENT_ADMIN_CONVERSION_LAN_32", "Vieja categoría no existe: se han añadido a una categoría superior");
define("CONTENT_ADMIN_CONVERSION_LAN_33", "Nueva categoría no existe: se han añadido a una categoría superior");
define("CONTENT_ADMIN_CONVERSION_LAN_34", "NO HAY PÁGINAS DE CATEGORÍAS ANALIZADAS");
define("CONTENT_ADMIN_CONVERSION_LAN_35", "PÁGINAS DE CATEGORÍAS ANALIZADAS");
define("CONTENT_ADMIN_CONVERSION_LAN_36", "NO HAY PÁGINAS ANALIZADAS O ENVIADAS");
define("CONTENT_ADMIN_CONVERSION_LAN_37", "PÁGINAS ANALIZADAS O ENVIADAS PRESENTES");
define("CONTENT_ADMIN_CONVERSION_LAN_38", "NO HAY PÁGINAS DE CATEGORÍAS DE ARTÍCULOS");
define("CONTENT_ADMIN_CONVERSION_LAN_39", "PÁGINAS DE CATEGORÍAS DE ARTÍCULOS PRESENTES");
define("CONTENT_ADMIN_CONVERSION_LAN_40", "NO HAY PÁGINAS DE ARTÍCULOS O ENVIADOS");
define("CONTENT_ADMIN_CONVERSION_LAN_41", "PÁGINAS DE ARTÍCULOS O ENVIADOS PRESENTES");
define("CONTENT_ADMIN_CONVERSION_LAN_42", "Resultados de la conversión de los contenidos antiguos a los nuevos contenidos del plugin");
define("CONTENT_ADMIN_CONVERSION_LAN_43", "Pulse el botón para convertir el antiguas tablas de contenidos");
define("CONTENT_ADMIN_CONVERSION_LAN_44", "¡El nuevo contenido ya contiene datos !<br />¿Está seguro de convertir el antiguo contenido en las nuevas tablas de contenidos ?<br /><br />Si todavía queire conevrtir la tabla, los viejos datos de contenidos serán agregados a la nueva tabla ya existente, pero no se garantiza que todos los artículos se agreguen a las categorías existentes de manera correcta");
define("CONTENT_ADMIN_CONVERSION_LAN_45", "Falló la inserción: padre principal no insertada");
define("CONTENT_ADMIN_CONVERSION_LAN_46", "Comience a manejar su contenido en <a href='".e_PLUGIN."content/admin_content_config.php'> Plugin de manejo de contenidos</a> !");
define("CONTENT_ADMIN_CONVERSION_LAN_47", "Conversión completada");
define("CONTENT_ADMIN_CONVERSION_LAN_48", "Click aquí para detalles");
define("CONTENT_ADMIN_CONVERSION_LAN_49", "Conversión de página");
define("CONTENT_ADMIN_CONVERSION_LAN_50", "Conversión de padres principales");
define("CONTENT_ADMIN_CONVERSION_LAN_51", "Filas desconocidas");
define("CONTENT_ADMIN_CONVERSION_LAN_52", "Creados ajustes por defecto de categ´rias principales padre");
define("CONTENT_ADMIN_CONVERSION_LAN_53", "ya existe un padre principal con ese nombre");
define("CONTENT_ADMIN_CONVERSION_LAN_54", "Crear ajustes por defecto de categorías padre (contenido, análisi y artículos)");
define("CONTENT_ADMIN_CONVERSION_LAN_55", "plugin de manejo de contenidos : opciones de conversión");
define("CONTENT_ADMIN_CONVERSION_LAN_56", "Click en el botón para ir a la página a Crear nueva categoría.");
define("CONTENT_ADMIN_CONVERSION_LAN_57", "Escoja padre");

?>