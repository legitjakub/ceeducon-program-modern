<?php
/**
 * Every visible text on the site, grouped the way the pages are built.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ceeducon_cc_content_field_input(string $key, string $type, string $value): void
{
    $name = 'content[' . $key . ']';
    $id = 'cc-' . $key;

    if ($type === 'textarea') {
        printf(
            '<textarea id="%1$s" name="%2$s" rows="3" class="cc-input" data-cc-input>%3$s</textarea>',
            esc_attr($id),
            esc_attr($name),
            esc_textarea($value)
        );
        return;
    }

    printf(
        '<input id="%1$s" name="%2$s" type="%3$s" class="cc-input" data-cc-input value="%4$s" />',
        esc_attr($id),
        esc_attr($name),
        esc_attr($type === 'url' ? 'url' : 'text'),
        esc_attr($value)
    );
}

function ceeducon_cc_render_content(): void
{
    if (!ceeducon_cc_can_edit()) {
        return;
    }

    $groups = ceeducon_cc_content_groups();
    $meta = ceeducon_cc_group_meta();
    $values = ceeducon_cc_content_values();
    $active = isset($_GET['group']) ? (int) $_GET['group'] : 0;
    ?>
    <div class="wrap cc-wrap">
      <?php ceeducon_cc_header('ceeducon-cc-content', __('Texty webu', 'ceeducon-cc'), __('Všechno, co je na stránkách napsané. Prázdné pole znamená „použij znění ze šablony".', 'ceeducon-cc')); ?>
      <?php ceeducon_cc_render_notice(); ?>

      <?php if (!$groups) : ?>
        <div class="cc-panel">
          <p><?php esc_html_e('Seznam textů dodává šablona CEEDUCON Programme. Aktivujte ji a texty se tu objeví.', 'ceeducon-cc'); ?></p>
        </div>
      </div>
      <?php return; endif; ?>

      <script type="application/json" id="cc-tokens-data"><?php
          echo ceeducon_cc_json_for_script(ceeducon_cc_tokens());
      ?></script>

      <form class="cc-content-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" data-cc-content>
        <input type="hidden" name="action" value="ceeducon_cc_save_content" />
        <input type="hidden" name="active_group" value="<?php echo esc_attr((string) $active); ?>" data-cc-active-group />
        <?php wp_nonce_field('ceeducon_cc_save_content'); ?>

        <div class="cc-toolbar cc-toolbar--sticky">
          <label class="cc-search">
            <span class="dashicons dashicons-search" aria-hidden="true"></span>
            <input type="search" placeholder="<?php esc_attr_e('Hledat v textech…', 'ceeducon-cc'); ?>" data-cc-search />
          </label>
          <span class="cc-toolbar-status" data-cc-search-status></span>
          <label class="cc-toggle">
            <input type="checkbox" data-cc-preview-toggle />
            <span><?php esc_html_e('Ukázat, jak se doplní {{zástupné texty}}', 'ceeducon-cc'); ?></span>
          </label>
          <button type="submit" class="button button-primary"><?php esc_html_e('Uložit texty', 'ceeducon-cc'); ?></button>
        </div>

        <div class="cc-columns">
          <nav class="cc-sidebar" data-cc-groupnav>
            <?php $index = 0; foreach ($groups as $group => $fields) :
                $info = $meta[$group] ?? ['title' => $group, 'icon' => 'admin-generic'];
                $edited = 0;
                foreach ($fields as [$key, , $default]) {
                    if (array_key_exists($key, $values) && (string) $values[$key] !== (string) $default) {
                        $edited++;
                    }
                }
                ?>
              <button type="button" class="cc-sidebar-item<?php echo $index === $active ? ' is-active' : ''; ?>" data-cc-group="<?php echo (int) $index; ?>">
                <span class="dashicons dashicons-<?php echo esc_attr($info['icon']); ?>" aria-hidden="true"></span>
                <span class="cc-sidebar-label"><?php echo esc_html($info['title']); ?></span>
                <span class="cc-sidebar-count"<?php echo $edited ? ' data-edited="1"' : ''; ?>><?php echo (int) count($fields); ?></span>
              </button>
            <?php $index++; endforeach; ?>
          </nav>

          <div class="cc-panels">
            <?php $index = 0; foreach ($groups as $group => $fields) :
                $info = $meta[$group] ?? ['title' => $group, 'icon' => 'admin-generic']; ?>
              <section class="cc-panel cc-group" data-cc-group-panel="<?php echo (int) $index; ?>" <?php echo $index === $active ? '' : 'hidden'; ?>>
                <header class="cc-group-head">
                  <div>
                    <h2><?php echo esc_html($info['title']); ?></h2>
                    <?php if (!empty($info['note'])) : ?><p class="description"><?php echo esc_html($info['note']); ?></p><?php endif; ?>
                  </div>
                </header>

                <?php foreach ($fields as [$key, $label, $default, $type]) :
                    $value = ceeducon_cc_field_value($values, $key, (string) $default);
                    $is_edited = array_key_exists($key, $values) && (string) $values[$key] !== (string) $default;
                    $search = strtolower($label . ' ' . $key . ' ' . wp_strip_all_tags($value)); ?>
                  <div class="cc-field<?php echo $is_edited ? ' is-edited' : ''; ?>" data-cc-field data-cc-search="<?php echo esc_attr($search); ?>">
                    <div class="cc-field-head">
                      <label for="cc-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label>
                      <code class="cc-key"><?php echo esc_html($key); ?></code>
                      <?php if ($is_edited) : ?><span class="cc-chip"><?php esc_html_e('upraveno', 'ceeducon-cc'); ?></span><?php endif; ?>
                      <button type="button" class="cc-reset" data-cc-reset data-cc-default="<?php echo esc_attr((string) $default); ?>">
                        <?php esc_html_e('vrátit výchozí', 'ceeducon-cc'); ?>
                      </button>
                    </div>
                    <?php ceeducon_cc_content_field_input($key, (string) $type, $value); ?>
                    <p class="cc-preview" data-cc-preview hidden></p>
                  </div>
                <?php endforeach; ?>
              </section>
            <?php $index++; endforeach; ?>

            <p class="cc-empty" data-cc-empty hidden><?php esc_html_e('Nic takového tu není. Zkuste jiné slovo.', 'ceeducon-cc'); ?></p>

            <p class="submit">
              <button type="submit" class="button button-primary button-hero"><?php esc_html_e('Uložit texty', 'ceeducon-cc'); ?></button>
            </p>
          </div>
        </div>
      </form>

      <details class="cc-panel cc-danger">
        <summary><?php esc_html_e('Vrátit texty na výchozí znění', 'ceeducon-cc'); ?></summary>
        <div class="cc-panel-body">
          <p class="description"><?php esc_html_e('Smaže uložená znění a web se vrátí k textům dodaným v šabloně. Program ani nastavení ročníku se nemění.', 'ceeducon-cc'); ?></p>
          <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" onsubmit="return confirm('<?php echo esc_js(__('Opravdu vrátit texty na výchozí znění?', 'ceeducon-cc')); ?>');">
            <input type="hidden" name="action" value="ceeducon_cc_reset_content" />
            <?php wp_nonce_field('ceeducon_cc_reset_content'); ?>
            <select name="group_index">
              <option value="all"><?php esc_html_e('Všechny skupiny', 'ceeducon-cc'); ?></option>
              <?php $index = 0; foreach ($groups as $group => $fields) :
                  $info = $meta[$group] ?? ['title' => $group]; ?>
                <option value="<?php echo (int) $index; ?>"><?php echo esc_html($info['title']); ?></option>
              <?php $index++; endforeach; ?>
            </select>
            <button type="submit" class="button"><?php esc_html_e('Vrátit výchozí', 'ceeducon-cc'); ?></button>
          </form>
        </div>
      </details>
    </div>
    <?php
}
