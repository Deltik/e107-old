<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/lan_installer.php,v $
|     $Revision: 1.8 $
|     $Date: 2007-04-21 09:35:39 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/

define("LANINS_001", "Instalaci�n e107");


define("LANINS_002", "Etapa ");
define("LANINS_003", "1");
define("LANINS_004", "Selecci�n del idioma");
define("LANINS_005", "Por favor, escoja el idioma a usar durante la instalaci�n");
define("LANINS_006", "Fijar idioma");
define("LANINS_007", "4");
define("LANINS_008", "PHP &amp; MySQL Comprobaci�n de versiones / Comprobaci�n de permisos de archivos");
define("LANINS_009", "Reinicie los permisos del archivo");
define("LANINS_010", "Archivo no escribible: ");
define("LANINS_010a", "Carpeta no escribible: ");
define("LANINS_011", "Error");
define("LANINS_012", "Las funciones de MySQL no parecen existir. Esto puede significar que la extensi�n MySQL de PHP no est� instalada o su versi�n de PHO no fu� compilada para funcionar con MySQL."); // help for 012
define("LANINS_013", "No se pudo determinar la versi�n de su MySQL. Esto podr�a significar que su servidor MySQL est� ca�do o rechazando las conexiones.");
define("LANINS_014", "Permisos de archivo");
define("LANINS_015", "Versi�n PHP");
define("LANINS_016", "MySQL");
define("LANINS_017", "OK");
define("LANINS_018", "Aseg�rese de que estos archivos listados tienen permisos de escritura. Si tiene alg�n problema a la hora de configurarlos, contacte con su servidor.");
define("LANINS_019", "La versi�n de PHP instalada en su servidor no es operativa para funcionar con e107. e107 requiere una versi�n mayor de 4.3.0 para funcionar correctamente. Actualice su versi�n PHP o contacte con su servidor para actualizar.");
define("LANINS_020", "Contin�e la instalaci�n");
define("LANINS_021", "2");
define("LANINS_022", "Detalles del servidor MySQL");
define("LANINS_023", "Por favor, escriba los ajustes de MySQL aqu�.
			  
Si tiene permisos de administrador, podr� crear una nueva base de datos activando la caja, si no, deber�a crear una base de datos o usar una ya existente.

Si solo tiene una base de datos, utilice el prefijo para que otros scripts puedan utilizar la misma base de datos.
Si no tiene datos de su MySQL, contacte con su servidor.");
define("LANINS_024", "Servidor MySQL:");
define("LANINS_025", "Nombre de usuario MySQL:");
define("LANINS_026", "Contrase�a MySQL:");
define("LANINS_027", "Base de datos MySQL:");
define("LANINS_028", "�Crear base de datos?");
define("LANINS_029", "Prefijo de la tabla:");
define("LANINS_030", "El servidor MySQL que quiere utilizar. Puede incluir el puerto utilizado. ej. \"servidor:puerto\" o una ruta local ej. \":/ruta/a/socket\" para localhost.");
define("LANINS_031", "El nombre de usuario que desea para conectar a su servidor MySQL");
define("LANINS_032", "La contrase�a del usuario");
define("LANINS_033", "La base de datos MySQL que desea utilizar en e107, algunas veces referenciada como una estructura. Si el usuario tiene permiso para crear bases de datos, tiene la opci�n de hacerlo si actualmente no existe.");
define("LANINS_034", "El prefijo que desea utilizar en e107 para encabezar las tablas. �til para instalaciones m�ltiples de e107 en una misma base de datos.");
define("LANINS_035", "Continuar");
define("LANINS_036", "3");
define("LANINS_037", "Verificaci�n de conexi�n MySQL");
define("LANINS_038", " y creaci�n de la base de datos");
define("LANINS_039", "Aseg�rese que ha rellenado todos los campos m�s importantes, Servidor, Usuario y Base de datos MySQL.(Son necesarios para el servidor MySQL)");
define("LANINS_040", "Errores");
define("LANINS_041", "e107 no pudo establecer conexi�n con el servidor MySQL usando la informaci�n dada. Por favor, vuelva a la �ltima p�gina para asegurarse que la informaci�n es correcta.");
define("LANINS_042", "Conexi�n con el servidor MySQL establecida y verificada.");
define("LANINS_043", "Imposible crear la base de datos, por favor, aseg�rese que tiene permisos suficientes para crear bases de datos en su servidor.");
define("LANINS_044", "Base de datos creada con �xito.");
define("LANINS_045", "Por favor, pulse el bot�n para pasar a la siguiente estapa.");
define("LANINS_046", "5");
define("LANINS_047", "Detalles del Administrador");
define("LANINS_048", "Vuelva al �tlima paso");
define("LANINS_049", "Las dos contrase�as no coinciden. Por favor, vuelva e int�ntelo de nuevo.");
define("LANINS_050", "Extensi�n XML");
define("LANINS_051", "Instalada");
define("LANINS_052", "No instalada");
define("LANINS_053", "e107 .700 requiere la extensi�n PHP XML para ser instalado. Por favor, contacte con su servidor o lea la informaci�n en ");
define("LANINS_054", " antes de continuar");
define("LANINS_055", "Confirmaci�n de instalaci�n");
define("LANINS_056", "6");
define("LANINS_057", " e107 ahora tiene toda la informaci�n para completar la instalaci�n.

Por favor, pulse el bot�n para crear la base de datos y guardar sus ajustes.

");
define("LANINS_058", "7");
define("LANINS_060", "Imposible leer el archivo de datos sql

Por favor, aseg�rese que <b>core_sql.php</b> existe en el directorio <b>/e107_admin/sql</b>.");
define("LANINS_061", "e107 no pudo crear las tablas necesarias.
Por favor, limpie la base de datos y rectifique cualquier problema antes de probar de nuevo.");
define("LANINS_062", "�Bienvenido a su nuevo sitio!");
define("LANINS_062", "[b]�Bienvenido a su nuevo sitio![/b]
e107 se instal� correctamente y est� preparado para aceptar contenidos.<br />
Su secci�n de administraci�n est� [link=e107_admin/admin.php]aqu�[/link], haga click para ir ahora. Necesitar� conectarse con el usuario y contrase�a utilizada en la instalaci�n.
[b]Soporte[/b]
P�gina e107: [link=http://e107.org]http://e107.org[/link], entrar� respuesta y documentaci�n aqu�.
Foros: [link=http://e107.org/e107_plugins/forum/forum.php]http://e107.org/e107_plugins/forum/forum.php[/link]
  	 
[b]Descargas[/b]
Plugins: [link=http://e107coders.org]http://e107coders.org[/link]
Themas: [link=http://e107themes.org]http://e107themes.org[/link]
  	 
Gracias por probar e107, esperamos que cubra sus necesidades.
(Puede eliminar este mensaje desde la secci�n de administraci�n.)");
define("LANINS_063", "Bienvenido a e107.");

define("LANINS_069", "�e107 Se ha instalado con �xito!

Por razones de seguridad deber�a fijar los permisos en el archivo <b>e107_config.php</b> a 644.

Tambi�n deber�a borrar el directorio e107_install de su servidor despu�s de pulsar el bot�n siguiente
");
define("LANINS_070", "e107 no pudo guardar la archivo de configuraci�n principal en su servidor.

Aseg�rese que el archivo <b>e107_config.php</b> tiene los permisos correctos");
define("LANINS_071", "Finalizando la instalaci�n");
define("LANINS_072", "Nombre Admin");
define("LANINS_073", "Este es el nombre que usar� al conectarse. Si lo desea, util�celo como nombre a mostrar");
define("LANINS_074", "Nombre Admin a mostrar");
define("LANINS_075", "Este es el nombre que se mostrar� en su perfil y en todo el sitio. Si desea mostrar el mismo nombre que el nombre de usuario, d�jelo en blanco.");
define("LANINS_076", "Contrase�a Admin");
define("LANINS_077", "Por favor, indique la contrase�a que asignar� al administrador");
define("LANINS_078", "Admin Password Confirmation");
define("LANINS_079", "Vuelva a escribir la contrase�a para confrimarla");
define("LANINS_080", "Email Admin");
define("LANINS_081", "Escriba su direcci�n email");
define("LANINS_082", "usuario@susitio.com");
// Better table creation error reporting
define("LANINS_083", "MySQL Informe error:");
define("LANINS_084", "El instalador no puede conectarse con la base de datos");
define("LANINS_085", "El instalador no puede seleccionar la base de datos:");
define("LANINS_086", "Usuario Admin, Contrase�a Admin y Correo Admin son campos <b>necesarios</b>. Por favor, vuelva a la �ltima p�gina y aseg�rese de escribir la informaci�n correcta.");
define("LANINS_087", "Varios");
define("LANINS_088", "Inicio");
define("LANINS_089", "Descargas");
define("LANINS_090", "Miembros");
define("LANINS_091", "Enviar noticias");
define("LANINS_092", "Contactar");
define("LANINS_093", "Dar permisos a menus privados");
define("LANINS_094", "Ejemplo de clase de foro privada");
define("LANINS_095", "Comprobaci�n integridad");
define("LANINS_096", '�ltimos comentarios'); 
define("LANINS_097", '[m�s ...]'); 
define("LANINS_098", 'Art�culos'); 
define("LANINS_099", 'Art�culos p�gina inicial ...'); 
define("LANINS_100", '�ltimos env�os del foro'); 
define("LANINS_101", 'Actualizar ajustes de men�'); 
define("LANINS_102", 'Fecha / Hora'); 
define("LANINS_103", 'Revisiones'); 
define("LANINS_104", 'Revisar p�gina inicial ...'); 
define("LANINS_105", ''); 
define("LANINS_106", ''); 
?>