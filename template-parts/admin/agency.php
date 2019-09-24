<?php

global $wp_settings_sections;
$wta_site_initiated = get_option('wta_site_initiated');

if (!isset($_GET['tab'])) $_GET['tab'] = 'wta_agency_main';

function the_active_tab($name) {
  if ($_GET[ 'tab' ] == $name) echo 'nav-tab-active';
}
function is_active_tab($name) {
  return $_GET[ 'tab' ] == $name;
}
?>

<section class="wrap">
  <h1><?php echo get_admin_page_title() ?></h1>

  <nav class="nav-tab-wrapper">
    <?php foreach ($wp_settings_sections['wta_agency'] as $n => $section): ?>
      <a href="?page=wta_agency&tab=<?= $section['id'] ?>" class="nav-tab <?php the_active_tab($section['id']) ?>"><?= $section['title']  ?></a>
    <?php endforeach ?>
    
  </nav>

  <?php if (is_active_tab('wta_agency_help')): 
    get_template_part('template-parts/admin/agency-help');
  ?>

  <?php else: ?>

    <?php if (is_active_tab('wta_agency_main')/* !$wta_site_initiated */) : ?>
      <h2>Установить "Мое агенстство ВЕЛЛ"</h2>
      <div id="wtaSiteInitResponse"></div>
      <a class="button action button-primary" onclick="wtaSiteInit()">Выполнить первоначальную настройку</a>
      <p>Внимание! это действие удалит всю информацию с сайта</p>
      <script>
        const wtaSiteInit = () => {
          const e = window.event;
          // e.target.(html)
          e.target.classList.add('updating-message');
          jQuery.post('<?= admin_url('admin-ajax.php') ?>', {
            action: 'wta_site_init',
          }, statusHtml => {
            jQuery('#wtaSiteInitResponse').html(statusHtml);
            e.target.nextElementSibling.remove();
            e.target.onclick = () => location.reload();
            e.target.classList.remove('updating-message');
            e.target.textContent = "Обновить страницу";
          });
        }
      </script>
      <hr>
    <?php endif; ?>

    <form method="post" action="options.php">

      <?php
      settings_fields('wta_agency');
      foreach ($wp_settings_sections['wta_agency'] as $n => $section): ?>
          
        <?php if (is_active_tab($section['id'])): ?>

          <h2><?= $section['title']  ?></h2>
          <table class="form-table" role="presentation">
            <?php do_settings_fields('wta_agency', $section['id']) ?>
          </table>

        <?php else: ?>

          <div style="display: none">
            <?php do_settings_fields('wta_agency', $section['id']) ?>
          </div>

        <?php endif;

      endforeach;
      submit_button();
      ?>

    </form>
  <?php endif ?>

</section>