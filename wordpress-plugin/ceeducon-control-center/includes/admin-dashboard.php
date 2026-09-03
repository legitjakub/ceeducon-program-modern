<?php
/**
 * Shared admin chrome plus the overview screen: what the site currently says,
 * and what still needs attention before the next edition goes live.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_cc_tabs(): array
{
    return [
        'ceeducon-cc' => ['label' => __('Přehled', 'ceeducon-cc'), 'icon' => 'dashboard'],
        'ceeducon-cc-programme' => ['label' => __('Program', 'ceeducon-cc'), 'icon' => 'list-view'],
        'ceeducon-cc-content' => ['label' => __('Texty webu', 'ceeducon-cc'), 'icon' => 'edit'],
        'ceeducon-cc-edition' => ['label' => __('Ročník konference', 'ceeducon-cc'), 'icon' => 'calendar-alt'],
        'ceeducon-cc-tools' => ['label' => __('Zálohy a nástroje', 'ceeducon-cc'), 'icon' => 'backup'],
    ];
}

function ceeducon_cc_header(string $current, string $title, string $lead = ''): void
{
    ?>
    <div class="cc-masthead">
      <div class="cc-masthead-top">
        <span class="cc-logo">CEEDUCON</span>
        <span class="cc-edition-chip">
          <?php echo esc_html((string) ceeducon_cc_edition_get('event_title')); ?>
          · <?php echo esc_html(ceeducon_cc_edition_full_date()); ?>
        </span>
        <a class="cc-view-site" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noreferrer">
          <?php esc_html_e('Zobrazit web', 'ceeducon-cc'); ?> ↗
        </a>
      </div>
      <nav class="cc-tabs">
        <?php foreach (ceeducon_cc_tabs() as $slug => $tab) : ?>
          <a class="cc-tab<?php echo $slug === $current ? ' is-active' : ''; ?>" href="<?php echo esc_url(ceeducon_cc_page_url($slug)); ?>">
            <span class="dashicons dashicons-<?php echo esc_attr($tab['icon']); ?>"></span>
            <?php echo esc_html($tab['label']); ?>
          </a>
        <?php endforeach; ?>
      </nav>
    </div>
    <div class="cc-page-head">
      <h1><?php echo esc_html($title); ?></h1>
      <?php if ($lead !== '') : ?><p class="cc-lead"><?php echo esc_html($lead); ?></p><?php endif; ?>
    </div>
    <?php
}

function ceeducon_cc_render_notice(): void
{
    $notice = isset($_GET['ceeducon-notice']) ? sanitize_key(wp_unslash($_GET['ceeducon-notice'])) : '';
    if ($notice === '') {
        return;
    }

    $messages = [
        'saved' => ['success', __('Uloženo. Změna je na webu vidět hned.', 'ceeducon-cc')],
        'reset' => ['success', __('Texty vráceny na výchozí znění ze šablony.', 'ceeducon-cc')],
        'restored' => ['success', __('Program obnoven z poslední zálohy.', 'ceeducon-cc')],
        'imported' => ['success', __('Data naimportována.', 'ceeducon-cc')],
        'invalid' => ['error', __('Uložení se nezdařilo — data nedorazila v pořádku. Zkuste to prosím znovu; nic se nezměnilo.', 'ceeducon-cc')],
        'badjson' => ['error', __('Vložený text není platný JSON, nic se neuložilo.', 'ceeducon-cc')],
        'nobackup' => ['error', __('Zatím není z čeho obnovovat — žádná záloha programu neexistuje.', 'ceeducon-cc')],
        'range' => ['error', __('Konec konference musí být až po jejím začátku. Nic se neuložilo.', 'ceeducon-cc')],
    ];

    if (!isset($messages[$notice])) {
        return;
    }

    [$kind, $text] = $messages[$notice];
    printf(
        '<div class="notice notice-%1$s is-dismissible cc-notice"><p>%2$s</p></div>',
        esc_attr($kind),
        esc_html($text)
    );
}

/* ---------------------------------------------------------------------------
 * Overview
 * ------------------------------------------------------------------------ */

/** The checks worth running before a new edition goes live. */
function ceeducon_cc_health(array $programme): array
{
    $checks = [];
    $stats = ceeducon_cc_prog_stats($programme);

    $checks[] = [
        'ok' => ceeducon_cc_theme_active(),
        'label' => __('Šablona CEEDUCON Programme je aktivní', 'ceeducon-cc'),
        'hint' => __('Bez ní se nastavení sice uloží, ale web ho nepoužije.', 'ceeducon-cc'),
    ];

    $registration = trim((string) ceeducon_cc_edition_get('registration_url'));
    $checks[] = [
        'ok' => $registration !== '',
        'label' => __('Odkaz na registraci je vyplněný', 'ceeducon-cc'),
        'hint' => __('Bez něj tlačítko Register vede na náhradní adresu zapsanou v šabloně.', 'ceeducon-cc'),
        'link' => ceeducon_cc_page_url('ceeducon-cc-edition'),
    ];

    $start = ceeducon_cc_edition_start();
    $checks[] = [
        'ok' => $start instanceof DateTimeImmutable,
        'label' => __('Datum konference dává smysl', 'ceeducon-cc'),
        'hint' => __('Z data se počítají odkazy do kalendáře i strukturovaná data pro Google.', 'ceeducon-cc'),
        'link' => ceeducon_cc_page_url('ceeducon-cc-edition'),
    ];

    $checks[] = [
        'ok' => $stats['sessions'] > 0,
        'label' => sprintf(__('Program obsahuje %d přednášek', 'ceeducon-cc'), (int) $stats['sessions']),
        'link' => ceeducon_cc_page_url('ceeducon-cc-programme'),
    ];

    $checks[] = [
        'ok' => $stats['missing_type'] === 0,
        'label' => $stats['missing_type'] === 0
            ? __('Všechny přednášky mají typ pro filtr', 'ceeducon-cc')
            : sprintf(__('%d přednášek nemá typ — ve filtru se neobjeví', 'ceeducon-cc'), (int) $stats['missing_type']),
        'link' => ceeducon_cc_page_url('ceeducon-cc-programme'),
    ];

    $checks[] = [
        'ok' => $stats['missing_abstract'] === 0,
        'label' => $stats['missing_abstract'] === 0
            ? __('Všechny přednášky mají anotaci', 'ceeducon-cc')
            : sprintf(__('%d přednášek nemá anotaci', 'ceeducon-cc'), (int) $stats['missing_abstract']),
        'hint' => __('Anotace se ukazuje po otevření přednášky. Bez ní zůstane okno jen se jmény a sálem.', 'ceeducon-cc'),
        'link' => ceeducon_cc_page_url('ceeducon-cc-programme'),
    ];

    return $checks;
}

function ceeducon_cc_render_dashboard(): void
{
    if (!ceeducon_cc_can_edit()) {
        return;
    }

    $programme = ceeducon_cc_prog_load();
    $stats = ceeducon_cc_prog_stats($programme);
    $content = ceeducon_cc_content_stats();
    $warnings = ceeducon_cc_prog_warnings($programme);
    $checks = ceeducon_cc_health($programme);
    ?>
    <div class="wrap cc-wrap">
      <?php ceeducon_cc_header('ceeducon-cc', __('Přehled', 'ceeducon-cc'), __('Co web právě říká a co ještě čeká na doplnění.', 'ceeducon-cc')); ?>
      <?php ceeducon_cc_render_notice(); ?>

      <div class="cc-grid cc-grid--cards">
        <a class="cc-card cc-card--link" href="<?php echo esc_url(ceeducon_cc_page_url('ceeducon-cc-programme')); ?>">
          <span class="cc-card-eyebrow"><?php esc_html_e('Program', 'ceeducon-cc'); ?></span>
          <strong class="cc-card-number"><?php echo (int) $stats['sessions']; ?></strong>
          <span class="cc-card-note">
            <?php printf(
                esc_html__('%1$d dny · %2$d sálů · %3$d řečníků', 'ceeducon-cc'),
                (int) $stats['days'], (int) $stats['rooms'], (int) $stats['speakers']
            ); ?>
          </span>
        </a>

        <a class="cc-card cc-card--link" href="<?php echo esc_url(ceeducon_cc_page_url('ceeducon-cc-content')); ?>">
          <span class="cc-card-eyebrow"><?php esc_html_e('Texty webu', 'ceeducon-cc'); ?></span>
          <strong class="cc-card-number"><?php echo (int) $content['fields']; ?></strong>
          <span class="cc-card-note">
            <?php printf(
                esc_html__('%1$d skupin · %2$d upravených proti šabloně', 'ceeducon-cc'),
                (int) $content['groups'], (int) $content['edited']
            ); ?>
          </span>
        </a>

        <a class="cc-card cc-card--link" href="<?php echo esc_url(ceeducon_cc_page_url('ceeducon-cc-edition')); ?>">
          <span class="cc-card-eyebrow"><?php esc_html_e('Ročník', 'ceeducon-cc'); ?></span>
          <strong class="cc-card-number"><?php echo esc_html((string) ceeducon_cc_edition_year()); ?></strong>
          <span class="cc-card-note">
            <?php echo esc_html(ceeducon_cc_edition_full_date() . ' · ' . (string) ceeducon_cc_edition_get('venue_name')); ?>
          </span>
        </a>
      </div>

      <div class="cc-grid cc-grid--split">
        <section class="cc-panel">
          <h2><?php esc_html_e('Kontrola před spuštěním', 'ceeducon-cc'); ?></h2>
          <ul class="cc-checks">
            <?php foreach ($checks as $check) : ?>
              <li class="cc-check<?php echo $check['ok'] ? ' is-ok' : ' is-todo'; ?>">
                <span class="cc-check-mark" aria-hidden="true"><?php echo $check['ok'] ? '✓' : '!'; ?></span>
                <span class="cc-check-body">
                  <strong><?php echo esc_html($check['label']); ?></strong>
                  <?php if (!empty($check['hint'])) : ?><small><?php echo esc_html($check['hint']); ?></small><?php endif; ?>
                </span>
                <?php if (!$check['ok'] && !empty($check['link'])) : ?>
                  <a class="button button-small" href="<?php echo esc_url($check['link']); ?>"><?php esc_html_e('Doplnit', 'ceeducon-cc'); ?></a>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>

        <section class="cc-panel">
          <h2><?php esc_html_e('Zástupné texty', 'ceeducon-cc'); ?></h2>
          <p class="description"><?php esc_html_e('Napište je kamkoli do textů webu. Návštěvník uvidí vždy aktuální hodnotu z Ročníku konference — příští rok se přepíšou samy.', 'ceeducon-cc'); ?></p>
          <table class="cc-tokens">
            <tbody>
              <?php foreach (ceeducon_cc_tokens() as $token => $value) : ?>
                <tr>
                  <th><button type="button" class="cc-copy" data-cc-copy="<?php echo esc_attr($token); ?>"><code><?php echo esc_html($token); ?></code></button></th>
                  <td><?php echo esc_html($value !== '' ? $value : '—'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </section>
      </div>

      <?php if ($warnings) : ?>
        <section class="cc-panel cc-panel--warn">
          <h2><?php esc_html_e('V programu je co doladit', 'ceeducon-cc'); ?></h2>
          <ul class="cc-warnings">
            <?php foreach ($warnings as $warning) : ?><li><?php echo esc_html($warning); ?></li><?php endforeach; ?>
          </ul>
          <p><a class="button" href="<?php echo esc_url(ceeducon_cc_page_url('ceeducon-cc-programme')); ?>"><?php esc_html_e('Otevřít program', 'ceeducon-cc'); ?></a></p>
        </section>
      <?php endif; ?>
    </div>
    <?php
}
