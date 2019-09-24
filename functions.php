<?php

/**
 * Well Travel Agency functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Well_Travel_Agency
 */

require get_template_directory() . '/inc/uderscores.php';
require get_template_directory() . '/php/wta_admin.php';
require get_template_directory() . '/php/wta_widgets.php';



# public

add_action('wp_enqueue_scripts', 'add_wta_public_script');
function add_wta_public_script()
{
	wp_enqueue_style('font_awesome', get_template_directory_uri() . 'css/fa/css/all.css');
	wp_enqueue_style('open_sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap');
}

add_theme_support( 'customize-selective-refresh-widgets' );


# adminpanel

add_action('admin_enqueue_scripts', 'add_wta_admin_script');
function add_wta_admin_script() {
	wp_enqueue_script('wta_admin_script', get_template_directory_uri() . '/js/wta-admin-script.js');
}

add_action('admin_menu', 'add_wta_menu');
add_action('admin_init', 'page_init');
function add_wta_menu() {
	add_menu_page(
		'Мое агентство',
		'Мое агентство',
		'manage_options',
		'wta_agency',
		function () {
			get_template_part('template-parts/admin/agency');
		},
		'dashicons-admin-home',
		100
	);
}
function page_init() {
	wta_admin::draw_page([
		'id' => 'wta_agency',
		'sections' => [
			[
				'id' => 'wta_agency_main',
				'label' => 'Основные',
				'fields' => [
					[
						'id' => 'agency_id',
						'label' => 'ИД агентства',
					],
					[
						'id' => 'agency_name',
						'label' => 'Название агентства (Отображается в шапке сайта)',
					],
					[
						'id' => 'wta_site_initiated',
						'field' => 'hidden'
					],
					[
						'id' => 'wta_site_map',
						'field' => 'hidden'
					],
					[
						'id' => 'agency_phone',
						'label' => 'Телефоны агентства (Отображается в шапке сайта)',
						'field' => 'multiple_input'
					],
				],
			],
			[
				'id' => 'wta_agency_secondary',
				'label' => 'Второстепенные',
				'fields' => [
					[
						'id' => 'agency_description',
						'label' => 'Описание агентства',
					],
				],
			],
			[
				'id' => 'wta_agency_help',
				'label' => 'Помощь',
			],
		],
	]);
}



# site init

add_action('wp_ajax_wta_site_init', 'wta_site_init');
function wta_site_init() {
	# create pages
	
	foreach (get_pages() as $key => $post) {
		wp_delete_post($post->ID);
		echo '<p>Удалена страница "' . $post->post_title . '"</p>';
	}
	foreach (get_posts() as $key => $post) {
		wp_delete_post($post->ID);
		echo '<p>Удалена запись "' . $post->post_title . '"</p>';
	}

	$wta_site_map = [];
	$wta_initial_pages = [
		'home' => 'Главная',
		'news' => 'Новости туризма',
		'avia' => 'Авиабилеты',
		'geo' => 'Популярные направления',
		'spo' => 'Отборные туры',
		'hotels' => 'Отели',
		'contacts' => 'Контакты',
	];
	echo '<div class="wrap">';
	foreach ($wta_initial_pages as $key => $page) {
		$id = wp_insert_post([
			'post_title' => $page,
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type' => 'page'
		]);
		$wta_site_map[$key] = $id;
		echo '<p>Добавлена страница "' . $page . '"</p>';
	}
	echo '</div>';
	update_option('wta_site_map', serialize($wta_site_map));

	# homepage
	update_option('page_on_front', $wta_site_map['home']);
	update_option('show_on_front', 'page');
	update_post_meta( $wta_site_map['home'], '_wp_page_template', 'page-home.php' );
	echo '<p>Настройка главной страницы</p>';

	# install plugins
	wta_admin::install_then_activate_plugin(
		'github-updater-develop/github-updater.php',
		'https://github.com/afragen/github-updater/archive/develop.zip'
	);
	wta_admin::install_then_activate_plugin(
		'advanced-custom-fields-pro-master/acf.php',
		'https://github.com/wp-premium/advanced-custom-fields-pro/archive/master.zip'
	);

	# insert widgets
	insert_widget_in_sidebar('wta_tour_search', '', 'home');
	insert_widget_in_sidebar('wta_whywe', '', 'home');
	insert_widget_in_sidebar('wta_advantages', '', 'home');
	echo '<p>Добавление виджетов на главную страницу</p>';

	echo '<p>Готово!</p>';

	wp_die();
}



# widgets

add_action( 'widgets_init', 'register_wta_widgets' );
function register_wta_widgets() {
	register_sidebar([
		'name'          => 'Главная',
		'id'            => 'home',
		'description'   => '',
		'class'         => '',
		// 'before_widget' => '<section id="%1$s" class="wta-section widget %2$s">',
		// 'after_widget'  => "</section>\n",
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => "</h2>\n",
	]);

	register_widget( 'wta_tour_search' );
	register_widget( 'wta_whywe' );
	register_widget( 'wta_advantages' );

	$deprecated_widgets = [
		'WP_Widget_Calendar',         // Календарь
		'WP_Widget_Archives',         // Архивы
		'WP_Widget_Links',            // Ссылки
		'WP_Widget_Meta',             // Мета виджет
		'WP_Widget_Recent_Comments',  // Последние комментарии
		'WP_Widget_RSS',              // RSS
		'WP_Widget_Tag_Cloud',        // Облако меток
	];
	foreach ($deprecated_widgets as $widget) {
		unregister_widget( $widget );
	}
}



#plugins

add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );
function filter_plugin_updates( $value ) {
	unset( $value->response['advanced-custom-fields-pro-master/acf.php'] );
	return $value;
}


// function wta_01_register_block() {
// 	wp_register_script(
// 		'wta-01',
// 		get_template_directory_uri() . '/plugins/wta_widgets/script.js',
// 		array( 'wp-blocks', 'wp-element' )
// 	);

// 	register_block_type( 'wta/example-01-basic', array(
// 		'editor_script' => 'wta-01',
// 	));
// }
// add_action( 'init', 'wta_01_register_block' );



# form

// TODO



# captcha

// TODO



# frontpage

function get_tour_search()
{
	?>
		<div id="tvscw"></div>
	<?php
	wp_enqueue_script('tour_search', '//well.ru/local/modules/tour.search/script.js');
}

function get_spo_tours()
{
	echo 'spo_tours';
}

function get_my_tours()
{
	echo 'my_tours';
}

function get_vk()
{
	echo 'my_vk';
}
