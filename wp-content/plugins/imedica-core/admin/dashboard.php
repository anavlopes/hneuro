<?php
$demos = wp_remote_get( 'http://imedicaassets.brainstormforce.com/demos/imedica/' ); ?>
<style id="imedica_core_css">
  .error_message {
    margin-top: 40px;
  }

  .error_message p {
    font-size: 15px;
  }

  .error_message .left-margin {
    margin-left: 30px;
  }

  #setting-error-tgmpa, .rs-update-notice-wrap, .updated.notice.is-dismissible {
    display: none !important;
  }
</style>

<?php if ( is_wp_error( $demos ) ) {
  $demo_error = (array) $demos;
  echo "<div class='error_message'>";
  echo "<p>";
  echo "Hey, It seems the demo data server, <i><a href='http://imedicaassets.brainstormforce.com/demos/imedica/'>http://imedicaassets.brainstormforce.com/demos/imedica/</a></i>  is unreachable from from  your site.";
  echo "<p class='left-margin'>";
    echo "1. Sometimes, simple page reload fixes any temporary issues, No kidding!";
    echo "</p>";
    echo "<p class='left-margin'>";
    echo "2. If that does not work, You will need to talk to your server administrator and check if demo server is being blocked by the firewall!";
  echo "</p>";
  echo "<p>";
  echo 'Error code for further debugging - ';
  echo "</p>";
  echo "<pre>";
  print_r( $demo_error['errors'] );
  echo "</pre>";
  echo "<p>";
  echo "Meanwhile, You can open up a <a href='https://support.brainstormforce.com/dashboard/' target='_blank'>Support Ticket</a> on out support portal, and we will help you to get the demo data on your site using a manual procedure.";
  echo "</p>";
  echo "</div>";
  die();
} else {
  $demos = (array) json_decode( $demos['body'] );
}
if ( is_multisite() ) {
  if ( ! defined( 'BSF_REGISTRATION_URL' ) ) {
    define( "BSF_REGISTRATION_URL", network_admin_url() . 'admin.php?page=bsf-registration' );
  }
  if ( ! defined( 'IMEDICA_EXTENSION_URL' ) ) {
    define( "IMEDICA_EXTENSION_URL", network_admin_url() . 'admin.php?page=bsf-extensions' );
  }
} else {
  if ( ! defined( 'IMEDICA_EXTENSION_URL' ) ) {
    define( "BSF_REGISTRATION_URL", get_admin_url() . 'admin.php?page=bsf-registration' );
  }
  if ( ! defined( 'IMEDICA_EXTENSION_URL' ) ) {
    define( "IMEDICA_EXTENSION_URL", get_admin_url() . 'admin.php?page=bsf-extensions' );
  }
}
?>
<style>
  #setting-error-tgmpa, .rs-update-notice-wrap, .updated.notice.is-dismissible {
    display: none !important;
  }
</style>
<div class="wrap">
  <h2>
    <?php _e( 'Import Demo Content', 'imedica' ); ?>
    <span class="title-count theme-count"><?php echo count( $demos ); ?></span></h2>
  <?php
  $limit           = (int) ini_get( 'memory_limit' );
  $wp_limit        = (int) WP_MEMORY_LIMIT;
  $wp_max_limit    = (int) WP_MAX_MEMORY_LIMIT;
  $execustion_time = ini_get( 'max_execution_time' );
  $memory_notice   = $plugins_notice = false;
  if ( $limit < 256 || $wp_limit < 256 || $wp_max_limit < 256 || $execustion_time < 300 ) {
    $memory_notice = true;
  }

  if ( function_exists( 'bsf_exension_installer_url' ) ) {
    $plugin_installer_url = bsf_exension_installer_url( '10395942' );
  } else {
    $plugin_installer_url = IMEDICA_EXTENSION_URL;
  }

  if ( ! is_plugin_active( 'revslider/revslider.php' ) ) {
    $plugins_notice = true;
  }
  if ( $memory_notice || $plugins_notice ) {
    ?>
    <div class="updated-nag notice plugin-requirements ic-demo-import-nag"
         style="float:left; width:97.5%; position: relative;">
      <p class="ic-demo-import-nag-title">To import demo data flawlessly, your hosting server should have decent
        memory configuration.<br> Further, you must have some plugins installed.</p>
      <?php if ( $memory_notice ) { ?>
      <div class="ic-nag-list-section">
        <h3><span class="dashicons dashicons-admin-generic"></span> System Requirements</h3>

        <p class="ic-nag-list-main">Please check that you have the following configuration for your website.</p>
        <ul>
          <?php if ( $limit < 256 ) { ?>
            <li>PHP Memory Limit: Required - <strong>256 MB</strong> / Currently - <strong
                class="ic-danger-tag"><?php echo $limit; ?> MB</strong></li>
          <?php }
          if ( $execustion_time < 300 ) { ?>
            <li>PHP Execution Time: Required - <strong>300ms</strong> / Currently - <strong
                class="ic-danger-tag"><?php echo $execustion_time; ?>ms</strong></li>
          <?php }
          if ( $wp_limit < 256 ) { ?>
            <li>WP_MEMORY_LIMIT: Required - <strong>256 MB</strong> / Currently - <strong
                class="ic-danger-tag"><?php echo $wp_limit; ?> MB</strong></li>
          <?php }
          if ( $wp_max_limit < 256 ) { ?>
            <li>WP_MAX_MEMORY_LIMIT: Required - <strong>256 MB</strong> / Currently - <strong
                class="ic-danger-tag"><?php echo $wp_max_limit; ?> MB</strong></li>
          <?php } ?>
        </ul>
        <p><a class="ic-demo-import-link" href="http://docs.brainstormforce.com/increasing-memory-limit/"
              target="_blank">Learn More</a></p>
      </div>
      <div class="ic-nag-list-section">
        <?php } ?>
        <?php if ( $plugins_notice ) { ?>
          <h3><span class="dashicons dashicons-admin-plugins"></span> Plugin Requirements</h3>
          <p class="ic-nag-list-main">Please make sure that you have the following plugins installed and
            activated.</p>
          <ul>
            <li>Revolution Slider</li>
          </ul>
          <p><a class="ic-demo-import-link" href="<?php echo esc_attr( $plugin_installer_url ); ?>"
                target="_blank">Install Plugins</a></p>
        <?php } ?>
        <button type="button" class="notice-dismiss" onclick="jQuery('.plugin-requirements').slideUp('fast');">
          <span class="screen-reader-text">Dismiss this notice.</span></button>
      </div>
      <div class="ic-demo-import-nag-bottom">
        <p>Without meeting minimum requirements stated above, the demo data will be imported. However it can be
          partial.</p>
      </div>
    </div>
  <?php } ?>
  <div id="msg" style="clear: both;margin-bottom: 15px;"></div>
  <div class="theme-browser rendered">
    <div class="themes">
      <?php
      foreach ( $demos as $demo => $options ) {
        $options    = (array) $options;
        $title      = $options['title'];
        $url        = $options['url'];
        $screenshot = $options['screenshot'];
        $live       = $options['live'];
        ?>
        <div class="theme">
          <div class="theme-screenshot" onclick="window.open('<?php echo esc_attr( $url ); ?>');"><img
              src="<?php echo esc_attr( $screenshot ); ?>" alt="<?php echo esc_attr( $title ); ?>"></div>
          <?php if ( $live ) { ?>
            <span class="more-details view-demo" onclick="window.open('<?php echo esc_attr( $url ); ?>');">
        <?php _e( "View Demo", "imedica" ); ?>
        </span> <span class="more-details installing-demo" style="display:none;">
        <?php _e( "Installing...", "imedica" ); ?>
        </span>
            <h3 class="theme-name"> <?php echo esc_attr( $title ); ?> </h3>
            <div class="theme-actions"><a class="button button-primary install-demo"
                                          data-demo="<?php echo esc_attr( $demo ); ?>" href="#">
                <?php _e( "Install", "imedica" ); ?>
              </a></div>
          <?php } else { ?>
            <span class="more-details view-demo" style="display:block; opacity: 1;">
        <?php _e( "Coming Soon!!!", "imedica" ); ?>
        </span>
            <h3 class="theme-name"> <?php echo esc_attr( $title ); ?> </h3>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
    <br class="clear">
  </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    jQuery("body").addClass('themes-php');
    jQuery(".install-demo").click(function (e) {
      e.preventDefault();
      var process = window.confirm('Executing Demo Import will make your site similar as ours. Please bear in mind -\n\n1. It is strongly recommended to run Demo Import on a fresh WordPress installation.\n\n2. If you have any existing pages, posts, menus & other data, it will be overwritten.\n\n3. Some copyrighted images won\'t be imported. Instead they will be replaced with placeholders.');
      if (process) {
        jQuery(this).parents('.theme').find(".view-demo").css('display', 'none');
        jQuery(this).parents('.theme').find(".installing-demo").css({'display': 'block', 'opacity': '1'});
        jQuery(".theme-screenshot").css({'opacity': '0.4', 'background': '#fff'});
        jQuery(".theme-screenshot > img").css({'opacity': '1'});
        jQuery(".theme-actions").css('display', 'none');
        var demo = jQuery(this).data('demo');
        var data = {action: 'imedica_ajax_import_data', demo: demo};
        var msg = jQuery('#msg');
        jQuery.ajax({
          url: ajaxurl,
          data: data,
          type: 'POST',
          dataType: 'HTML',
          success: function (result) {
            console.log(result);
            jQuery(".view-demo").removeAttr('style');
            jQuery(".installing-demo").css('display', 'none');
            jQuery(".theme-screenshot").removeAttr('style');
            jQuery(".theme-screenshot > img").removeAttr('style');
            jQuery(".theme-actions").removeAttr('style');
            msg.html('<div class="updated"><p>Data imported successfully! Please check your site.</p></div>');
            msg.slideDown();
            setTimeout(function () {
              msg.slideUp();
            }, 5000);

          },
          error: function (err) {
            jQuery(".view-demo").removeAttr('style');
            jQuery(".installing-demo").css('display', 'none');
            jQuery(".theme-screenshot").removeAttr('style');
            jQuery(".theme-screenshot > img").removeAttr('style');
            jQuery(".theme-actions").removeAttr('style');
            msg.html('<div class="update-nag">Something went wrong! Please check your site for the dummy data. If the import was not successful, try resetting and importing the data again.</div>');
            msg.slideDown();
            setTimeout(function () {
              msg.slideUp();
            }, 5000);
          },
          timeout: 900000
        });
      }
    });
  });
</script>
