<?php
/**
 * The programme editor. PHP hands over the whole programme as one JSON
 * document and takes it back the same way; everything in between happens in
 * the browser, which is what keeps a two-day, nine-room grid editable without
 * a page reload for every change.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_cc_prog_labels(): array
{
    return [
        'day' => __('Den', 'ceeducon-cc'),
        'addDay' => __('+ Přidat den', 'ceeducon-cc'),
        'removeDay' => __('Odebrat den', 'ceeducon-cc'),
        'dayDate' => __('Datum', 'ceeducon-cc'),
        'dayLabel' => __('Označení', 'ceeducon-cc'),
        'dayTitle' => __('Nadpis dne', 'ceeducon-cc'),
        'addSlot' => __('+ Přidat časový blok', 'ceeducon-cc'),
        'addBreak' => __('+ Přidat přestávku', 'ceeducon-cc'),
        'addSession' => __('+ Přidat přednášku', 'ceeducon-cc'),
        'from' => __('Od', 'ceeducon-cc'),
        'to' => __('Do', 'ceeducon-cc'),
        'sessions' => __('Přednášky', 'ceeducon-cc'),
        'break' => __('Přestávka', 'ceeducon-cc'),
        'breakTitle' => __('Popis přestávky', 'ceeducon-cc'),
        'breakKind' => __('Druh', 'ceeducon-cc'),
        'title' => __('Název', 'ceeducon-cc'),
        'rooms' => __('Sály', 'ceeducon-cc'),
        'theme' => __('Téma', 'ceeducon-cc'),
        'format' => __('Formát', 'ceeducon-cc'),
        'type' => __('Typ (filtr na webu)', 'ceeducon-cc'),
        'speakers' => __('Řečníci', 'ceeducon-cc'),
        'speakersHint' => __('Každý na svůj řádek — přesně tak, jak mají být na webu.', 'ceeducon-cc'),
        'abstract' => __('Anotace', 'ceeducon-cc'),
        'abstractHint' => __('Prázdný řádek odděluje odstavce.', 'ceeducon-cc'),
        'none' => __('— žádný —', 'ceeducon-cc'),
        'noTheme' => __('— bez tématu —', 'ceeducon-cc'),
        'noSessions' => __('V tomto bloku zatím nic není.', 'ceeducon-cc'),
        'noRoom' => __('bez sálu', 'ceeducon-cc'),
        'noSpeakers' => __('bez řečníků', 'ceeducon-cc'),
        'hasAbstract' => __('má anotaci', 'ceeducon-cc'),
        'noAbstract' => __('bez anotace', 'ceeducon-cc'),
        'duplicate' => __('Duplikovat', 'ceeducon-cc'),
        'remove' => __('Odebrat', 'ceeducon-cc'),
        'close' => __('Zavřít', 'ceeducon-cc'),
        'moveTo' => __('Přesunout do bloku', 'ceeducon-cc'),
        'moveUp' => __('Nahoru', 'ceeducon-cc'),
        'moveDown' => __('Dolů', 'ceeducon-cc'),
        'confirmSession' => __('Opravdu odebrat tuto přednášku?', 'ceeducon-cc'),
        'confirmSlot' => __('Opravdu odebrat celý blok i s jeho přednáškami?', 'ceeducon-cc'),
        'confirmDay' => __('Opravdu odebrat celý den?', 'ceeducon-cc'),
        'unsaved' => __('Neuložené změny', 'ceeducon-cc'),
        'saved' => __('Vše uloženo', 'ceeducon-cc'),
        'leave' => __('Máte neuložené změny v programu.', 'ceeducon-cc'),
        'newSession' => __('Nová přednáška', 'ceeducon-cc'),
        'newDayTitle' => __('Nový den', 'ceeducon-cc'),
        'roomsHint' => __('Pořadí určuje pořadí sloupců v mřížce na webu. Jeden sál na řádek.', 'ceeducon-cc'),
        'label' => __('Název', 'ceeducon-cc'),
        'color' => __('Barva', 'ceeducon-cc'),
        'id' => __('ID', 'ceeducon-cc'),
        'addRow' => __('+ Přidat', 'ceeducon-cc'),
        'idLocked' => __('ID se používá v uložených datech, proto ho nelze měnit.', 'ceeducon-cc'),
        'matches' => __('vyhovuje hledání', 'ceeducon-cc'),
        'filtered' => __('Filtr je zapnutý — část programu je skrytá.', 'ceeducon-cc'),
    ];
}

function ceeducon_cc_render_programme(): void
{
    if (!ceeducon_cc_can_edit()) {
        return;
    }

    $data = ceeducon_cc_prog_load();
    $stats = ceeducon_cc_prog_stats($data);
    $warnings = ceeducon_cc_prog_warnings($data);
    $backup = get_option('ceeducon_cc_programme_backup', []);
    ?>
    <div class="wrap cc-wrap cc-wrap--wide">
      <?php ceeducon_cc_header('ceeducon-cc-programme', __('Program konference', 'ceeducon-cc'), __('Dny, časové bloky, sály a jednotlivé přednášky. Uloží se na stejné místo, ze kterého web program čte.', 'ceeducon-cc')); ?>
      <?php ceeducon_cc_render_notice(); ?>

      <script type="application/json" id="cc-programme-data"><?php echo ceeducon_cc_json_for_script($data); ?></script>
      <script type="application/json" id="cc-programme-labels"><?php echo ceeducon_cc_json_for_script(ceeducon_cc_prog_labels()); ?></script>

      <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" data-cc-programme-form>
        <input type="hidden" name="action" value="ceeducon_cc_save_programme" />
        <input type="hidden" name="programme_payload" value="" data-cc-payload />
        <?php wp_nonce_field('ceeducon_cc_save_programme'); ?>

        <div class="cc-toolbar cc-toolbar--sticky cc-prog-toolbar">
          <div class="cc-view-switch" data-cc-view-switch>
            <button type="button" class="is-active" data-cc-view="schedule"><?php esc_html_e('Program', 'ceeducon-cc'); ?></button>
            <button type="button" data-cc-view="setup"><?php esc_html_e('Sály, témata a typy', 'ceeducon-cc'); ?></button>
          </div>
          <label class="cc-search">
            <span class="dashicons dashicons-search" aria-hidden="true"></span>
            <input type="search" placeholder="<?php esc_attr_e('Hledat přednášku, řečníka, sál…', 'ceeducon-cc'); ?>" data-cc-prog-search />
          </label>
          <span class="cc-dirty" data-cc-dirty hidden><?php esc_html_e('Neuložené změny', 'ceeducon-cc'); ?></span>
          <button type="submit" class="button button-primary"><?php esc_html_e('Uložit program', 'ceeducon-cc'); ?></button>
        </div>

        <div class="cc-prog-stats" data-cc-stats>
          <span><strong><?php echo (int) $stats['days']; ?></strong> <?php esc_html_e('dny', 'ceeducon-cc'); ?></span>
          <span><strong><?php echo (int) $stats['sessions']; ?></strong> <?php esc_html_e('přednášek', 'ceeducon-cc'); ?></span>
          <span><strong><?php echo (int) $stats['breaks']; ?></strong> <?php esc_html_e('přestávek', 'ceeducon-cc'); ?></span>
          <span><strong><?php echo (int) $stats['rooms']; ?></strong> <?php esc_html_e('sálů', 'ceeducon-cc'); ?></span>
          <span><strong><?php echo (int) $stats['speakers']; ?></strong> <?php esc_html_e('řečníků', 'ceeducon-cc'); ?></span>
        </div>

        <div id="cc-programme-app" class="cc-prog-app">
          <noscript><p><?php esc_html_e('Editor programu potřebuje zapnutý JavaScript.', 'ceeducon-cc'); ?></p></noscript>
        </div>

        <p class="submit">
          <button type="submit" class="button button-primary button-hero"><?php esc_html_e('Uložit program', 'ceeducon-cc'); ?></button>
        </p>
      </form>

      <?php if ($warnings) : ?>
        <section class="cc-panel cc-panel--warn">
          <h2><?php esc_html_e('Zkontrolujte prosím', 'ceeducon-cc'); ?></h2>
          <p class="description"><?php esc_html_e('Vychází z naposledy uloženého programu. Po uložení se seznam přepočítá.', 'ceeducon-cc'); ?></p>
          <ul class="cc-warnings">
            <?php foreach ($warnings as $warning) : ?><li><?php echo esc_html($warning); ?></li><?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

      <section class="cc-panel">
        <h2><?php esc_html_e('Záloha', 'ceeducon-cc'); ?></h2>
        <?php if (is_array($backup) && !empty($backup['json'])) : ?>
          <p class="description">
            <?php printf(
                esc_html__('Poslední záloha vznikla %1$s při ukládání (%2$s). Obnovením se program vrátí do stavu před tímto uložením.', 'ceeducon-cc'),
                esc_html((string) ($backup['saved'] ?? '')),
                esc_html((string) ($backup['user'] ?? '—'))
            ); ?>
          </p>
          <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" onsubmit="return confirm('<?php echo esc_js(__('Obnovit program z poslední zálohy?', 'ceeducon-cc')); ?>');">
            <input type="hidden" name="action" value="ceeducon_cc_restore_programme" />
            <?php wp_nonce_field('ceeducon_cc_restore_programme'); ?>
            <button type="submit" class="button"><?php esc_html_e('Obnovit z poslední zálohy', 'ceeducon-cc'); ?></button>
          </form>
        <?php else : ?>
          <p class="description"><?php esc_html_e('Záloha vznikne automaticky při prvním uložení programu.', 'ceeducon-cc'); ?></p>
        <?php endif; ?>
        <p><a href="<?php echo esc_url(ceeducon_cc_page_url('ceeducon-cc-tools')); ?>"><?php esc_html_e('Export a import celého programu →', 'ceeducon-cc'); ?></a></p>
      </section>
    </div>
    <?php
}
