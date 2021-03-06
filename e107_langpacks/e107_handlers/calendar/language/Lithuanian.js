// ** I18N

// Calendar LT language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Sekmadienis",
 "Pirmadienis",
 "Antradienis",
 "Trečiadienis",
 "Ketvirtadienis",
 "Penktadienis",
 "Šeštadienis",
 "Sekmadienis");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Sek",
 "Pir",
 "Ant",
 "Tre",
 "Ket",
 "Pen",
 "Šeš",
 "Sek");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 1;

// full month names
Calendar._MN = new Array
("Sausis",
 "Vasaris",
 "Kovas",
 "Balandis",
 "Gegužė",
 "Birželis",
 "Liepa",
 "Rugpjūtis",
 "Rugsėjis",
 "Spalis",
 "Lapkritis",
 "Gruodis");

// short month names
Calendar._SMN = new Array
("Sau",
 "Vas",
 "Kov",
 "Bal",
 "Geg",
 "Bir",
 "Lie",
 "Rgp",
 "Rgs",
 "Spa",
 "Lap",
 "Gru");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Apie kalendorių";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"Naujausia versija: http://www.dynarch.com/projects/calendar/\n" +
"Platinama pagal GNU LGPL. Išsamiau žiūrėkite http://gnu.org/licenses/lgpl.html ." +
"\n\n" +
"Datos pasirinkimas:\n" +
"- Naudokite \xab, \xbb mygtukus, kad pasirinktumėte metus\n" +
"- Naudokite " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " mygtukus, kad pasirinktumėte mėnesius\n" +
"- Laikyte nuspaudę pelės mygtuką ant bet kokio viršuje mygtuko greitesniam pasirinkimui.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Laiko pasirinkimas:\n" +
"- Paspauskite ant bet kokio laiko intervalo, kad padidintumėte jį\n" +
"- arba naudokite Shift sumažinti jį\n" +
"- arba nuspauskite ir tempkite greitesniam pasirinkimui.";

Calendar._TT["PREV_YEAR"] = "Ankst. metai (laikykite meniu)";
Calendar._TT["PREV_MONTH"] = "Ankst. mėnuo (laikykite meniu)";
Calendar._TT["GO_TODAY"] = "Eiti į šiandieną";
Calendar._TT["NEXT_MONTH"] = "Sek. mėnuo (laikykite meniu)";
Calendar._TT["NEXT_YEAR"] = "Sek. metai (laikykite meniu)";
Calendar._TT["SEL_DATE"] = "Pasirinkite datą";
Calendar._TT["DRAG_TO_MOVE"] = "Tempkite norėdami judinti";
Calendar._TT["PART_TODAY"] = " (šiandien)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Rodyti %s pirma";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "1,7";

Calendar._TT["CLOSE"] = "Uždaryti";
Calendar._TT["TODAY"] = "Šiandien";
Calendar._TT["TIME_PART"] = "(Shift-)spauskite arba tempkite reikšmės pakeitimui";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%G %i";

Calendar._TT["WK"] = "sav.";
Calendar._TT["TIME"] = "Laikas:";
