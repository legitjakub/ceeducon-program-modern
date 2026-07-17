# CEEDUCON 2026 – vícestránkový konferenční web a programový modul

Moderní vícestránkový web pro CEEDUCON 2026 (1.–2. prosince 2026, O2 universum Praha) se strukturou: Home, About, Programme, Practical, Speakers, Media Kit a Contact. Součástí je interaktivní programový modul, který zatím ukazuje archivní data z CEEDUCON 2025 (jasně označená jako archive sample) a je připravený na oficiální program 2026.

Live verze: <https://legitjakub.github.io/ceeducon-program-modern/>

## Spuštění

Data programu se načítají přes `fetch`, proto je potřeba lokální server:

```bash
python3 -m http.server 8080
```

Poté otevřete `http://localhost:8080`. Interaktivní program je na `programme.html`.

## Design

Design systém staví výhradně na brand identitě CEEDUCON:

- modrá `#0d5e9d`,
- oranžová `#ec722f`,
- světle modrá `#45c0ea`,
- bílá `#ffffff`,
- font **Tabac Sans** (woff2/woff v `assets/fonts`).

Prvky: kompaktní tmavý hero s reálnou konferenční fotografií, editovatelná foto slideshow, regionální mapa, jednodušší číslované tematické karty, scroll-reveal animace (respektují `prefers-reduced-motion`), sticky hlavička a footer s logy partnerů.

## Funkce programového modulu

- filtrování podle tematické linky, místnosti a části dne,
- fulltextové vyhledávání (⌘K),
- přepínání mezi čitelným seznamem a gridem místností,
- vlastní výběr „My programme" uložený v prohlížeči,
- detail příspěvku a export do kalendáře (`.ics`),
- ukázkový live režim se zvýrazněním probíhajícího bloku,
- odpočet do začátku konference,
- tisková verze (A4 landscape) pro export do PDF,
- plně responzivní: na mobilu se grid místností skládá do časové osy.

## Editace obsahu

Struktura webu je navržená pro snadný převod do WordPressu:

- programová data jsou oddělená v [`data/program.json`](data/program.json) (`rooms`, `themes`, `slots`),
- ve WordPress verzi se obsah stránek upravuje v nativních Gutenberg blocích nebo ve volitelných CEEDUCON Elementor widgetech; **CEEDUCON Content** zůstává jen jako kompatibilní fallback pro globální hodnoty a Programme JSON,
- samostatná video sekce na homepage používá responzivní privacy-enhanced YouTube embed a je editovatelná jako Gutenberg blok i Elementor widget,
- hlavní navigace používá nativní WordPress menu **Appearance → Menus → Primary navigation**,
- brand paleta, šířky layoutu a editorový náhled fontu Tabac Sans jsou registrované přes `theme.json` a `css/editor-style.css`,
- HTML ani JavaScript se při běžné změně obsahu neupravuje; frontend je bez frameworku a build kroku.

## WordPress šablona

- zdrojová složka: [`wordpress-theme/ceeducon-program`](wordpress-theme/ceeducon-program)
- ZIP pro nahrání: [`dist/ceeducon-program-wordpress-theme.zip`](dist/ceeducon-program-wordpress-theme.zip)

Šablona je vícestránková (front-page + page šablony `about`, `programme`, `practical`, `speakers`, `media`, `contact` mapované podle slugů), sdílí CSS/JS se statickou verzí a podporuje editaci přes nativní bloky i volitelný Elementor Free plugin. Postup instalace je v [README šablony](wordpress-theme/ceeducon-program/README.md).

Pro plnou produkční verzi je dalším krokem vlastní typ obsahu `session` (den, čas, místnost, linka, řečníci, anotace) a REST API ve stejné JSON struktuře — frontend zůstane beze změny.

## Struktura

```text
assets/             loga CEEDUCON, DZS, favicon, font Tabac Sans
css/styles.css      jediný design systém včetně print stylů
data/program.json   editovatelná data programu (archiv 2025)
dist/               ZIP balíček WordPress šablony
js/site.js          menu, hlavička, scroll-reveal a mobilní karusely
js/program.js       vykreslení programu, filtry, modal, export
js/program-data.js  vestavěná záložní data programu
index.html          homepage
about.html          o konferenci, témata, organizátoři
programme.html      2026 overview + interaktivní programový modul
practical.html      venue, doprava, FAQ
speakers.html       informace a timeline pro řečníky
contact.html        kontakt, organizátor a partneři
media.html          media kit, oficiální vizuály a press informace
wordpress-theme/    uploadovatelná WordPress šablona
```
