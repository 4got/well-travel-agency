<?php 

class wta_admin {

  static function draw_page($page) {
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
        foreach ($section['fields'] as $field) {
          $field['page'] = $id;
          $field['section'] = $section_id;
          self::add_field($field);
        }
      }
    }
  }

  static function add_field($args) {
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

  static function draw_input( $val ){
    $name = $val['name'];
    $class = isset($val['class']) ? $val['class'] : 'regular-text';
    ?>
    <input 
      type="text" 
      name="<?= $name ?>"
      class="<?= $class ?>"
      id="wta-<?= $name ?>" 
      value="<?= esc_attr( get_option($name) ) ?>" 
    /> 
    <?php
  }

  static function draw_hidden( $val ){
    $name = $val['name'];
    ?>
    <input 
      type="hidden" 
      name="<?= $name ?>"
      id="wta-<?= $name ?>" 
      value="<?= esc_attr( get_option($name) ) ?>" 
    />
    <script>
      jQuery('#wta-<?= $name ?>').closest('tr').hide();
    </script>
    <?php
  }

  
}
