<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.9 $
|     $Date: 2006-12-02 15:59:23 $
|     $Author: verant $
+----------------------------------------------------------------------------+
*/

define("LANINS_001", "Установка e107");


define("LANINS_002", "Шаг ");
define("LANINS_003", "1");
define("LANINS_004", "Выбор языка");
define("LANINS_005", "Пожалуйста, выберите язык, чтобы использовать его в течение процедуры установки");
define("LANINS_006", "Выбрать язык");
define("LANINS_007", "4"); // Was "2"
define("LANINS_008", "PHP &amp; MySQL Проверка версий / Проверка разрешений Файлов");
define("LANINS_009", "Перепроверить разрешения файла");
define("LANINS_010", "Файл не перезаписываемый: ");
define("LANINS_010a", "Каталог не перезаписываемый: ");
define("LANINS_011", "Ошибка");
define("LANINS_012", "MySQL функции, похоже, отсутствуют. Это, вероятно, означает, что PHP-расширения MySQL не установлены, либо ваша установка PHP не была скомпилирована с поддержкой MySQL."); // help for 012
define("LANINS_013", "Невозможно определить версию MySQL. Это не фатальная ошибка, поэтому, пожалуйста, продолжайте установку, но имейте в виду, что e107 требует MySQL >= 3.23, чтобы корректно функционировать.");
define("LANINS_014", "Разрешения Файла");
define("LANINS_015", "Версия PHP");
define("LANINS_016", "MySQL");
define("LANINS_017", "ПРОЙДЕНО");
define("LANINS_018", "Пожалуйста, убедитесь, что все перечисленные файлы существуюти и на них открыт права на запись для сервера. Обычно это означает CHMOD = 777, но окружения различаются - проконсультируйтесь с хост-провайдером, если у вас есть какие-либо проблемы.");
define("LANINS_019", "Установлена версия PHP не совместима с e107. Необходима версия PHP 4.3.0 или выше. Либо сделайте апгрейд вашей версии PHP, либо обратитесь к хост-провайдеру, чтобы он сделал апгрейд.");
define("LANINS_020", "Продолжить установку");
define("LANINS_021", "2"); // Was "3"
define("LANINS_022", "Информация о MySQL сервере");
define("LANINS_023", "Пожалуйста, введите настройки своей базы данных.

Если вы обладаете правами root, Вы можете создать новую базу данных, отметив соответствующий чекбокс, если нет, Вы должны создать базу данных или использовать имеющуюся.

Если у вас только одна база данных, используйте префикс так, чтобы другие скрипты могли совместно использовать ту же самую базу данных.
Если вам не известна информация о MySQL сервере, свяжитесь с вашим хост-провайдером.");
define("LANINS_024", "MySQL сервер:");
define("LANINS_025", "MySQL логин:");
define("LANINS_026", "MySQL пароль:");
define("LANINS_027", "MySQL База данных:");
define("LANINS_028", "Создать базу данных?");
define("LANINS_029", "Префикс имен таблиц:");
define("LANINS_030", "Сервер MySQL, предназначенный для использования системой e107. Он так же может включать номер порта. Например: \"hostname:port\" или путь до локального сокета, например: \":/path/to/socket\" на локальном хосте.");
define("LANINS_031", "Имя пользователя, которое e107 будет использовать для соединения с вашим сервером MySQL");
define("LANINS_032", "Пароль, соответствующий имени пользователя, которое вы ввели");
define("LANINS_033", "База данных MySQL, в которой вы хотите, чтобы находилась e107, иногда называемая схемой. Если пользователь обладает правами создания базы данных, Вы можете создать новую базу данных, отметив соответствующий чекбокс.");
define("LANINS_034", "Префикс, который вы можете использовать для создания таблиц e107. Полезно, если у вас только одна база данных и нужно несколько установок e107 на одной базе данных.");
define("LANINS_035", "Продолжить");
define("LANINS_036", "3"); // Was "4"
define("LANINS_037", "Проверка соединения с MySQL");
define("LANINS_038", " и создание БД");
define("LANINS_039", "Пожалуйста, убедитесь, что вы заполнили все поля, наиболее важно: Сервер MySQL, MySQL Имя пользователя и База данных MySQL (Они всегда требуются Сервером MySQL)");
define("LANINS_040", "Ошибки");
define("LANINS_041", "e107 не смогла установить соединение с MySQL используя информацию, которую вы ввели. Пожалуйста, перейдите к предыдущей странице и убедитесь в правильности настроек.");
define("LANINS_042", "Соединение с MySQL установлено и проверено.");
define("LANINS_043", "Невозможно создать базу данных, пожалуйста, убедитесь в том, что у Вас есть права на создание базы данных на сервере.");
define("LANINS_044", "База данных создана успешно.");
define("LANINS_045", "Пожалуйста, нажмите на кнопку, чтобы перейти к следующей стадии установки.");
define("LANINS_046", "5");
define("LANINS_047", "Информация о Главном Администраторе");
define("LANINS_048", "Назад к последнему шагу");
define("LANINS_049", "Пароли не совпадают, пожалуйста, вернитесь и введите ещё раз.");
define("LANINS_050", "XML расширение");
define("LANINS_051", "Установлено");
define("LANINS_052", "Не установлено");
define("LANINS_053", "e107 .700 требует установленного PHP XML расширения. Свяжитесь с Администрацией хостинга, или почитайте об этом ");
define("LANINS_054", " перед тем, как продолжить");
define("LANINS_055", "Подтверждение установки");
define("LANINS_056", "6");
define("LANINS_057", " e107 теперь имеет всю необходимую информацию для завершения установки

Пожалуйста щелкните по кнопке, чтобы создать таблицы базы данных и сохранить все Ваши настройки.

");
define("LANINS_058", "7");
define("LANINS_060", "Невозможно прочитать файл данных sql

Пожалуйста, убедитесь что файл <b>core_sql.php</b> существует в каталоге <b>/e107_admin/sql</b>"); //directory
define("LANINS_061", "e107 не смог создать нужные таблицы в базе данных.
Пожалуйста, очистите базу данных и устраните все проблемы перед тем как попробовать ещё раз.");

define("LANINS_062", "[b]Добро пожаловать на Ваш новый вебсайт![/b]
e107 была успешно установлена и теперь готова для принятия контента.<br />Ваш Админцентр [link=e107_admin/admin.php]находится здесь[/link], нажмите, чтобы перейти туда сейчас. Вам потребуется ввести логин и пароль, которые вы указали в процессе установки.

[b]Поддержка[/b]
Домашняя страница e107: [link=http://e107.org]http://e107.org[/link], здесь вы найдете FAQ и документацию.
Форумы: [link=http://e107.org/e107_plugins/forum/forum.php]http://e107.org/e107_plugins/forum/forum.php[/link]

[b]Загрузки[/b]
Плагины: [link=http://e107coders.org]http://e107coders.org[/link]
Темы: [link=http://e107themes.org]http://e107themes.org[/link]

Спасибо за то, что вы пробуете e107, мы надеемся, что эта система удовлетворит нужды вашего вебсайта.
(Вы можете удалить это сообщение, войдя в область администрирования.)");

define("LANINS_063", "Добро пожаловать в e107");

//define("LANINS_064", "Секция администрирования");
//define("LANINS_065", "находится здесь");
//define("LANINS_066", "щёлкните, чтобы перейти туда сейчас. Вы должны войти, используя имя и пароль, которые вы ввели во время установки.");
//define("LANINS_067", "там Вы сможете найти документацию и информацию по использованию Вашего нового сайта.");
//define("LANINS_068", "Спасибо, что вы воспользовались e107, мы надеемся, она полностью удовлетворит потребности вашего сайта.\n(Вы можете удалить это сообщение из секции администратора.)");

define("LANINS_069", "e107 успешно установлена!

Из соображений безопасности вы должны сейчас установить права доступа к файлу<br /><b>e107_config.php</b> равными 644.

Также удалите файл install.php с Вашего сервера после того, как Вы нажмёте на кнопку внизу.");
define("LANINS_070", "e107 не смогла сохранить главный файл конфигурации на ваш сервер.

Пожалуйста, убедитесь что у файла <b>e107_config.php</b> установлены корректные права доступа");
define("LANINS_071", "Завершение установки");

define("LANINS_072", "Логин администратора");
define("LANINS_073", "Это логин, который вы будете использовать для доступа на сайт. Если вы желаете, можете использовать этот логин и в качестве вашего отображаемого имени");
define("LANINS_074", "Отображаемое имя Администратора");
define("LANINS_075", "Это имя будет видно пользователям в сообщениях, на форуме и т.п. Если вы хотите использовать то же имя, что и логин, оставьте это поле пустым.");
define("LANINS_076", "Пароль Администратора");
define("LANINS_077", "Введите пароль, с которым вы будете входить в систему");
define("LANINS_078", "Подтверждение пароля");
define("LANINS_079", "Напечатайте пароль Администратора еще раз для подтверждения");
define("LANINS_080", "Email Администратора");
define("LANINS_081", "Введите ваш email-адрес");

define("LANINS_082", "user@yoursite.com");

// Better table creation error reporting
define("LANINS_083", "Ошибки, сообщенные MySQL:");
define("LANINS_084", "Инсталлятор не может установить соединение с базой данных");
define("LANINS_085", "Инсталлятор не может выбрать базу данных:");
define("LANINS_086", "<b>Требуется</b> заполнить поля: Имя, Пароль и Адрес электронной почты администратора. Пожалуйста, вернитесь на предыдущую страницу и убедитесь, что данные введены корректно.");

?>