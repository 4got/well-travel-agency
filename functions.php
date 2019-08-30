<?php
/**
 * Well Travel Agency functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Well_Travel_Agency
 */

require get_template_directory() . '/inc/uderscores.php';


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