<?php
$wta_site_initiated = get_option('wta_site_initiated');
?>

<div class="wrap">
  <h1><?php echo get_admin_page_title() ?></h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('wta_agency');
    do_settings_sections('wta_agency');
    submit_button();
    ?>
  </form>
  
    <?php if (!$wta_site_initiated) : ?>
      <input type="button" class="button action" value="Выполнить первоначальную настройку" onclick="wtaSiteInit()">
      <script>
        const wtaSiteInit = (e) => {
          e = e || window.event;
          e.preventDefault();
          const data = {
            action: 'wta_site_init',
			      whatever: 1234
          };
          jQuery.post( '<?= admin_url('admin-ajax.php') ?>', data, (response) => {
            alert('Получено с сервера: ' + response);
          });
        }
      </script>
    <?php endif ?>
  
</div>