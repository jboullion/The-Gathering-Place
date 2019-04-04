<?php
/**
 * PK Swiss Army Knife administration view.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">

    <?php screen_icon( 'options-general' ); ?>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'pk-swiss-army-knife' );
            do_settings_sections( 'pk-swiss-army-knife' );
            submit_button();
        ?>
    </form>

</div>
