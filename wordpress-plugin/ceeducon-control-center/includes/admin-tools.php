<?php
/**
 * Export, import and the raw values — the way out when a screen is not enough.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_cc_export_payload(): array
{
    return [
        'exported' => current_time('mysql'),
        'site' => home_url('/'),
        'plugin_version' => CEEDUCON_CC_VERSION,
        'edition' => ceeducon_cc_edition_settings(),
        'content' => ceeducon_cc_content_values(),
    ];
}

function ceeducon_cc_export(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění stahovat zálohu.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_export');

    $json = (string) wp_json_encode(ceeducon_cc_export_payload(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $name = 'ceeducon-' . gmdate('Y-m-d-Hi') . '.json';

    nocache_headers();
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $name . '"');
    header('Content-Length: ' . strlen($json));
    echo $json; // phpcs:ignore WordPress.Security.EscapeOutput -- a JSON download, not markup
    exit;
}
add_action('admin_post_ceeducon_cc_export', 'ceeducon_cc_export');

function ceeducon_cc_import(): void
{
    if (!ceeducon_cc_can_edit()) {
        wp_die(esc_html__('Nemáte oprávnění obnovovat zálohu.', 'ceeducon-cc'));
    }
    check_admin_referer('ceeducon_cc_import');

    $raw = isset($_POST['payload']) ? wp_unslash($_POST['payload']) : '';
    $data = json_decode((string) $raw, true);

    if (!is_array($data)) {
        wp_safe_redirect(add_query_arg('ceeducon-notice', 'badjson', ceeducon_cc_page_url('ceeducon-cc-tools')));
        exit;
    }

    // A file holding only the programme is a valid thing to import too.
    if (isset($data['days']) && !isset($data['content'])) {
        ceeducon_cc_prog_store(ceeducon_cc_prog_sanitize($data));
        wp_safe_redirect(add_query_arg('ceeducon-notice', 'imported', ceeducon_cc_page_url('ceeducon-cc-tools')));
        exit;
    }

    if (isset($data['edition']) && is_array($data['edition'])) {
        update_option(CEEDUCON_CC_EDITION_OPTION, ceeducon_cc_edition_sanitize($data['edition']), false);
    }

    if (isset($data['content']) && is_array($data['content'])) {
        $clean = [];
        foreach ($data['content'] as $key => $value) {
            $key = sanitize_key((string) $key);
            if ($key === '') {
                continue;
            }
            if ($key === CEEDUCON_CC_PROGRAMME_KEY) {
                $programme = json_decode((string) $value, true);
                if (is_array($programme)) {
                    $clean[$key] = ceeducon_cc_prog_encode(ceeducon_cc_prog_sanitize($programme));
                }
                continue;
            }
            $clean[$key] = str_ends_with($key, '_url') ? esc_url_raw((string) $value) : wp_kses_post((string) $value);
        }
        update_option(CEEDUCON_CC_CONTENT_OPTION, $clean);
    }

    wp_safe_redirect(add_query_arg('ceeducon-notice', 'imported', ceeducon_cc_page_url('ceeducon-cc-tools')));
    exit;
}
add_action('admin_post_ceeducon_cc_import', 'ceeducon_cc_import');

function ceeducon_cc_render_tools(): void
{
    if (!ceeducon_cc_can_edit()) {
        return;
    }

    $programme = ceeducon_cc_prog_load();
    ?>
    <div class="wrap cc-wrap">
      <?php ceeducon_cc_header('ceeducon-cc-tools', __('Zálohy a nástroje', 'ceeducon-cc'), __('Stažení celého nastavení do souboru, obnovení ze zálohy a surová data pro případ nouze.', 'ceeducon-cc')); ?>
      <?php ceeducon_cc_render_notice(); ?>

      <div class="cc-grid cc-grid--split">
        <section class="cc-panel">
          <h2><?php esc_html_e('Stáhnout zálohu', 'ceeducon-cc'); ?></h2>
          <p class="description"><?php esc_html_e('Jeden soubor s texty webu, nastavením ročníku i celým programem. Hodí se udělat před větší úpravou.', 'ceeducon-cc'); ?></p>
          <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="ceeducon_cc_export" />
            <?php wp_nonce_field('ceeducon_cc_export'); ?>
            <button type="submit" class="button button-primary"><?php esc_html_e('Stáhnout .json', 'ceeducon-cc'); ?></button>
          </form>
        </section>

        <section class="cc-panel">
          <h2><?php esc_html_e('Obnovit ze zálohy', 'ceeducon-cc'); ?></h2>
          <p class="description"><?php esc_html_e('Vložte obsah dříve staženého souboru. Přijme se i soubor, ve kterém je jen program.', 'ceeducon-cc'); ?></p>
          <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" onsubmit="return confirm('<?php echo esc_js(__('Obnovením se přepíše současné nastavení. Pokračovat?', 'ceeducon-cc')); ?>');">
            <input type="hidden" name="action" value="ceeducon_cc_import" />
            <?php wp_nonce_field('ceeducon_cc_import'); ?>
            <textarea name="payload" rows="8" class="cc-input cc-code" placeholder='{"edition": …, "content": …}'></textarea>
            <button type="submit" class="button"><?php esc_html_e('Obnovit', 'ceeducon-cc'); ?></button>
          </form>
        </section>
      </div>

      <section class="cc-panel">
        <h2><?php esc_html_e('Program v JSON', 'ceeducon-cc'); ?></h2>
        <p class="description"><?php esc_html_e('Přesně to, co web čte. Klikněte pro označení celého obsahu.', 'ceeducon-cc'); ?></p>
        <textarea class="cc-input cc-code" rows="14" readonly onclick="this.select()"><?php
            echo esc_textarea(ceeducon_cc_prog_encode($programme));
        ?></textarea>
      </section>

      <section class="cc-panel cc-panel--muted">
        <h2><?php esc_html_e('Kde co je uložené', 'ceeducon-cc'); ?></h2>
        <ul class="cc-facts">
          <li><code>ceeducon_content</code> — <?php esc_html_e('texty webu a program', 'ceeducon-cc'); ?></li>
          <li><code>ceeducon_event_settings</code> — <?php esc_html_e('nastavení ročníku', 'ceeducon-cc'); ?></li>
          <li><code>ceeducon_cc_programme_backup</code> — <?php esc_html_e('poslední záloha programu', 'ceeducon-cc'); ?></li>
        </ul>
        <p class="description"><?php esc_html_e('Jsou to stejná místa, která používala předchozí dvojice pluginů — po přechodu na Control Center se nic nepřenášelo.', 'ceeducon-cc'); ?></p>
      </section>
    </div>
    <?php
}
