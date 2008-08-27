<?php
/*
+-----------------------------------------------------------------------------+
|     e107 website system - Language File.
+-----------------------------------------------------------------------------+
|     Spolszczenie systemu e107 v0.7
|     Polskie wsparcie: http://e107.org.pl - http://e107poland.org
|
|     $Revision: 1.14 $
|     $Date: 2008-08-27 11:58:15 $
|     $Author: marcelis_pl $
|     $Source: /cvs_backup/e107_langpacks/e107_plugins/content/languages/Polish/lan_content_help.php,v $
+-----------------------------------------------------------------------------+
|     Zgodne z: /e107_plugins/content/languages/English/lan_content_help.php rev. 1.16
+-----------------------------------------------------------------------------+
*/

define("CONTENT_ADMIN_HELP_1", "Zarządzanie publikacjami");

define("CONTENT_ADMIN_HELP_ITEM_1", "<i>Jeśli jeszcze nie dodałeś działów głównych kategorii, proszę to uczynić poprzez stronę <a href='".e_SELF."?cat.create'>tworzenia nowych kategorii publikacji</a>.</i><br /><br /><b>Kategoria</b><br />Wybierz kategorię z rozwijalnej listy menu, aby zarządzać umieszczonymi w niej publikacjami.<br /><br />Wybranie głównego działu spowoduje wyświetlenie wszystkich publikacji dla wskazanej kategorii głównej.<br />Wybranie podkategorii spowoduje wyświetlenie tylko publikacji należących do określonej podkategorii.<br /><br />Możesz również użyć menu znajdującego się po prawej stronie, aby wyświetlić wszystkie publikacje dla określonej kategorii.");

define("CONTENT_ADMIN_HELP_ITEM_2", "<b>Indeks alfabetyczny</b><br />Jeśli w wybranej kategorii są dostępne publikacje, których tematy rozpoczynają się od różnych liter, zobaczysz przyciski do wyboru publikacji rozpoczynających się tylko od danej litery. Wybranie przycisku 'ALL' spowoduje wyświetlenie listy wszystkich publikacji z danej kategorii.<br /><br /><b>Szczegółowy wykaz</b><br />Na stronie są wyświetlone wszystkie publikacje wraz z ich ID, ikoną, autorem, tematem [podtematem] oraz opcjami.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do publikacji<br />".CONTENT_ICON_EDIT." : edycja publikacji<br />".CONTENT_ICON_DELETE." : usunięcie publikacji<br />");

define("CONTENT_ADMIN_HELP_ITEMEDIT_1", "<b>Formularz edycji</b><br />Z poziomu tej strony możesz redagować wszystkie informacje dla wybranej publikacji, a następnie po wprowadzeniu zmian wysłać je na serwer.<br /><br />Jeśli potrzebujesz zmienić kategorię dla tej publikacji, proszę to zrobić przed wprowadzaniem jakichkolwiek zmian. Gdy już wybierzesz odpowiednią kategorię, zmień lub dodaj określone informacje, a następnie zaktualizuj publikację wysyłając wprowadzone zmiany.");

define("CONTENT_ADMIN_HELP_ITEMCREATE_1", "<b>Kategoria</b><br />Proszę wybrać kategorię z listy wyboru w celu dodania do niej nowej publikacji.<br />");

define("CONTENT_ADMIN_HELP_ITEMCREATE_2", "<b>Formularz tworzenia</b><br />Z poziomu tej strony możesz wprowadzić wszystkie informacje dla tworzonej publikacji, a następnie je wysłać.<br /><br />Bądź jednak świadom tego, że poszczególne działy głównych kategorii mogą mieć różne preferencje oraz odmienne pola dla wprowadzanych danych. Dlatego zawsze przed wprowadzeniem danych do określonych pól musisz najpierw wybrać odpowiednią kategorię dla tworzonej publikacji!");

define("CONTENT_ADMIN_HELP_CAT_1", "<i>Na tej stronie są wyświetlone wszystkie kategorie oraz podkategorie.</i><br /><br /><b>Szczegółowy wykaz</b><br />Na stronie są wyświetlone wszystkie kategorie oraz podkategorie wraz z ich ID, ikoną, autorem, kategorią [podtytułem] oraz opcjami.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do kategorii<br />".CONTENT_ICON_EDIT." : edycja kategorii<br />".CONTENT_ICON_DELETE." : usunięcie kategorii<br />");

define("CONTENT_ADMIN_HELP_CAT_2", "<i>Ta strona umożliwi Ci dodanie nowej kategorii.</i><br /><br />Zawsze wybieraj dział kategorii przed wypełnieniem pół danymi!<br /><br />Musisz to wykonać, ponieważ niektóre unikalne preferencje kategorii muszą być załadowane do systemu.<br /><br />Domyślnie strona kategorii jest wyświetlana do utworzenia nowej kategorii głównej.");

define("CONTENT_ADMIN_HELP_CAT_3", "<i>Na tej stronie znajduje się formularz do edycji kategorii.</i><br /><br /><b>Formularz edycji kategorii</b><br />Z poziomu tej strony możesz redagować wszystkie informacje dla wybranej (pod)kategorii, a następnie po wprowadzeniu zmian wysłać je na serwer.<br /><br />Jeśli chcesz zmienić lokalizację dla wybranej kategorii, proszę to zrobić przed wprowadzaniem jakichkolwiek zmian. Gdy już ustawisz odpowiednią kategorię, zredaguj wszystkie pozostałe pola.");

define("CONTENT_ADMIN_HELP_ORDER_1", "<i>Na tej stronie są wyświetlone wszystkie bieżące kategorie oraz podkategorie.</i><br /><br /><b>Szczegółowy wykaz</b><br />Na stronie są wyświetlone numery ID oraz nazwy kategorii. a także jest wyświetlonych kilka opcji do zarządzania kolejnością kategorii.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do kategorii<br />".CONTENT_ICON_ORDERALL." : zarządzanie globalną kolejnością publikacji bez względu na kategorie.<br />".CONTENT_ICON_ORDERCAT." : zarządzanie kolejnością publikacji w określonej kategorii.<br />".CONTENT_ICON_ORDER_UP." : przycisk ze strzałką do góry pozwoli Ci przesunąć daną kategorię o jedną pozycje w górę.<br />".CONTENT_ICON_ORDER_DOWN." : przycisk ze strzałką w dół pozwoli Ci przesunąć daną kategorię o jedną pozycje w dół.<br /><br /><b>Kolejność</b><br />Z poziomu tej strony możesz ręcznie ustawić kolejność wszystkich kategorii w danym dziale. Aby tego dokonać, zmień wartość w polach wyboru na kolejność jaką chcesz i naciśnij przycisk 'Zaktualizuj kolejność', aby zapisać wprowadzone zmiany.<br />");

define("CONTENT_ADMIN_HELP_ORDER_2", "<i>Na tej stronie są wyświetlone wszystkie publikacje dla wybranej kategorii.</i><br /><br /><b>Szczegółowy wykaz</b><br />Na stronie są wyświetlone numery ID publikacji, autorzy publikacji oraz temat publikacji. a także jest wyświetlonych kilka opcji do zarządzania kolejnością publikacji.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do publikacji<br />".CONTENT_ICON_ORDER_UP." : przycisk ze strzałką do góry pozwoli Ci przesunąć daną publikację o jedną pozycje w górę.<br />".CONTENT_ICON_ORDER_DOWN." : przycisk ze strzałką w dół pozwoli Ci przesunąć daną publikację o jedną pozycje w dół.<br /><br /><b>Kolejność</b><br />Z poziomu tej strony możesz ręcznie ustawić kolejność wszystkich publikacji w danej kategorii. Aby tego dokonać, zmień wartość w polach wyboru na kolejność jaką chcesz i naciśnij przycisk 'Zaktualizuj kolejność', aby zapisać wprowadzone zmiany.<br />");

define("CONTENT_ADMIN_HELP_ORDER_3", "<i>Na tej stronie są wyświetlone wszystkie publikacje dla wybranego głównego działu kategorii.</i><br /><br /><b>Szczegółowy wykaz</b><br />Na stronie są wyświetlone numery ID publikacji, autorzy publikacji oraz temat publikacji. a także jest wyświetlonych kilka opcji do zarządzania kolejnością publikacji.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do publikacji<br />".CONTENT_ICON_ORDER_UP." : przycisk ze strzałką do góry pozwoli Ci przesunąć daną publikację o jedną pozycje w górę.<br />".CONTENT_ICON_ORDER_DOWN." : przycisk ze strzałką w dół pozwoli Ci przesunąć daną publikację o jedną pozycje w dół.<br /><br /><b>Kolejność</b><br />Z poziomu tej strony możesz ręcznie ustawić kolejność wszystkich publikacji w danym dziale głównym. Aby tego dokonać, zmień wartość w polach wyboru na kolejność jaką chcesz i naciśnij przycisk 'Zaktualizuj kolejność', aby zapisać wprowadzone zmiany.<br />");

define("CONTENT_ADMIN_HELP_OPTION_1", "Na tej stronie możesz wybrać dział głównej kategorii, dla którego następnie będziesz mógł ustawić opcje, albo możesz wybrać również edycję domyślnych preferencji.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do kategorii<br />".CONTENT_ICON_OPTIONS." : edycja opcji<br /><br /><br />
Domyślne preferencje są tylko stosowane podczas tworzenia nowego głównego działu. Tak więc gdy tworzysz nowy główny dział, domyślne preferencje zostają zapisane do jego pamięci. Możesz je zmienić, aby się upewnić, że niedawno co utworzony główny dział posiada już ustawione określone opcje.
<br /><br />
Poszczególne działy główne mają własne ustawienia opcji, które są unikalne dla określonych głównych działów kategorii.<br /><br />
<b>Dziedziczenie</b><br />Pole wyboru opcji - dziedziczenie - umożliwi Ci pominięcie indywidualnych opcji działów głównych, a dokładniej zastosuje domyślne preferencje dla zaznaczonych działów.");

//define("CONTENT_ADMIN_HELP_OPTION_2", "<i>this page shows the options you can set for this main parent. Each main parent has their own specific set of options, so be sure to set them all correctly.</i><br /><br />");
//<b>default values</b><br />By default all values are present and already updated in the preferences when you view this page, but change any setting to your own standards.<br /><br />

define("CONTENT_ADMIN_HELP_MANAGER_1", "Na tej stronie wyświetlone są wszystkie kategorie. Możesz administrować 'osobistymi menedżerami publikacji' poszczególnych kategorii poprzez kliknięcie w odpowiednią ikonę.<br /><br /><b>Objaśnienie ikon</b><br />".CONTENT_ICON_USER." : informacje o autorze<br />".CONTENT_ICON_LINK." : link do kategorii<br />".CONTENT_ICON_CONTENTMANAGER_SMALL." : edycja osobistego menedżera publikacji<br />");

define("CONTENT_ADMIN_HELP_MANAGER_2", "<i>Na tej stronie możesz przydzielić określoną grupę użytkowników do wybranej kategorii.</i><br /><br /><b>Osobisty menedżer</b><br />Z poziomu tej strony możesz zdefiniować grupy użytkowników dla różnego typu osobistych menedżerów, obecnie są trzy typy menedżerów, które możesz określić. <br /><br />Zatwierdzanie publikacji: użytkownicy z tej grupy będą w stanie zatwierdzić nadesłane publikacje<br /><br />Osobisty menedżer: użytkownicy z tej grupy będą w stanie zarządzać tylko swoimi własnymi publikacjami<br /><br />Menedżer kategorii: użytkownicy z tej grupy będą w stanie zarządzać wszystkimi publikacjami w danej kategorii<br />");

define("CONTENT_ADMIN_HELP_SUBMIT_1", "<i>Na tej stronie wyświetlona jest lista wszystkich publikacji nadesłanych w ostatnim czasie przez użytkowników.</i><br /><br /><b>Szczegółowy wykaz</b><br />Na stronie są wyświetlone wszystkie nadesłane publikacje wraz z ich numerem ID, ikoną, działem głównym, tematem [podtematem], autorem oraz opcjami.<br /><br /><b>Opcje</b><br />Możesz opublikować lub usunąć daną publikację używając pokazanych przycisków.");



define("CONTENT_ADMIN_HELP_OPTION_DIV_1", "Ta strona umożliwi Ci ustawienie opcji dla strony tworzenia nowych publikacji.<br /><br />Możesz zdefiniować, które sekcje będą dostępne, kiedy administrator (lub menedżer osobistych publikacji) będzie tworzył nową publikację.<br /><br /><b>Własne tagi danych</b><br />Poprzez użycie własnych tagów danych możesz zezwolić użytkownikom lub administratorom na dodawanie opcjonalnych pól dla danej publikacji. Opcjonalne pola są parą identyfikatora (key)=>wartości (value). Na przykład: Mógłbyś dodać pole identyfikatora nazwane 'fotograf' i określić wartość pola przy pomocy wyrażenia 'wszystkie zdjęcia zostały wykonane przze mnie'. Zarówno identyfikator, jak i pole wartości są pustymi polami tekstowymi i zostaną one wyświetlone w formularzu tworzenia.<br /><br /><b>Wstępne ustawienie tagów danych</b><br />Oprócz własnych tagów danych możesz określić wstępnie ustawione tagi danych. Różnica jest taka, że w wstępnie ustawionych tagach danych, pole identyfikatora jest już podane i użytkownik musi tylko określić pole wartości dla wstępnego ustawienia. Na podstawie tego samego przykładu jak powyżej 'fotograf' może już być wcześniej zdefiniowany, a użytkownik będzie musiał tylko dostarczyć 'wszystkie zdjęcia zostały wykonane przze mnie'. Możesz wybrać rodzaj elementu poprzez wybór jednej z opcji z listy wyboru. W okienku, które się otworzy, możesz dostarczyć wszystkie informacje dla wstępnego ustawienia wybranego tagu danych.<br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_2", "Opcje nadsyłania publikacji są tylko stosowane dla formularza nadsyłania publikacji przez użytkowników.<br /><br />Z poziomu tej strony możesz określić, które sekcje będą dostępne dla użytkowników wysyłających publikacje.<br /><br />".CONTENT_ADMIN_OPT_LAN_11.":<br />".CONTENT_ADMIN_OPT_LAN_12."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_3", "Na stronie opcji ścieżek dostępu i wyglądu możesz określić lokalizację przechowywania obrazków oraz plików.<br /><br />Z poziomu tej strony możesz również określić, który temat zostanie zastosowany przez wskazany główny dział. Możesz utworzyć dodatkowy temat wyglądu poprzez skopiowanie (oraz zmianę nazwy) całego foldera 'default' znajdującego się wewnątrz katalogu 'templates'.<br /><br />Możesz tutaj również określić domyślny schemat rozmieszczenia dla nowych publikacji. Nowy schemat rozmieszczenia możesz wykonać poprzez utworzenie pliku <i>content_content _template_XXX.php</i> wewnątrz katalogu 'templates/default'. Schematy rozmieszczenia mogą zostać wykorzystane do nadania odmiennego układu rozmieszczenia każdej publikacji z określonego działu głównego.<br /><br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_4", "Opcje ogólne stanowią zbiór opcji stosowanych na wszystkich stronach publikacji plugina Zarządzanie publikacjami.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_5", "");

define("CONTENT_ADMIN_HELP_OPTION_DIV_6", "Opcje na tej stronie są używane przez 'menu' określonego działu głównego, jeśli zostało ono aktywowane.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."<br /><br />".CONTENT_ADMIN_OPT_LAN_118.":<br />".CONTENT_ADMIN_OPT_LAN_119."<br /><br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_7", "Opcje podglądu publikacji mają zastosowanie tylko dla niepełnego podglądu, który jest generowany dla poszczególnych publikacji.<br /><br />Podgląd ten jest dostępny tylko na kilku stronach, jak na przykład strona ostatnio dodanych publikacji. Jest on również generowany dla wyświetlanych publikacji na stronach kategorii oraz stronach poszczególnych autorów.<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_8", "Strona kategorii wyświetla informacje na temat kategorii publikacji z określonego działu głównego.<br /><br />Są obecne dwa odmienne obszary:<br /><br />Strona wszystkich kategorii:<br />Ta strona wyświetla wszystkie kategorie z określonego działu głównego.<br /><br />Strona przeglądanej kategorii:<br />Ta strona wyświetla publikacje danej kategorii, opcjonalnie podkategorie z określonej kategorii oraz publikacje z danej kategorii lub kilku kategorii.<br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_9", "Strona publikacji służy do wyświetlania określonych pozycji publikacji.<br /><br />Możesz określić sekcje, które zostaną wyświetlone, poprzez zaznaczenie (odznaczenie) określonych pól wyboru.<br /><br />Możesz tutaj również włączyć wyświetlanie adresu email niezarejestrowanych autorów.<br /><br />Z poziomu tej strony możesz również zdeaktywować ikony: email, drukuj, pdf; system ocen oraz komentarzy.<br /><br />".CONTENT_ADMIN_OPT_LAN_74."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_10", "Strona autorów wyświetla listę wszystkich unikalnych autorów publikacji z określonego działu głównego.<br /><br />Możesz określić sekcje, które zostaną wyświetlone, poprzez zaznaczenie (odznaczenie) określonych pól wyboru.<br /><br />Z poziomu tej strony możesz również ograniczyć ilość publikacji wyświetlanych na pojedynczej stronie.<br />");

define("CONTENT_ADMIN_HELP_OPTION_DIV_11", "Strona archiwum wyświetla wszystkie publikacje z określonego działu głównego.<br /><br />Możesz określić sekcje, które zostaną wyświetlone, poprzez zaznaczenie (odznaczenie) określonych pól wyboru.<br /><br />Możesz tutaj również włączyć wyświetlanie adresu email niezarejestrowanych autorów.<br /><br />Z poziomu tej strony możesz również ograniczyć ilość publikacji wyświetlanych na pojedynczej stronie.<br /><br />".CONTENT_ADMIN_OPT_LAN_66."<br /><br />".CONTENT_ADMIN_OPT_LAN_68."");

define("CONTENT_ADMIN_HELP_OPTION_DIV_12", "Strona Lista Top : Najlepiej ocenione wyświetla wszystkie publikacje, które zostały ocenione przez użytkowników.<br /><br />Możesz określić sekcje, które zostaną wyświetlone, poprzez zaznaczenie (odznaczenie) określonych pól wyboru.<br /><br />Z poziomu tej strony możesz również określić czy adresy email niezarejestrowanych autorów mają zostać wyświetlone.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_13", "Strona Lista Top : Najlepsze wyniki wyświetla wszystkie publikacje, które mają przyznany wynik przez autora danej publikacji.<br /><br />Możesz określić sekcje, które zostaną wyświetlone, poprzez zaznaczenie (odznaczenie) określonych pól wyboru.<br /><br />Z poziomu tej strony możesz również określić czy adresy email niezarejestrowanych autorów mają zostać wyświetlone.");

define("CONTENT_ADMIN_HELP_OPTION_DIV_14", "Ta strona umożliwi Ci ustawienie opcji dla strony administracyjnej tworzenia kategorii.<br /><br />Możesz określić sekcje, które będą dostępne, kiedy administrator (lub menedżer osobistych publikacji) będzie tworzył nową kategorię publikacji.");

?>
