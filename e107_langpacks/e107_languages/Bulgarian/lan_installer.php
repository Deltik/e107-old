<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File
|     Bulgarian Language Pack for e107 Version 0.7
|     Copyright © 2005 - Bulgarian e107
|     http://www.e107bg.org
|     Encoding: utf-8
|
|     $Source: /cvsroot/e107/e107_langpacks/e107_languages/Bulgarian/lan_installer.php,v $
|     $Revision: 1.2 $
|     $Date: 2005/07/19 19:46:15 $
|     $Author: secretr $
+----------------------------------------------------------------------------+
*/
define("LANINS_001", "e107 Инсталиране");


define("LANINS_002", "Етап ");
define("LANINS_003", "1");
define("LANINS_004", "Избор на език");
define("LANINS_005", "Моля изберете език за инсталацията");
define("LANINS_006", "Избор на език");
define("LANINS_007", "2");
define("LANINS_008", "Проверка версия на PHP &amp; MySQL  / Проверка правата на файловете");
define("LANINS_009", "Провери отново правата");
define("LANINS_010", "Файлове, нямащи права за писане: ");
define("LANINS_010a", "Папки, нямащи права за писане: ");
define("LANINS_011", "Грешка");
define("LANINS_012", "Не могат да бъдат намерени MySQL Функции. Това вероятно означава, че или не е инсталиран MySQL PHP модулът, или не е конфигуриран правилно."); // help for 012
define("LANINS_013", "Невъзможно е определянето на номера на версията на вашия MySQL сървър. Това може да означава, че вашия MySQL сървър не функционира или отказва създаването на връзка с него.");
define("LANINS_014", "Права на файлове");
define("LANINS_015", "PHP версия");
define("LANINS_016", "MySQL");
define("LANINS_017", "ОК");
define("LANINS_018", "Убедете се, че всички изброени файлове съществуват и са с права за писане от сървъра. Това обикновено изисква CHMOD-ването им с 777, но сървър обкръжението варира - свържете се с вашия хост администратор ако срещнете проблеми.");
define("LANINS_019", "Версията на PHP инсталирана на вашия сървър не е подходяща за работа с e107. e107 изисква PHP минимум версия 4.3.0 за да работи коректно. Актуализирайте версията на PHP или се свържете с вашата хостинг компания за актуализация.");
define("LANINS_020", "Продължение на инсталацията");
define("LANINS_021", "3");
define("LANINS_022", "MySQL Сървър Информация");
define("LANINS_023", "Моля въведете настройките за вашия MySQL сървър.

Ако имате root права, можете да създадете нова база с избирането на отметката, в противен случай трябва предварително да създадете база или да използвате вече съществуваща.

Ако притежавате само една база, използвайте префикс, така че други скриптове да могат да използват същата база без това да доведе до проблеми.
Ако нямате информация за вашия MySQL съръвр, свържете се с вашата хостинг компания.");
define("LANINS_024", "MySQL Сървър:");
define("LANINS_025", "MySQL Потребителско име:");
define("LANINS_026", "MySQL Парола:");
define("LANINS_027", "MySQL База:");
define("LANINS_028", "Създаване на база?");
define("LANINS_029", "Префикс за таблиците:");
define("LANINS_030", "MySQL сървърът, който искате е107 да ползва. Това може да включва също така и номер на порт. Напр. \"име:порт\" или път до локален socket напр. \":/път/до/socket\" за localhost.");
define("LANINS_031", "Потребителското име, което искате да използва e107 за връзка с вашия MySQL сървър");
define("LANINS_032", "Паролата за потребителя, който сте въвели");
define("LANINS_033", "MySQL базата, в която искате да бъде записан e107. Ако потребителското име права за създаване на база. можете да ползвате опцията за автоматично създаване на базата ако тя не съществува.");
define("LANINS_034", "Префиксът, който искате да ползва e107 при създаването на своите таблици. Удобно е за многократно инсталиране на е107 в една и съща база данни.");
define("LANINS_035", "Нататък");
define("LANINS_036", "4");
define("LANINS_037", "Проверка на връзката с MySQL");
define("LANINS_038", " и създаване на информационната база");
define("LANINS_039", "Моля уверете се, че сте попълнили всички полета и най-вече - MySQL Сървър, MySQL Потребителско име и MySQL База (Задължително искани от MySQL Сървъра)");
define("LANINS_040", "Грешки");
define("LANINS_041", "e107 не можа да установи връзка с MySQL сървъра, използвайки информацията, която въведохте. Моля върнете се обратно и се уверете, че информацията е коректна.");
define("LANINS_042", "Връзката с MySQL сървъра е установена и проверена.");
define("LANINS_043", "Невъзможно е създаването на база, моля убедете се, че имате права за създаване на база на вашия сървър.");
define("LANINS_044", "Базата е създадена успешно.");
define("LANINS_045", "Моля използвайте бутона за да продължите към следващия етап.");
define("LANINS_046", "5");
define("LANINS_047", "Административна Информация");
define("LANINS_048", "Върни се обратно на последната стъпка");
define("LANINS_049", "Двете пароли, които въведохте не съвпадат. Моля върнете се обратно и опитайте отново.");
define("LANINS_050", "XML Модул");
define("LANINS_051", "Инсталирано");
define("LANINS_052", "Не е инсталирано");
define("LANINS_053", "e107 0.7.х изисква инсталиран PHP XML модул. Моля свържете се с вашата хостинг компания или прочетете информацията на ");
define("LANINS_054", " преди да продължите");
define("LANINS_055", "Потвърждение за инсталиране");
define("LANINS_056", "6");
define("LANINS_057", " e107 вече има цялата необходима информация за завършване на инсталацията.

Моля използвайте бутона за създаване на таблиците и за запазване на всички ваши настройки.

");
define("LANINS_058", "7");
define("LANINS_060", "Невъзможно е прочитането на sql файлът

Моля убедете се, че файлът <b>core_sql.php</b> съществува в директорията <b>/e107_admin/sql</b>.");
define("LANINS_061", "e107 не успя да създаде всички необходими таблици.
Моля изчистете базата и коригирайте всички проблеми преди да опитате отново.");
define("LANINS_062", "Добре дошли на вашия нов уебсайт!
e107 беше успешно инсталиран и е готов за записване на информация.
Вашата административна секция [link=e107_admin/admin.php]се намира тук[/link], кликнете за да отидете там. Необходимо е да се логнете като използвате името и паролата, които въведохте по време на инсталацията.

[b]Поддръжка[/b]
е107 България: На [link=http://e107bg.org]http://e107bg.org[/link] можете да зададете въпроси относно ползването на е107, теми, модули и т.н.
е107 DEV България: На [link=http://dev.e107bg.org]http://dev.e107bg.org[/link] можете да получите техническа информация и да се включите в разработката на е107 за България.
e107 : [link=http://e107.org]http://e107.org[/link] - е107 на английски - оригиналната версия на английски.
Общност на разработчиците на е107 модули: [link=http://www.e107coders.org]http://e107coders.org[/link]

[b]Файлове[/b]
Модули: [link=http://e107coders.org]http://e107coders.org[/link]
Теми: [link=http://e107styles.org]http://e107styles.org[/link] | [link=http://e107themes.org]http://e107themes.org[/link]

Благодарим ви че използвате e107, надяваме се, че тази портална система ще задоволи нуждите ви за уебсайт.
(Можете да изтриете това съобщение от Администрация)");
define("LANINS_063", "e107 беше успешно инсталиран");

define("LANINS_069", "e107 беше успешно инсталирана!

От съображения за сигурност е необходимо да зададете правата на файла <b>e107_config.php</b> отново на 644.

Освен това е необходимо изтриването на install.php и директория e107_install от вашия сървър след използването на бутона по-долу.
");
define("LANINS_070", "e107 не можа да запише основния конфигурационен файл на вашия сървър.

Моля убедете се, че файлът <b>e107_config.php</b> има необходимите права");
define("LANINS_071", "Завършване на инсталацията");

define("LANINS_072", "Администраторско Потребителско Име");
define("LANINS_073", "Това е името, което ще ползвате за да се логвате във вашия сайт. Ако желаете това може да бъде и името, което ще бъде показвано.");
define("LANINS_074", "Админ Име за показване");
define("LANINS_075", "Това е името, което искате да бъде показвано на потребителите на сайта във вашия профил, форумите и т.н. Ако искате да ползвате същото име като вашето логин име, оставете това поле празно.");
define("LANINS_076", "Администраторска парола");
define("LANINS_077", "Моля въведете тук администраторската парола, която искате да ползвате.");
define("LANINS_078", "Потвърждаване на Администраторска парола");
define("LANINS_079", "Моля въведете отново администраторската парола за потвърждение.");
define("LANINS_080", "Администраторски имейл");
define("LANINS_081", "Въведете вашия имейл адрес");

define("LANINS_082", "потребител@вашия_сайт.com");

// Better table creation error reporting
define("LANINS_083", "MySQL Грешка:");
define("LANINS_084", "Невъзможно е създаване на връзка с базата данни");
define("LANINS_085", "Невъзможно е избирането на база:");

define("LANINS_086", "Администраторско име, Администраторска парола и Администраторска Имейл са <b>задължителни</b> полета. Моля върнете се обратно и се убедете че информацията е попълнена коректно.");

define("LANINS_087", "Други");
define("LANINS_088", "Начало");
define("LANINS_089", "Файлове");
define("LANINS_090", "Потребители");
define("LANINS_091", "Предложи новина");
define("LANINS_092", "Контакт");
define("LANINS_093", "Задаване права за лично меню");
define("LANINS_094", "Примерен форум клас");
define("LANINS_095", "Проверка на системата");

define("LANINS_096", 'Последни коментари');
define("LANINS_097", '[още ...]');
define("LANINS_098", 'Статии');
define("LANINS_099", 'Страница коментари ...');
define("LANINS_100", 'Последно от форумите');
define("LANINS_101", 'Актуализиране настройките на менюта');
define("LANINS_102", 'Дата / Време');
define("LANINS_103", 'Преглед');
define("LANINS_104", 'Преглед заглавна страница ...');

define("LANINS_105", 'A database name or prefix beginning with some digits followed by “e” or “E” is not acceptable.  <br />A database name or prefix can not be empty.');
define("LANINS_106", 'WARNING - E107 cannot write to the directories and/or files listed. While this will not stop E107 installing, it will mean that certain features are not available. 
				You will need to change the file permissions to use these features');

// for v0.7.16+ only
define('LANINS_DB_UTF8_CAPTION', 'MySQL Charset:');
define('LANINS_DB_UTF8_LABEL',   'Force UTF-8 Database?');
define('LANINS_DB_UTF8_TOOLTIP', 'If set, the installation script will make the database UTF-8 compatible if possible. UTF-8 Database are required for the next major e107 version.');
?>