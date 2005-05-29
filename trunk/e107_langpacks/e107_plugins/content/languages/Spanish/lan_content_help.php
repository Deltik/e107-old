<?php


//require_once(e_PLUGIN."content/handlers/content_class.php");

define("CONTENT_ADMIN_HELP_LAN_0", "<i>Si no ha creado ninguna categor�a principal todav�a, hagalo en la p�gina de crear categor�as.</i><br /><br /><b>botones principales</b><br />seleccione la categor�a principal pulsando el bot�n.");
define("CONTENT_ADMIN_HELP_LAN_1", "<i>Esta p�gina muestra todos los contenidos de la principal categor�a padre que ha seleccionado en la p�gina de contenidos.</i><br />");
define("CONTENT_ADMIN_HELP_LAN_2", "<br /><i>Ha seleccionado una categor�a espec�fica para mostrar contenidos.<br />la lista contendr� solo los contenidos de la categor�a seleccionada.</i><br />");
define("CONTENT_ADMIN_HELP_LAN_3", "<br /><b>Primeras letras</b><br />Si las letras iniciales de los multiples contenidos de la cabecera de contenido est� rpesente, ver� los botones solo para aquellos contenidos comenzando con esa letra.<br /><br /><b>lista detallada</b><br />Ver� una lista con todos los contenidos y su id, icono, autor, cabecera [subcabecera] y opciones.<br /><br /><b>opciones</b><br />puede editar o eliminar un contenido con los botones mostrados.");
define("CONTENT_ADMIN_HELP_LAN_4", "<i>Las principales categor�as padre se mostrar�n como botones.</i><br /><br /><b>botones padre principales</b><br /> por favor seleccione una categor�a del padre principal para crear su contenido.<br />");
define("CONTENT_ADMIN_HELP_LAN_5", "<i>Esta p�gina muestra el formulario de creaci�n de contenidos</i><br /><br /><b>Crear formulario</b><br />usted puede proporcionar toda la informaci�n para el nuevo contenido.<br /><br /><b>bbcode</b><br />con los tags bbcode puede especificar ciertos estilos para partes del texto, insertar enlaces y dem�s.<br /><br /><b>[newpage=name]</b><br />Con el tag [newpage] puede cortar la informaci�n en varias p�ginas.<br />Uso del tag [newpage]: Si quiere crear m�ltiples p�ginas, inserte un tag  [newpage] antes de cada p�gina (�y no olvide de insertar uno al principio de cada contenido!).<br />El nuevo m�todo [newpage=name] le permite dar un nombre a cada p�gina, que se mostrar� en el �ndice del contenido<br />");
define("CONTENT_ADMIN_HELP_LAN_6", "<i>Esta p�gina muestra el formulario de edici�n del contenido</i><br /><br /><b>Formulario de edici�n</b><br />Puede editar toda la informaci�n del formulario y enviar los cambios");
define("CONTENT_ADMIN_HELP_LAN_7", "<i>Ver� los botones de la principal categor�a padre y del formulario de creaci�n </i><br /><br /><b>Padre principal predeterminado</b><br />por defecto, este formulario le permite crear un nuevo padre. (la tira de categor�as est� vac�a)<br /><br /><b>Botones padre principal</b><br />Si quiere crear una subcategor�a dentro de una existente en las categor�as principales padre, pulse el bot�n para seleccionar la categor�a principal padre.");
define("CONTENT_ADMIN_HELP_LAN_8", "<i>Ha seleccionado la categor�a principal padre para crear una subcategor�a en</i><br /><br /><b>Tira de categor�as</b><br />Ver� que la tira de categor�a contiene todas la subcategor�as existentes de su categor�a principal padre</b><br /> Ahora puede a�adir toda la informaci�n de la nueva categor�a.");
define("CONTENT_ADMIN_HELP_LAN_9", "<i>Ver� los botones de la categor�a principal padre.</i><br /><br /><b>Botones padre principal</b><br />Primero debe pulsar el bot�n para selecciona la categor�a padre principal.");
define("CONTENT_ADMIN_HELP_LAN_10", "
  	 <i>Esta p�gina mostrar� todas las categor�as de la categor�a padre principal que seleccion� en la p�gina anterior.</i><br /><br />
  	 <b>lista detallada</b><br />Ver� una lista de todas las subcategor�as con su id, icono, autor, categor�a [subcabecera] y opciones.<br />
  	 <br />
  	 <b>explicaci�n de los iconos</b><br />
  	 ".CONTENT_ICON_EDIT." : Para todas las categor�as haga click en este bot�n para editarlas.<br />
  	 ".CONTENT_ICON_DELETE." : Para todas las categor�as haga click en este bot�n pata eliminarlas.<br />
  	 ".CONTENT_ICON_OPTIONS." : Solo para la categor�a principal (al tope de la lista) haga click en este bot�n para fijar y controlar las opciones.<br />
  	 ".CONTENT_ICON_CONTENTMANAGER_SMALL." : (Solo Admin del sitio) para cada subcategor�a haga click en este bot�n para manejar el configurador personal de otros administradores.<br />
  	 ");
define("CONTENT_ADMIN_HELP_LAN_11", "<i>Esta p�gina muestra el formulario de edici�n de la categor�a. </i><br /><br /><b>Formulario de edici�n de categor�a</b><br /> Ahora puede a�adir toda la informaci�n para esta (sub)categor�a y enviar los cambios.");
define("CONTENT_ADMIN_HELP_LAN_12", "
  	 <i>Esta p�gina muestra las opciones que puede fijar para este padre principal. Cada padre principal tiene su propio juego de opciones, asi que est� seguro de fijarlas correctamente.</i><br /><br />
  	 <b>Valores por defecto</b><br />Por defecto, todos los valroes est�n presentes y actualizados en las preferencias cuando Usted mira esta p�gina, pero cambie lo que quiera de sus propios estandares.<br /><br />
  	 <b>Divisi�n en 8 secciones</b><br />Las opciones est�n divididas en 8 secciones principales. Ver� cada secci�n en el men� de la derecha. Puede hacer click en ellas e ir al juego espec�fico de opciones para esa secci�n.<br /><br />
  	 <b>Crear</b><br />En esta secci�n puede especificar las opciones para la creaci�n de contenidos en las p�ginas de los Admins.<br /><br />
  	 <b>submit</b><br />En esta secci�n puede especificar las opciones para el formulario de env�os de contenidos.<br /><br />
  	 <b>ruta y tema</b><br />En esta secci�n puede fijar un tema para este padre principal, y proveer las direcciones donde tiene almacenadas las im�genes para este padre principal.<br /><br /><b>General</b><br />En esta secci�n puede especificar las opciones generales para usar a trav�s de todas las p�ginas de contenidos.<br /><br />
  	 <b>Lista de p�ginas</b><br />En esta secci�n puede especificar las opciones de las p�ginas, donde se listan los contenidos.<br /><br />
  	 <b>P�gina de categor�as</b><br />En esta secci�n puede especificar las opciones de como mostrar las p�ginas de categor�as.<br /><br />
  	 <b>P�gina de contenidos</b><br />En esta secci�n puede especificar las opciones de como mostrar las p�ginas de contenidos.<br /><br />
  	 <b>Men�</b><br />En esta secci�n puede especificar las opciones para el men� en el padre principal.<br /><br />
  	 ");
define("CONTENT_ADMIN_HELP_LAN_13", "<i>En esta p�gina puede listar todos los contenidos que se han enviado por los usuarios .</i><br /><br /><b>lista de detalles</b><br />Ver� una lista de los contenidos con su id, icono, padre principal, cabecera [subcabecera], autor y opciones.<br /><br /><b>opciones</b><br />Puede enviar o eliminar contenidos usando los botones mostrados.");
define("CONTENT_ADMIN_HELP_LAN_14", "�rea de ayuda de configuraci�n de contenidos");
define("CONTENT_ADMIN_HELP_LAN_15", "<br /><br /><b>configuraci�n personal</b><br />Puede asignar administradores a ciertas categor�as.Haciendo esto, esos administradores pueden configurar sus contenidos personales fuera del control de la p�gina del administrador de categor�as (content_manager.php).");
define("CONTENT_ADMIN_HELP_LAN_16", "<i>en esta p�gina puede asignar administradores a las categor�as que ha seleccionado </i><br /><br />Asigne un administrador en la columna de la izquierda pulsando su nombre. Despu�s de pulse el bot�n de asignaci�n  en la columna derecha para asignarle a esta categor�a.");
define("CONTENT_ADMIN_HELP_LAN_17", "<i>Si no ha a�adido a�n categor�as padres principales, por favor cree una nueva p�gina de categor�as.</i><br /><br /><b>Botones padre principales</b><br /> Seleccione una categor�a padre principal pulsando el bot�n .");
define("CONTENT_ADMIN_HELP_LAN_18", "
  	 <i>Esta p�gina muestra todas las categor�as de la categor�a principal padre que seleccion� en la p�gina de ordenaci�n de contenidos.</i><br /><br />
  	 <b>Lista detallada</b><br />Ver� la categor�a id y su nombre. Tambi�n ver� varias opciones para manejar el orden de las categor�as.<br />
  	 <br />
  	 <b>Explicaci�n de los iconos</b><br />
  	 ".CONTENT_ICON_ORDERALL." Manejar el orden global de los contenidos sin contar con la categor�a.<br />
  	 ".CONTENT_ICON_ORDERCAT." Manejar el orden de los contenidos de la categor�a espec�ficada.<br />
  	 <img src='".e_IMAGE."admin_images/up.png' alt=''> El bot�n arriba le permite mover el orden del contenido un valor arriba.<br />
  	 <img src='".e_IMAGE."admin_images/down.png' alt=''> El bot�n abajo le permite mover el orden del contenido un valor abajo.<br />
  	 <br />
  	 <b>order</b><br />Aqu� puede manualmente fijar el orden de todas las categor�as en el padre principal. Necesita cambiar los valores en las cajas seleccionadas en el orden que desee y pulsar el bot�n de abajo para guardar el orden.<br />
  	 ");
define("CONTENT_ADMIN_HELP_LAN_19", "
  	 <i>Esta p�gina muestra todos los contenidos de la categor�a que ha seleccionado.</i><br /><br />
  	 <b>Lista detallada</b><br />Puede ver el contenido id, autor, y cabecera Tambi�n puede ver varias opciones para manejar el orden de los contenidos.<br />
  	 <br />
  	 <b>Explicaci�n de los iconos</b><br />
  	 <img src='".e_IMAGE."admin_images/up.png' alt=''> El bot�n arriba le permite mover el orden del contenido un valor arriba.<br />
  	 <img src='".e_IMAGE."admin_images/down.png' alt=''> El bot�n abajo le permite mover el orden del contenido un valor abajo.<br />
  	 <br />
  	 <b>Orden</b><br />Aqu� puede manualmente fijar el orden de todas las categor�as de este padre principal. Necesita cambiar los valores de las cajas de selecci�n para ordenarlos como desee y pulsar el bot�n de abajo  para guardar el nuevo orden.<br />
  	 ");
define("CONTENT_ADMIN_HELP_LAN_20", "<i>Si no ha a�adido una categor�a padre principal, por favor, cree una nueva p�gina de categor�as.</i><br /><br /><b>Botones padre principal</b><br />seleccione una categor�a padre principal usando el bot�n.<br /><br /><b>3 tipos de ordenaci�n</b><br />hay 3 tipos de ordenaci�n diferentes disponibles, que no interfieren con cada uno.<br /><b>ordenar categor�a</b><br />primero debe ordenar las subcategor�as del padre principal. (esto se usar� al ojear las categor�as)<br /><b>ordenarlas en cada categor�a </b><br />segundo puede ordenar los contenidos en subcategor�as separadas (esto se usar� al ojear los contenidos de las categor�as)<br /><b>ordenarlos todos en una lista</b><br />tercero puede ordenar todos los contenidos del padre principal(esto se usar� en la lista reciente de los contenidos).");

?>