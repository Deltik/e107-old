<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_plugins/content/languages/Spanish/lan_content_help.php,v $
|     $Revision: 1.2 $
|     $Date: 2005-11-11 23:57:58 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
define("CONTENT_ADMIN_HELP_1", "�rea de ayuda de la p�gina de contenidos");

define("CONTENT_ADMIN_HELP_ITEM_1", "
<i>Si no ha a�adido categor�as principales, h�galo en la p�gina <a href='".e_SELF."?type.0.cat.create'>Crear nueva categor�a</a>.</i><br /><br />
<b>Categor�a</b><br />Seleccione una categor�a desde el men� desplegable para gestionar el contenido de esa categor�a.<br /><br />
Las categor�as principales se mostrar�n en negrita y tendr�n (TODAS) las extensiones detr�s de ellas. Seleccionando una de �stas se mostrar�n todos los elementos de esta categor�a principal.<br /><br />Para cada categor�a principal todas las subcategor�as se mostrar�n incluyendo la propia categor�a principal (�stas mostradas en texto plano). Seleccionando en estas categor�as se mostrar�n los elementos, solo se mostrar�n los elementos de esa categor�a.");
define("CONTENT_ADMIN_HELP_ITEM_2", "
<b>Primeras letras</b><br />Si m�ltiples primeras letras de contenidos existen (cabeceras de contenidos), ver� los botones para seleccionar solo los contenidos con esa letra. Seleccionando el bot�n 'todo' se mostrar� una lista conteniendo todos los contenidos en esa categor�a.<br /><br />
<b>Lista detallada</b><br />Ver� una lista de todos los caontenidos con su id, icono, autor, cabecera [subcabecera] y opciones.<br /><br />
<b>Explicaci�n de iconos</b><br />".CONTENT_ICON_EDIT." : Editar el contenido.<br />".CONTENT_ICON_DELETE." : Eliminar el contenido.<br />");

define("CONTENT_ADMIN_HELP_ITEMEDIT_1", "
<b>Editar formulario</b><br />Puede editar toda la informaci�n de este contenido y enviar los cambios.<br /><br />
Si cambia la categor�a de este contenido, por favor h�galo antes. Despu�s de seleccionar la categor�a correcta, cambie o a�ada campos existentes, antes de enviar los nuevos cambios.");

define("CONTENT_ADMIN_HELP_ITEMCREATE_1", "<b>Categor�a</b><br />Por favor, seleccione una categor�a desde la caja de selecci�n para crear su contenido.<br />");
define("CONTENT_ADMIN_HELP_ITEMCREATE_2", "<b>Formulario de creaci�n</b><br />Ahora puede proveer toda la informaci�n a este cotenido y enviarlo.<br /><br />Cuidado con el hecho de que diferentes categor�as principales pueden tener diferentes juegos de ajustes; diferentes campos pueden estar disponibles. �De todas maneras siempre necesitar� seleccionar un a categor�a primero antes de rellenar los campos!");

define("CONTENT_ADMIN_HELP_CAT_1", "<i>Esta p�gina muestra todas las categor�as y subcategor�as existentes.</i><br /><br /><b>Lista detallada</b><br />Ver� una lista con todas las subcategor�as con su id, icono, autor, categor�a [subcabecera] y opciones.<br /><br /><b>Explicaci�n de los iconos</b><br />".CONTENT_ICON_USER." : Enlace al perfil del autor<br />".CONTENT_ICON_LINK." : Enlace a la categor�a<br />".CONTENT_ICON_EDIT." : Editar la categor�a<br />".CONTENT_ICON_DELETE." : Eliminar la categor�a<br />");
define("CONTENT_ADMIN_HELP_CAT_2", "<i>Esta p�gina le permite crear una nueva categor�a</i><br /><br />�Siempre escoja una categor�a antes de rellenar cualquier campo !<br /><br />Es necesario hacerlo, porque se necesita cargar unas �nicas preferencias de categor�a en el sistema.<br /><br />Por defecto la p�gina de categor�as se muestra para crear una nueva categor�a principal.");
define("CONTENT_ADMIN_HELP_CAT_3", "<i>Esta p�gina muestra el formulario para editar la categor�a.</i><br /><br /><b>Formulario de edici�n de categor�a</b><br />Ahora puede editar toda la informaci�n para esta (sub)categor�a y enviar los cambios.<br /><br />Si quiere cambiar la zona de la categor�a principal de esta categor�a, por favor, h�galo antes. Despues de fijar la correcta categor�a, edite todos sus campos.");

define("CONTENT_ADMIN_HELP_ORDER_1", "<i>Esta p�gina muestra todas las categor�as y subcategor�as existentes.</i><br /><br /><b>LIsta detallada</b><br />Ver� el id y nombre de la categor�a. Tambi�n ver� algunas opciones para gestionar el orden de las categor�as.<br /><br /><b>eExplicaci�n de iconos</b><br />".CONTENT_ICON_USER." : Enlace al perfil del autor<br />".CONTENT_ICON_LINK." : Enlace a la categor�a<br />".CONTENT_ICON_ORDERALL." : Gestionar el orden de los contenidos sin tener en cuenta la categor�a.<br />".CONTENT_ICON_ORDERCAT." : Gestionar el orden de los contenidos en una categor�a espec�fica.<br />".CONTENT_ICON_ORDER_UP." : El bot�n arriba le permite mover un contenido en ese orden.<br />".CONTENT_ICON_ORDER_DOWN." : El bot�n abajo le permite mover el contenido en ese orden.<br /><br /><b>Orden</b><br />Aqu� puede manualmente fijar el orden de todas las categor�as de cada categor�a principal. Necesita cambiar los valores en la cajas al orden que desea establecer y pulsar el bot�n actualizar para enviar los cambios.<br />");
define("CONTENT_ADMIN_HELP_ORDER_2", "<i>Esta p�gina muestra todos los contenidos de las p�ginas que ha seleccionado.</i><br /><br /><b>Lista detallada</b><br />Ver� el id, autor, cabecera y algunas opciones para gestionar el orden de los contenidos.<br /><br /><b>Explicaci�n de iconos</b><br />".CONTENT_ICON_USER." : Enlace al perfil del autor<br />".CONTENT_ICON_LINK." : Enlace al contenido<br />".CONTENT_ICON_ORDER_UP." : El bot�n arriba le permite mover un contenido en ese orden.<br />".CONTENT_ICON_ORDER_DOWN." : El bot�n abajo le permite mover el contenido en ese orden.<br /><br /><b>Orden</b><br />Aqu� puede fijar manualmente el orden de todas las categor�as de esta categor�a principal. Necesita cambiar los valores en la cajas para fijar el orden deseado, luego pulsar en actualizar para enviar los cambios.<br />");
define("CONTENT_ADMIN_HELP_ORDER_3", "<i>Esta p�gina muestra todos los contenidos de la categor�a principal que ha seleccionado.</i><br /><br /><b>Lista detallada</b><br />Ver� el id, autor, cabeceray algunas opciones para gestionar el roden de los contenidos.<br /><br /><b>Explicaci�n de iconos</b><br />".CONTENT_ICON_USER." : Enlace al perfil del autor<br />".CONTENT_ICON_LINK." : Enlace al contenido<br />".CONTENT_ICON_ORDER_UP." : El bot�n arriba le permite mover un contenido en ese orden.<br />".CONTENT_ICON_ORDER_DOWN." : El bot�n abajo le permite mover el contenido en ese orden.<br /><br /><b>Orden</b><br />Aqu� puede fijar manualmente el orden de todas las categor�as de su categor�a principal. Necesita cambiar el valor de las cajas para fijar el roden deseado, luego pulsar en actualizar para enviar los cambios.<br />");

define("CONTENT_ADMIN_HELP_OPTION_1", "En esta p�gina puede seleccionar una categor�a principal para fijar las opciones, o puede escojer editar las preferencias por defecto.<br /><br /><b>Explicaci�n de los iconos</b><br />".CONTENT_ICON_USER." : Enlace al perfil del autor<br />".CONTENT_ICON_LINK." : enlace a la categor�a<br />".CONTENT_ICON_OPTIONS." : Editar las opciones<br /><br /><br />
  	 Las preferencias predeterminadas solo se usan cuando crea una categor�a principal. Por lo tanto, cuando crea una nueva categor�a principal esas preferencias se almacenar�n en ella. Puede cambiarlas asegur�ndose que las nuevas categor�as principales creadas ya tienen este juego de opciones existentes.
  	 <br /><br />
  	 Cada categor�a principal tiene su propio juego de opciones, las cuales son �nicas para esa categor�a principal espec�fica");
/*define("CONTENT_ADMIN_HELP_OPTION_2", "
<i>Esta p�gina muestra las opciones que puede fijar en la categor�a principal. Cada categor�a principal tiene su propio juego de opciones, por lo que aseg�rese de fijarlas correctamente.</i><br /><br />
<b>Valores por defecto</b><br />Por defecto, todos los valores existentes se actualizan en las preferencias cuendo navegue por esta p�gina, pero los puede cambiar seg�n sus est�ndares<br/><br />
");
*/
define("CONTENT_ADMIN_HELP_MANAGER_1", "En esta p�gina ver� una lista de categor�as. Puede gestionar el 'Gestor personal de contenidos' para cada categor�a pulsando en el icono.<br /><br /><b>Explicaci�n de inconos</b><br />".CONTENT_ICON_USER." : Enlace al perfil del autor<br />".CONTENT_ICON_LINK." : Enlace a la categor�a<br />".CONTENT_ICON_CONTENTMANAGER_SMALL." : Editar los gestores personales de contenidos<br />");
define("CONTENT_ADMIN_HELP_MANAGER_2", "<i>En esta p�gina puede asignar usuarios a las categor�as seleccionadas</i><br /><br /><b>Gestor personal</b><br />Puede asignar usuarios a ciertas categor�as. Haciendo �sto, esos ususarios pueden gestionar su contenido personal de esas categor�as fuera las �rea del administrador (content_manager.php).<br /><br />Asigne usuarios desde la columna de la izquierda pulsando sobre su nombre. Ver� como esos nombres se mueven a la columna de la derecha. Despu�s de pulsar el bot�n de asignaci�n, los usuarios de la columan de la derecha ser�n asignados a esa categor�a.");

define("CONTENT_ADMIN_HELP_SUBMIT_1", "<i>En esta p�gina ver� una lista de todos los contenidos que han enviado los usuarios.</i><br /><br /><b>Lista detallada</b><br />Ver� una lista de esos contenidos con su id, autor, categor�a principal, cabecera [subcabecera] y opciones.<br /><br /><b>Opciones</b><br />Puede enviar o eliminar contenidos usando los botones mostrados.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_1", "this page allows you to set options for the admin create item page.<br /><br />You can define which sections are available when an admin (or personal content manager) creates a new content item<br /><br /><b>custom data tags</b><br />you can allow a user or admin to add optional fields to the content item by using these custom data tags. These optional fields are blank key=>value pairs. For instance: you could add a key field for 'photographer' and provide the value field with 'all photos by me'. Both these key and value fields are empty textfields which will be present in the create form.<br /><br /><b>preset data tags</b><br />apart from the custom data tags, you can provide preset data tags. The difference is that in preset data tags, the key field already is given and the user only needs to provide the value field for the preset. In the same example as above 'photographer' can be predefined, and the user needs to provide 'all photos by me'. You can choose the element type by selecting one option in the selectbox. In the popup window, you can provide all the information for the preset data tag.<br />");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_2", "El env�o de opciones afectan sobre el ev�o de contenidos del ususario.<br /><br />Puede definir que secciones est�n disponibles para el usuario cuando env�o un contenido.<br /><br />".CONTENT_ADMIN_OPT_LAN_11.":<br />".CONTENT_ADMIN_OPT_LAN_12."");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_3", "En la ruta y opciones de temas, puede definir donde se almacenar�n las im�genes y archivos.<br /><br />Puede definir que tema se usar� como principal. Puede crear temas adicionales copiando y renombrando el directorio  'default' en sus carpetas de templates.<br /><br />Puede definir una estructura de plantilla por defecto para los nuevos contenidos. Puede crear una nueva plantilla creando el archivo content_content_template_XXX.php en su carpeta 'templates/default'. Estas plantillas pueden ser usadas para diferentes contenidos en la categor�a principal.<br /><br />");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_4", "Las opciones generales son opciones que se usan a trav�s de p�ginas de contenidos del plugin gestor de contenidos.");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_5", "Estas opciones afectan sobre el �rea de los contenidos personales en el �rea del administrador del gestor de contenidos.<br /><br />".CONTENT_ADMIN_OPT_LAN_63."");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_6", "Estas opciones se usan en el men� para esta categor�a principal si la ha activado en el men�.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."<br /><br />".CONTENT_ADMIN_OPT_LAN_118.":<br />".CONTENT_ADMIN_OPT_LAN_119."<br /><br />");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_7", "Las opciones de previsualizaci�n de contenidos afectan sobre la peque�a previsualizaci�n que se da a cada contenido.<br /><br />Esta previsualizaci�n se da en diversas p�ginas, como la p�gina reciente, la vista de elementos en la p�gina de categor�as y en la vista de elementos por autor.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_8", "Las p�ginas de categor�as muestran informaci�n de las categor�as de contenidos.<br /><br />Existen dos �reas presentes:<br /><br />P�gina de todas las categor�as:<br />Esta p�gina muestra todas las categor�as de este principal<br /><br />Ver p�gina de categor�a:<br />Esta p�gina muestra la categor�a, opcionalmente sus subcategor�as y los contenidos presentes en ellas<br />");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_9", "La p�gina de contenidos muestra los contenidos.<br /><br />Puede definir que secciones mostrar activando/desactivando las cajas de selecci�n.<br /><br />Puede mostrar la direcci�n de correo de un autor no-miembro.<br /><br />Puede sobreescribir los iconos email/imprimir/pdf , el sistema de valoraci�n y los comentarios.<br /><br />".CONTENT_ADMIN_OPT_LAN_74."");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_10", "La p�gina del autor muestra una lista �nica de autores de los contenidos.<br /><br />Puede definir que seccione smostrar activando/desactivando las cajas de selecci�n.<br /><br />Puede limitar el n�mero de contenidos por p�gina.<br />");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_11", "La p�gina de archivos muestra todos los contenidos.<br /><br />Puede definir que secciones mostrar activando/desactivando las cajas de selecci�n.<br /><br />Puede mostrar las direcciones de correo de los autores no.miembros.<br /><br />Puede limitar el n�mero de elementos a mostrar por p�gina.<br /><br />".CONTENT_ADMIN_OPT_LAN_66."<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_12", "La p�gina m�s valorada muestra los contenidos que han sido valorados por los usuarios.<br /><br />Puede escojer que secciones mostrar activando las cajas de selecci�n.<br /><br />Tambi�n puede definir si se mostrar� la direcci�n de correo del un autor no-miembro.");
  	 
define("CONTENT_ADMIN_HELP_OPTION_DIV_13", "La p�gina m�s puntuada muestra todos los contenidos que han sido puntuados por el autor en los contenidos.<br /><br />Puede escojer que secciones mostrar activando las cajas de selecci�n.<br /><br />Tambi�n puede definir si se mostrar� la direcci�n de correo del un autor no-miembro.");

?>