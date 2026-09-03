<?php
/**
 * The screen for everything that is different each year.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_cc_edition_field(string $key, string $label, string $type = 'text', string $help = '', array $attrs = []): void
{
    $value = (string) ceeducon_cc_edition_get($key);
    $extra = '';
    foreach ($attrs as $attr => $attr_value) {
        $extra .= sprintf(' %s="%s"', esc_attr($attr), esc_attr((string) $attr_value));
    }
    ?>
    <label class="cc-field cc-field--inline">
      <span class="cc-field-label"><?php echo esc_html($label); ?></span>
      <?php if ($type === 'textarea') : ?>
        <textarea class="cc-input" name="edition[<?php echo esc_attr($key); ?>]" rows="3"<?php echo $extra; // phpcs:ignore ?>><?php echo esc_textarea($value); ?></textarea>
      <?php else : ?>
        <input class="cc-input" type="<?php echo esc_attr($type); ?>" name="edition[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>"<?php echo $extra; // phpcs:ignore ?> />
      <?php endif; ?>
      <?php if ($help !== '') : ?><small><?php echo esc_html($help); ?></small><?php endif; ?>
    </label>
    <?php
}

function ceeducon_cc_edition_media_field(string $key, string $label, string $help): void
{
    $attachment_id = (int) ceeducon_cc_edition_get($key);
    $preview = $attachment_id > 0 ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
    ?>
    <div class="cc-field cc-media" data-cc-media>
      <span class="cc-field-label"><?php echo esc_html($label); ?></span>
      <input type="hidden" name="edition[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr((string) $attachment_id); ?>" data-cc-media-id />
      <div class="cc-media-preview" data-cc-media-preview>
        <?php if ($preview) : ?><img src="<?php echo esc_url($preview); ?>" alt="" /><?php else : ?><span><?php esc_html_e('bez obrázku', 'ceeducon-cc'); ?></span><?php endif; ?>
      </div>
      <div class="cc-media-actions">
        <button type="button" class="button" data-cc-media-select><?php esc_html_e('Vybrat obrázek', 'ceeducon-cc'); ?></button>
        <button type="button" class="button-link-delete" data-cc-media-remove<?php echo $attachment_id < 1 ? ' hidden' : ''; ?>><?php esc_html_e('Odebrat', 'ceeducon-cc'); ?></button>
      </div>
      <small><?php echo esc_html($help); ?></small>
    </div>
    <?php
}

function ceeducon_cc_render_edition(): void
{
    if (!ceeducon_cc_can_edit()) {
        return;
    }

    $days = ceeducon_cc_edition_day_count();
    $timezones = timezone_identifiers_list();
    $current_timezone = (string) ceeducon_cc_edition_get('timezone');
    ?>
    <div class="wrap cc-wrap">
      <?php ceeducon_cc_header('ceeducon-cc-edition', __('Ročník konference', 'ceeducon-cc'), __('Údaje, které se mění každý rok. Jedna změna tady projde hero blokem, odkazy do kalendáře, SEO popisky i strukturovanými daty.', 'ceeducon-cc')); ?>
      <?php ceeducon_cc_render_notice(); ?>

      <div class="cc-edition-hero">
        <span class="cc-edition-hero-eyebrow"><?php echo esc_html((string) ceeducon_cc_edition_get('country_label')); ?></span>
        <strong><?php echo esc_html((string) ceeducon_cc_edition_get('event_title')); ?></strong>
        <span class="cc-edition-hero-date"><?php echo esc_html(ceeducon_cc_edition_full_date()); ?></span>
        <span class="cc-edition-hero-note">
          <?php echo esc_html((string) ceeducon_cc_edition_get('venue_name')); ?>
          · <?php echo esc_html((string) ceeducon_cc_edition_get('fee_text')); ?>
          · <?php printf(esc_html(_n('%d den', '%d dny', $days, 'ceeducon-cc')), (int) $days); ?>
        </span>
      </div>

      <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="cc-edition-form">
        <input type="hidden" name="action" value="ceeducon_cc_save_edition" />
        <?php wp_nonce_field('ceeducon_cc_save_edition'); ?>

        <div class="cc-toolbar cc-toolbar--sticky">
          <span class="cc-toolbar-title"><?php esc_html_e('Ročník konference', 'ceeducon-cc'); ?></span>
          <button type="submit" class="button button-primary"><?php esc_html_e('Uložit ročník', 'ceeducon-cc'); ?></button>
        </div>

        <section class="cc-panel">
          <h2><?php esc_html_e('Termín', 'ceeducon-cc'); ?></h2>
          <div class="cc-grid cc-grid--fields">
            <?php ceeducon_cc_edition_field('event_title', __('Název ročníku', 'ceeducon-cc'), 'text', __('Necháte-li tvar „CEEDUCON RRRR", rok se dopočítá z data sám.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('country_label', __('Země v hero bloku', 'ceeducon-cc'), 'text', __('Malý text nad názvem na titulní straně.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('start_date', __('Začátek — datum', 'ceeducon-cc'), 'date'); ?>
            <?php ceeducon_cc_edition_field('start_time', __('Začátek — čas', 'ceeducon-cc'), 'time'); ?>
            <?php ceeducon_cc_edition_field('end_date', __('Konec — datum', 'ceeducon-cc'), 'date'); ?>
            <?php ceeducon_cc_edition_field('end_time', __('Konec — čas', 'ceeducon-cc'), 'time'); ?>
            <label class="cc-field cc-field--inline">
              <span class="cc-field-label"><?php esc_html_e('Časové pásmo', 'ceeducon-cc'); ?></span>
              <select class="cc-input" name="edition[timezone]">
                <?php foreach ($timezones as $timezone) : ?>
                  <option value="<?php echo esc_attr($timezone); ?>" <?php selected($current_timezone, $timezone); ?>><?php echo esc_html($timezone); ?></option>
                <?php endforeach; ?>
              </select>
              <small><?php esc_html_e('Podle něj se počítají odkazy do kalendáře.', 'ceeducon-cc'); ?></small>
            </label>
          </div>
        </section>

        <section class="cc-panel">
          <h2><?php esc_html_e('Místo konání', 'ceeducon-cc'); ?></h2>
          <div class="cc-grid cc-grid--fields">
            <?php ceeducon_cc_edition_field('venue_name', __('Místo, jak ho vidí návštěvník', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('city_label', __('Město v textech', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('venue_schema_name', __('Název místa pro Google', 'ceeducon-cc'), 'text', __('Bez města — jen jméno budovy.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('street_address', __('Ulice a číslo', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('address_locality', __('Městská část', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('postal_code', __('PSČ', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('country_code', __('Kód země', 'ceeducon-cc'), 'text', __('Dvě písmena, například CZ.', 'ceeducon-cc'), ['maxlength' => 2]); ?>
          </div>
        </section>

        <section class="cc-panel">
          <h2><?php esc_html_e('Účast a registrace', 'ceeducon-cc'); ?></h2>
          <div class="cc-grid cc-grid--fields">
            <?php ceeducon_cc_edition_field('fee_text', __('Vstupné', 'ceeducon-cc'), 'text', __('Dosadí se do {{fee}}. Slovo „free" navíc řekne Googlu, že je konference zdarma.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('registration_text', __('Stav registrace', 'ceeducon-cc'), 'text', __('Dosadí se do {{registration}} a do hero bloku.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('registration_url', __('Odkaz na registrační formulář', 'ceeducon-cc'), 'url', __('Sem vede tlačítko Register v hlavičce i v hero bloku.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('language', __('Jazyk konference', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('language_code', __('Kód jazyka', 'ceeducon-cc'), 'text', __('Například en nebo cs.', 'ceeducon-cc'), ['maxlength' => 5]); ?>
          </div>
        </section>

        <section class="cc-panel">
          <h2><?php esc_html_e('Čísla na titulní straně', 'ceeducon-cc'); ?></h2>
          <div class="cc-grid cc-grid--fields">
            <label class="cc-field cc-field--inline">
              <span class="cc-field-label"><?php esc_html_e('Číslo 1 — hodnota', 'ceeducon-cc'); ?></span>
              <input class="cc-input" type="text" value="<?php echo esc_attr((string) $days); ?>" readonly />
              <small><?php esc_html_e('Počítá se z termínu konference.', 'ceeducon-cc'); ?></small>
            </label>
            <?php ceeducon_cc_edition_field('stat_1_label', __('Číslo 1 — popis', 'ceeducon-cc')); ?>
            <?php for ($index = 2; $index <= 4; $index++) : ?>
              <?php ceeducon_cc_edition_field('stat_' . $index . '_value', sprintf(__('Číslo %d — hodnota', 'ceeducon-cc'), $index)); ?>
              <?php ceeducon_cc_edition_field('stat_' . $index . '_label', sprintf(__('Číslo %d — popis', 'ceeducon-cc'), $index)); ?>
            <?php endfor; ?>
          </div>
        </section>

        <section class="cc-panel">
          <h2><?php esc_html_e('Obrázky ročníku', 'ceeducon-cc'); ?></h2>
          <div class="cc-grid cc-grid--fields">
            <?php ceeducon_cc_edition_media_field('hero_image_id', __('Obrázek titulní strany', 'ceeducon-cc'), __('Prázdné = zůstane obrázek ze šablony.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_media_field('social_image_id', __('Obrázek pro sdílení', 'ceeducon-cc'), __('Použije se při sdílení odkazu na sítích.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('hero_image_alt', __('Popis obrázku titulní strany', 'ceeducon-cc'), 'text', __('Přečtou ho odečítače obrazovky.', 'ceeducon-cc')); ?>
            <?php ceeducon_cc_edition_field('calendar_description', __('Popis v kalendáři', 'ceeducon-cc'), 'textarea'); ?>
          </div>
        </section>

        <section class="cc-panel cc-panel--muted">
          <h2><?php esc_html_e('Dopočítá se samo', 'ceeducon-cc'); ?></h2>
          <dl class="cc-derived">
            <div><dt><?php esc_html_e('Datum v textech', 'ceeducon-cc'); ?></dt><dd><?php echo esc_html(ceeducon_cc_edition_full_date()); ?></dd></div>
            <div><dt><?php esc_html_e('Zkrácené datum', 'ceeducon-cc'); ?></dt><dd><?php echo esc_html(ceeducon_cc_edition_short_date()); ?></dd></div>
            <div><dt><?php esc_html_e('Den v hero bloku', 'ceeducon-cc'); ?></dt><dd><?php echo esc_html(ceeducon_cc_edition_event_day()); ?></dd></div>
            <div><dt><?php esc_html_e('Měsíc v hero bloku', 'ceeducon-cc'); ?></dt><dd><?php echo esc_html(ceeducon_cc_edition_event_month()); ?></dd></div>
            <div><dt><?php esc_html_e('Google Kalendář', 'ceeducon-cc'); ?></dt><dd class="cc-derived-url"><?php echo esc_html(ceeducon_cc_google_calendar_url()); ?></dd></div>
            <div><dt><?php esc_html_e('Outlook', 'ceeducon-cc'); ?></dt><dd class="cc-derived-url"><?php echo esc_html(ceeducon_cc_outlook_calendar_url()); ?></dd></div>
          </dl>
        </section>

        <p class="submit">
          <button type="submit" class="button button-primary button-hero"><?php esc_html_e('Uložit ročník', 'ceeducon-cc'); ?></button>
        </p>
      </form>
    </div>
    <?php
}
