<div class="wrap">
  <h1><?php echo get_admin_page_title() ?></h1>
  <form method="post" action="options.php">
  <?php
    settings_fields( 'wta_agency' );
    do_settings_sections( 'wta_agency' );
    submit_button();
  ?>
  </form>
</div>