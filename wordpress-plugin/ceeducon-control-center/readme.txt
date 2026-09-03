=== CEEDUCON Control Center ===
Contributors: ceeducon
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 1.0.1
License: GPLv2 or later

Jedno místo pro celý web CEEDUCON: texty stránek, údaje ročníku a vizuální editor programu.

== Description ==

Plugin přidává do administrace jedno menu CEEDUCON s pěti obrazovkami: Přehled,
Program, Texty webu, Ročník konference a Zálohy a nástroje.

Nahrazuje pluginy CEEDUCON Conference Edition a CEEDUCON Programme Editor — po
aktivaci se samy vypnou. Data zůstávají na stejném místě v databázi.

== Changelog ==

= 1.0.1 =
* Oprava uloženého programu: „(ESN Czechia)" u tří řečníků se změnilo na
  „(ESN Czech Republic)" podle aktuální tabulky pořadatelů. Proběhne jednou,
  jen tam, kde je text přesně v původním znění, a uloží zálohu.

= 1.0.0 =
* První vydání: sloučení nastavení ročníku, textů webu a editoru programu.
* Program se ukládá jako jeden dokument, takže nenaráží na limit max_input_vars.
* Texty se nabízejí i s původními zástupnými texty, takže se uložením nezmrazí.
* Automatická záloha programu při každém uložení a obnovení jedním tlačítkem.
