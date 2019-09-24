<?php

class wta_admin
{

  static function draw_page($page)
  {
    $id = $page['id'];

    if (isset($page['sections'])) {
      foreach ($page['sections'] as $section) {
        $section_id = $section['id'];
        $section_label = isset($section['label']) ? $section['label'] : '';
        add_settings_section(
          $section_id,
          $section_label,
          '',
          $id
        );
        if (isset($section['fields'])) {
          foreach ($section['fields'] as $field) {
            $field['page'] = $id;
            $field['section'] = $section_id;
            self::add_field($field);
          }
        }
        
      }
    }
  }

  static function add_field($args)
  {
    register_setting(
      $args['page'],
      $args['id']
    );

    $field = isset($args['field']) ? $args['field'] : 'input';
    $section = isset($args['section']) ? $args['section'] : '';
    $label = isset($args['label']) ? $args['label'] : null;
    switch ($field) {
      case 'input':
        add_settings_field(
          $args['id'],
          $label,
          [__CLASS__, 'draw_input'],
          $args['page'],
          $section,
          ['name' => $args['id']]
        );
        break;

      case 'multiple_input':
        add_settings_field(
          $args['id'],
          $label,
          [__CLASS__, 'draw_multiple_input'],
          $args['page'],
          $section,
          ['name' => $args['id']]
        );
        break;

      case 'hidden':
        add_settings_field(
          $args['id'],
          $label,
          [__CLASS__, 'draw_hidden'],
          $args['page'],
          $section,
          ['name' => $args['id']]
        );
        break;

      default:
        add_settings_field(
          $args['id'],
          $label,
          [__CLASS__, 'draw_input'],
          $args['page'],
          $section,
          ['name' => $args['id']]
        );
        break;
    }
  }

  static function draw_input($val)
  {
    $name = $val['name'];
    $class = isset($val['class']) ? $val['class'] : 'regular-text';
    ?>
      <input type="text" name="<?= $name ?>" class="<?= $class ?>" id="wta-<?= $name ?>" value="<?= esc_attr(get_option($name)) ?>" />
    <?php
  }

  static function draw_hidden($val){
    $name = $val['name'];
    ?>
      <input type="hidden" name="<?= $name ?>" id="wta-<?= $name ?>" value="<?= esc_attr(get_option($name)) ?>" />
      <script>
        jQuery('#wta-<?= $name ?>').closest('tr').hide();
      </script>
    <?php
  }

  static function draw_multiple_input($val)
  {
    $name = $val['name'];
    $valueArr = unserialize(get_option($name));
    $valueArr[] = '';
    ?>
      <input type="hidden" name="<?= $name ?>" id="wta-<?= $name ?>" value="<?= esc_attr(get_option($name)) ?>" />
      <div class="wta-multiple" data-id="wta-<?= $name ?>">
        <?php foreach ($valueArr as $n => $value) : ?>
          <div class="tablenav">
            <input type="text" value="<?= $value ?>" oninput="wtaCollectMultiple('<?= $name ?>')" />
            <?php if ($n !== 0) : ?>
              <input type="button" class="button action" onclick="wtaRemoveInputFromMultiple('<?= $name ?>')" value="Удалить">
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>
      <input type="button" class="button action" onclick="wtaAddInputToMultiple('<?= $name ?>')" value="Добавить">
      <script>
        if (!window.wtaCollectMultiple) {
          window.wtaCollectMultiple = (name) => {
            e = window.event;
            const input = document.getElementById(`wta-${name}`);
            const container = document.querySelector(`.wta-multiple[data-id="wta-${name}"]`);
            const valueArr = [];
            Array.from(container.querySelectorAll('input[type="text"]')).forEach(input => {
              if (input.value.length > 0) valueArr.push(input.value);
            });
            input.value = wtaSerialize(valueArr);
          };
          window.wtaRemoveInputFromMultiple = (name) => {
            e = window.event;
            const tablenav = e.target.closest('.tablenav');
            tablenav.remove();
            return wtaCollectMultiple(name);
          };
          window.wtaAddInputToMultiple = (name) => {
            const container = document.querySelector(`.wta-multiple[data-id="wta-${name}"]`);
            const tablenav = crElem('div', container, null, {
              'class': 'tablenav'
            });
            crElem('input', tablenav, null, {
              'type': 'text',
              'oninput': `wtaCollectMultiple('${name}')`
            });
            crElem('input', tablenav, null, {
              'class': 'button action',
              'type': 'button',
              'value': 'Удалить',
              'onclick': `wtaRemoveInputFromMultiple('${name}')`
            });
            return wtaCollectMultiple(name);
          };
        }
      </script>
    <?php
  }

  static function install_then_activate_plugin($slug, $plugin_zip)
  {
  if (self::is_plugin_installed($slug)) return false;

  $installed = self::install_plugin($plugin_zip);
  if (!is_wp_error($installed) && $installed) {
    $activated = activate_plugin($slug, '', false, true);
  } else return false;

  if (!is_wp_error($activated)) {
    return true;
  } else echo '<p>' . $activated->get_error_message() . '</p>';

  return false;
  }

  static function is_plugin_installed($slug)
  {
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();

    if (!empty($all_plugins[$slug])) {
      return true;
    } else {
      return false;
    }
  }

  static function install_plugin($plugin_zip)
  {
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    wp_cache_flush();

    $upgrader = new Plugin_Upgrader();
    $installed = $upgrader->install($plugin_zip);

    return $installed;
  }

  static function upgrade_plugin($plugin_slug)
  {
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    wp_cache_flush();

    $upgrader = new Plugin_Upgrader();
    $upgraded = $upgrader->upgrade($plugin_slug);

    return $upgraded;
  }
}
