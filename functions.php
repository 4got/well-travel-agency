<?php
/**
 * Well Travel Agency functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Well_Travel_Agency
 */

require get_template_directory() . '/inc/uderscores.php';
require get_template_directory() . '/class/wta_admin.php';


# adminpanel

$wta_admin_pages = [
	[
		'name' => 'agency',
		'fields' => [
			['name' => 'agency_name']
		]
	],
];

add_action( 'admin_menu', 'add_wta_menu' );
add_action( 'admin_init', 'page_init' );

function add_wta_menu(){
	add_menu_page(
		'Мое агентство', 
		'Мое агентство', 
		'manage_options', 
		'wta_agency', 
		function() {
			get_template_part( 'template-parts/admin/agency' );
		}, 'dashicons-admin-home', 100
	);
}


function page_init(){
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
						'id' => 'agency_name',
						'label' => 'Название агентства (Отображается в шапке сайта)',
					],
				],
			],
		],
	]);
}













// add_action( 'admin_init', 'register_wta_settings' );
// function register_wta_settings() {
// 	register_setting( 'wta', 'wta');
// 	add_settings_section(
// 		'wta-main', // ID
// 		'Главное', // Title
// 		function() {
// 			print 'Enter your settings below:';
// 		},
// 		'agency' // Page
// 	);
// 	add_settings_field(
// 		'agency_name',
// 		'Название агентства (Отображается в шапке сайта)',
// 		'draw_input',
// 		'agency',
// 		'wta-main',
// 		[
// 			'id' => 'wta-' . 'agency_name',
// 			'option_name' => 'agency_name',
// 		]
// 	);
// } 

// add_action( 'admin_menu', function() {
//   add_menu_page( 'Мое агентство', 'Мое агентство', 'edit_others_posts', 'agency', 
//   function() {
// 		get_template_part( 'template-parts/admin/agency' );
		
//   }, 'dashicons-admin-home', 100 );
// });





# frontpage

function get_tour_search() {
  ?> <div id="tvscw"></div> <?php
  wp_enqueue_script( 'tour_search', '//well.ru/local/modules/tour.search/script.js' );
}

function get_spo_tours() {
  echo 'spo_tours';
}

function get_my_tours() {
  echo 'my_tours';
}