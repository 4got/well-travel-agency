<?php 

class wta_widget extends WP_Widget {

	function __construct($args) {
		parent::__construct(
			$args['id'], 
			$args['name'],
			[
        'description' => $args['description'],
        'customize_selective_refresh' => true,
      ]
    );

    if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
      if (method_exists($this, 'add_widget_scripts')) {
        add_action('wp_enqueue_scripts', array( $this, 'add_widget_scripts' ));
      }
		}
  }
  
  function template() {
    if (method_exists($this, 'render')) {
      $this->render();
    }
  }

	function widget( $args, $instance ){
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if( $title )
      echo $args['before_title'] . $title . $args['after_title'];

    self::template();

		echo $args['after_widget'];
  }
}

class wta_tour_search extends wta_widget {
  function __construct() {
    parent::__construct([
      'id' => 'wta_tour_search',
      'name' => 'Поисковик туров',
      'description' => 'Классический поисковик туров Велл',
    ]);
  }

  function render() {
    ?><div id="tvscw" style="width: 100%;"></div><?php
  }

  function add_widget_scripts() {
		if( ! apply_filters( 'wta_tour_search', true, $this->id_base ) )
      return;
      
    wp_enqueue_script('wta_tour_search', '//well.ru/local/modules/tour.search/script.js', '', [], true);
	}
}

class wta_whywe extends wta_widget {
  function __construct() {
    parent::__construct([
      'id' => 'wta_whywe',
      'name' => 'Почему мы',
      'description' => '4 причины выбрать именно нас',
    ]);
  }

  function render() {
    get_template_part( 'template-parts/common/whywe' );
  }
}

class wta_advantages extends wta_widget {
  function __construct() {
    parent::__construct([
      'id' => 'wta_advantages',
      'name' => 'Наши преимущества',
      'description' => 'Еще 4 причины выбрать нас',
    ]);
  }

  function render() {
    get_template_part( 'template-parts/common/advantages' );
  }
}

class wta_vk extends wta_widget {
  function __construct() {
    parent::__construct([
      'id' => 'wta_vk',
      'name' => 'Виджет сообщества ВКонтакте',
      'description' => 'Группа вконтакте прямо на вашем сайте, повышает лояльность посетителей',
    ]);
  }

  function render() {
    if (!get_option('agency_vk_group')) return;

    ?><section class="wta-section wta-section-vk">
      <div class="wta-container" >
        <div class="wta-row">
          <div class="wta-col-6">
            <div class="wta-vk-banner">
              <h1>Мы в VK!</h1>
              <h5 class="m-vertical">Подписывайтесь на нас<br> и отдыхайте в лучших отелях<br> по самым скромным ценам!</h5>
              <p class="m-vertical">
                <a href="//vk.com/club<?= get_option('agency_vk_group') ?>" class="wta-button-main wta-button-large wta-icon wta-button-vk" target="_blank">Наша группа ВКонтакте</a>
              </p>
            </div>
          </div>
          <div class="wta-col-6">
            <div id="wta_vk_group" style="width: 100%;"></div>
          </div>
        </div>
      </div>
    </section><?php
  }
  
  function add_widget_scripts() {
		if (!apply_filters('vk_openapi', true, $this->id_base)) return;
      
    wp_enqueue_script('vk_openapi', '//vk.com/js/api/openapi.js?162');
    wp_register_script('wta_vk_group', get_stylesheet_directory_uri() . '/js/wta-vk-group-script.js', '', [], true);
    $params = [
      'group_id' => get_option('agency_vk_group')
    ];
    wp_localize_script('wta_vk_group', 'params', $params);
    wp_enqueue_script('wta_vk_group');
	}
}


function insert_widget_in_sidebar( $widget_id, $widget_data, $sidebar ) {
	$sidebars_widgets = get_option( 'sidebars_widgets', [] );
  $widget_instances = get_option( 'widget_' . $widget_id, [] );
  
	$numeric_keys = array_filter( array_keys( $widget_instances ), 'is_int' );
	$next_key = $numeric_keys ? max( $numeric_keys ) + 1 : 2;
	if ( ! isset( $sidebars_widgets[ $sidebar ] ) ) {
		$sidebars_widgets[ $sidebar ] = [];
	}
	$sidebars_widgets[ $sidebar ][] = $widget_id . '-' . $next_key;
  $widget_instances[ $next_key ] = $widget_data;
  
	update_option( 'sidebars_widgets', $sidebars_widgets );
	update_option( 'widget_' . $widget_id, $widget_instances );
}

function remove_widgets_in_sidebar( $sidebar ) {
  $sidebars_widgets = get_option( 'sidebars_widgets', [] );
  
  foreach ($sidebars_widgets[$sidebar] as $key => $widget) {
    if (preg_match("/^(.+?)-(\d+?)$/", $widget, $matches)) {
      $widget_id = $matches[1];
      $widget_key = $matches[2];
      $widget_instances = get_option( 'widget_' . $widget_id, [] );
      unset($widget_instances[$widget_key]);

      update_option( 'widget_' . $widget_id, $widget_instances );
    }
  }
  $sidebars_widgets[ $sidebar ] = [];

  update_option( 'sidebars_widgets', $sidebars_widgets );
}