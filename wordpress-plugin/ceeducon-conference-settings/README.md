# CEEDUCON Conference Edition

Lehký WordPress plugin pro údaje, které se mění s každým ročníkem konference.

Po aktivaci otevřete **CEEDUCON Content → Conference edition**. Na jednom místě lze změnit:

- název ročníku, začátek, konec a časové pásmo (rok a počet dnů se odvodí automaticky),
- místo konání a adresu,
- vstupné, stav a URL registrace,
- veřejné statistiky,
- hlavní a sociální obrázek.

Plugin z těchto hodnot automaticky vytvoří datumové popisky, odkazy pro Google a Outlook Calendar, SEO Event structured data a roční údaje v Hero sekcích. Stejné hodnoty se použijí ve fallback šablonách i Gutenberg blocích.

V běžných obsahových polích lze použít tokeny `{{event_title}}`, `{{year}}`, `{{date}}`, `{{date_short}}`, `{{venue}}`, `{{city}}`, `{{fee}}` a `{{registration}}`. Po změně ročníku se na frontendu nahradí aktuálními hodnotami; text kolem tokenů zůstává normálně editovatelný.

Datum, místo, registrace, kalendářové odkazy a hlavní fotografie jsou v Hero bloku záměrně uzamčené a odkazují na centrální formulář. Tím nemohou mít Gutenberg a PHP fallback rozdílné hodnoty.

Design, rozložení, fonty a responzivita zůstávají řízené tématem. Program sessions se nadále spravují odděleně v jejich strukturované administraci.
