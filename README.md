# CEEDUCON 2025 – responzivní program konference

Samostatné řešení úkolu pro pozici Webmaster/ka v Domě zahraniční spolupráce. Web převádí rozsáhlý program z tabulky čas × místnost do čitelné časové osy, která funguje na desktopu i mobilu.

## Spuštění

Data se načítají přes `fetch`, proto je potřeba lokální server:

```bash
python3 -m http.server 8080
```

Poté otevřete `http://localhost:8080`.

## Jak jsem nad řešením přemýšlel

Nejdůležitější otázky návštěvníka jsou: **kdy**, **kde** a **o čem** se něco děje. Čas je proto hlavní navigační osa. Každá karta ukazuje místnost, téma a název příspěvku bez nutnosti otevírat detail. Společné části programu mají odlišnou podobu a nepůsobí jako další přednáška.

Původní široká tabulka je na mobilu obtížně použitelná. Nové řešení používá na všech zařízeních stejný mentální model, ale na úzkém displeji skládá příspěvky do jednoho sloupce. Uživatel nemusí horizontálně posouvat osm místností a neztrácí kontext času.

## Funkce

- filtrování podle tematické linky a místnosti,
- fulltextové vyhledávání v českých i anglických názvech,
- ukázkový live režim se zvýrazněním právě probíhajícího bloku,
- vlastní výběr oblíbených příspěvků uložený v prohlížeči,
- detail příspěvku a export do kalendáře ve formátu `.ics`,
- tisková verze optimalizovaná pro export do PDF,
- přístupné ovládání klávesnicí a respektování `prefers-reduced-motion`,
- responzivní rozvržení bez horizontálního posouvání programu.

## Udržitelnost a editace

Obsah je oddělený od šablony v [`data/program.json`](data/program.json). Editor upravuje pouze:

- `rooms` – seznam místností,
- `themes` – tematické linky a jejich barvy,
- `slots` – časové bloky, společné části a jednotlivé příspěvky.

HTML ani JavaScript se při běžné změně programu neupravuje. Frontend je bez frameworku a build kroku, takže má minimum závislostí a lze jej jednoduše vložit do existujícího webu.

## WordPress

Součástí repozitáře je připravená WordPress šablona:

- zdrojová složka: [`wordpress-theme/ceeducon-program`](wordpress-theme/ceeducon-program)
- ZIP pro nahrání do WordPressu: [`dist/ceeducon-program-wordpress-theme.zip`](dist/ceeducon-program-wordpress-theme.zip)

Po nahrání a aktivaci šablony lze běžné texty upravovat v administraci přes **Vzhled → Přizpůsobit → CEEDUCON content**. Editovatelné jsou hero texty, úvodní blok, tematické oblasti, popisy nástrojů, intro programu, venue a footer.

Program jako takový zůstává ve strukturovaných datech `data/program.json` / `js/program-data.js`. Pro plnou produkční verzi bych další krok řešil přes vlastní typ obsahu `session` a ACF pole pro den, čas, místnost, tematickou linku, řečníky a anotaci. Frontend by potom získával data přes WordPress REST API ve stejné struktuře jako současný JSON.

## Další rozvoj

- **Více dnů:** datový model lze rozšířit o pole `days`; přepínač dne už má samostatnou komponentu.
- **Skutečný live režim:** demo čas se nahradí aktuálním časem v zóně `Europe/Prague` a aktivuje pouze v den konference.
- **Řečníci a anotace:** detail karty je připravený na fotografie, medailonky a odkazy na prezentace nebo stream.
- **Synchronizace programu:** REST API může načítat změny z WordPressu bez nového nasazení webu.
- **Osobní program:** oblíbené položky lze synchronizovat s uživatelským účtem nebo poslat e-mailem.

## Struktura

```text
assets/             logo, font Tabac Sans, reference
css/styles.css      jediný design systém včetně print stylů
data/program.json   editovatelný obsah programu
dist/               ZIP balíček WordPress šablony
js/i18n.js          české překlady názvů příspěvků
js/program.js       vykreslení, filtry, live režim, modal a export
index.html          sémantická kostra stránky
wordpress-theme/    uploadovatelná WordPress šablona
```
