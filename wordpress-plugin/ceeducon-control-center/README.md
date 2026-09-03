# CEEDUCON Control Center

Jeden plugin pro celý web CEEDUCON. Nahrazuje dvojici starších pluginů
(*CEEDUCON Conference Edition* a *CEEDUCON Programme Editor*) a schovává surovou
obrazovku „CEEDUCON Content", kterou přidává šablona.

## Co je uvnitř

| Obrazovka | K čemu je |
| --- | --- |
| **Přehled** | Kolik má program přednášek, co ještě chybí, seznam zástupných textů s aktuálními hodnotami. |
| **Program** | Vizuální editor programu — dny, časové bloky, přednášky, sály, témata, typy a formáty. |
| **Texty webu** | Všechny viditelné texty stránek, rozdělené podle stránek a sekcí. |
| **Ročník konference** | Údaje, které se mění každý rok: termín, místo, vstupné, registrace, čísla, obrázky. |
| **Zálohy a nástroje** | Stažení a obnovení celého nastavení, program v JSON. |

## Kde se ukládá

Plugin používá **stejná místa v databázi** jako předchozí pluginy i šablona, takže
při přechodu se nic nepřenáší a nic se neztratí:

- `ceeducon_content` — texty webu a program (klíč `programme_json`)
- `ceeducon_event_settings` — nastavení ročníku
- `ceeducon_cc_programme_backup` — poslední záloha programu (vzniká sama při ukládání)

## Instalace

1. **Pluginy → Přidat nový → Nahrát plugin** a nahrajte `ceeducon-control-center.zip`.
2. Aktivujte. Starší dvojice pluginů se sama vypne a objeví se o tom hláška.
3. V levém menu přibude **CEEDUCON**.

Odinstalace se dá vrátit zpět: plugin deaktivujte a znovu zapněte starší dvojici —
data zůstávají na stejném místě.

## Proč vznikl

Program se dřív ukládal jako formulář s jedním polem na každou hodnotu. U dvou dnů,
devíti sálů a sedmdesáti tří přednášek to je přes tisíc polí a PHP jich ve výchozím
nastavení přijme právě tisíc (`max_input_vars`) — zbytek zahodí bez varování. Tady
odchází celý program jako jeden JSON, takže na limit nenarazí.

Druhá věc: stará obrazovka textů ukazovala u nevyplněných polí už dosazené hodnoty.
Jedno uložení tak zapsalo „1–2 December 2026" natvrdo místo `{{date}}` a příští ročník
se tyto texty přestaly aktualizovat. Tady se v poli vždy ukazuje původní znění se
zástupnými texty a náhled se dá zapnout přepínačem.

## Vývoj

Bez build kroku — čisté PHP, CSS a JavaScript. Soubory:

```
ceeducon-control-center.php   menu, načtení, vypnutí nahrazených pluginů
includes/edition.php          ročník: hodnoty, tokeny, filtry pro šablonu, ukládání
includes/content.php          texty: čtení seznamu polí ze šablony, ukládání
includes/programme.php        program: načtení, kontrola, úklid dat, ukládání
includes/admin-*.php          jednotlivé obrazovky
assets/admin.css              vzhled všech obrazovek
assets/admin.js               texty, obrázky, kopírování tokenů
assets/programme.js           editor programu
```
