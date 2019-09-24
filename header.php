<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Well_Travel_Agency
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="<?= get_template_directory_uri() ?>/favicon.png" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'well-travel-agency'); ?></a>

		<header id="masthead" class="site-header wta-section">

			<div class="wta-container">
				<div class="wta-row">
					<div class="wta-col-3">
						<a class="header-logo" href="<?php echo esc_url(home_url('/')); ?>"></a>
					</div>
					<div class="header-contacts wta-col-6">
						<h1 class="wta-icon site-title"><?= get_option('agency_name') ?></h1>
						<?php
						$phoneArr = unserialize(get_option('agency_phone'));
						?>
						<div class="wta-icon site-phone">
							<div>
								<?php foreach ($phoneArr as $n => $phone) :
									$phoneClean = preg_replace('/\D*/', '', $phone);
									if (substr($phoneClean, 0, 1) == 8) $phoneClean[0] = 7;
									?>
									<a href="tel:+<?= $phoneClean ?>"><?= $phone ?></a><?= $phone !== end($phoneArr) ? ',' : '' ?>
								<?php endforeach ?>
							</div>
						</div>

					</div><!-- .site-branding -->
					<div class="wta-col-3 header-buttonset">
						<button class="wta-button-main wta-button-large formPhoneToggler">Заказать звонок</button>
						<button class="wta-button-second wta-button-large formDefaultToggler">Написать нам</button>
					</div>
				</div>
			</div>



			<nav id="site-navigation" class="main-navigation wta-container">
				<button class="menu-toggle wta-button-main" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e('Меню', 'well-travel-agency'); ?></button>
				<?php
				wp_nav_menu(array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				));
				?>
			</nav><!-- #site-navigation -->

		</header><!-- #masthead -->

		<div id="content" class="site-content">