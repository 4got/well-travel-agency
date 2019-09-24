<?php
/**
 * Template Name: Home
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Well_Travel_Agency
 */

get_header();
?>


<main class="widget-area">
	<?php dynamic_sidebar( 'home' ); ?>
</main>


<?php 
// get_tour_search();

// get_template_part( 'template-parts/common/whywe' );

// get_spo_tours();

// get_vk();

get_footer();
