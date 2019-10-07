<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( 'WC_Settings_RH_Easy_New', false ) ) {
	return new WC_Settings_RH_Easy_New();
}

/**
 * WC_Settings_RH_Easy_New.
 */
class WC_Settings_RH_Easy_New {

	/**
	 * Constructor.
	 */
	public function __construct() {
        // Hook into the admin menu
        add_action( 'admin_menu', array( $this, 'settings_page' ) );
    }

    public function settings_page() {
        // Add the menu item and page
        $page_title =  __( 'ROI Hunter Easy', 'roi-hunter-easy' );
        $menu_title =  __( 'ROI Hunter Easy', 'roi-hunter-easy' );
        $capability = 'manage_options';
        $slug = 'roi-hunter-easy';
        $callback = array( $this, 'settings_page_content' );
        $icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIj4gIDxnIGZpbGw9IiNlZWUiPiAgICA8cGF0aCBkPSJNMzAzLjA1NiAxODcuMzA2YTMzLjUyNiAzMy41MjYgMCAwIDAtMTMuMDQ0LTEzLjIzNyA2MS43MTUgNjEuNzE1IDAgMCAwLTIwLjIxOS02Ljk5OGgtLjAxNmExNDguNTMyIDE0OC41MzIgMCAwIDAtMjUuNjg1LTIuMDY0Yy0xMC4wNCAwLTE5LjM1OS4zNzYtMjcuOTU4IDEuMTI4djg0LjY0OGgyMC43NjdjMTAuMjQzLjA5MSAyMC40OC0uNTQgMzAuNjM1LTEuODg3YTYzLjk3MiA2My45NzIgMCAwIDAgMjIuMTA1LTYuODA0IDMyLjk2NCAzMi45NjQgMCAwIDAgMTMuNDE0LTEzLjIzN2MzLjAyMS01LjUwNCA0LjUzMS0xMi41NTUgNC41MzEtMjEuMTU0YTQyLjA2IDQyLjA2IDAgMCAwLTQuNTMtMjAuMzk1eiIvPiAgICA8cGF0aCBkPSJNNDQwLjcwMSA3MS4zMTRsLTI1LjEwNyAyNS4xMDdDMzIwLjc1OCA0LjA2OCAxNjkuMzY3IDQuMzgyIDc0LjkzIDk3LjM4NmMtOTUuODAzIDk0LjM0OS05Ni45ODEgMjQ4LjQ5Ny0yLjYzMiAzNDQuM2wyNS4wODQtMjUuMDg0Yzk1LjE1NyA5NC4wOTYgMjQ4LjU2OCA5My43OCAzNDMuMzE5LS45NzEgOTUuMDgxLTk1LjA4MSA5NS4wODEtMjQ5LjIzNiAwLTM0NC4zMTd6TTMyMC4wNjcgMzg4LjM1YTU0OS4zNTEgNTQ5LjM1MSAwIDAgMC0xNS40OTUtMjcuNDEgODkxLjU0NSA4OTEuNTQ1IDAgMCAwLTE2LjgxNi0yNi44M2MtNS42NTQtOC43MDYtMTEuMjYtMTYuODk2LTE2LjgxNi0yNC41NzEtNS41NTgtNy42NzUtMTAuNzIzLTE0LjUzOC0xNS40OTUtMjAuNTktMy41MzEuMjQxLTYuNTYzLjM4Ny05LjA3Ny4zODdoLTMwLjI5NnY5OC45OThoLTQ3LjYyOVYxMzAuMjQ1YTI1NS45OTkgMjU1Ljk5OSAwIDAgMSAzNy4wODQtNS4wOTVsLS4wNDktLjAxN2MxMy4xMDQtLjg5MiAyNC44MTktMS4zMzggMzUuMTQ5LTEuMzM4IDM3Ljc4Mi4wMTEgNjYuNjkxIDYuOTQ0IDg2LjcyOCAyMC43OTkgMjAuMDM2IDEzLjg1NSAzMC4wNTUgMzUuMDE1IDMwLjA1NSA2My40NzgtLjAxMSAzNS41MjUtMTcuNTIxIDU5LjU5My01Mi41MyA3Mi4yIDQuNzk0IDUuNzUxIDEwLjIxMSAxMi44MDMgMTYuMjUyIDIxLjE1NHMxMi4yMTEgMTcuMzY1IDE4LjUxIDI3LjAzOWE1ODQuOTYyIDU4NC45NjIgMCAwIDEgMTguMTM5IDI5Ljg0NWM1Ljc5NCAxMC4yIDEwLjk1OSAyMC4yMTQgMTUuNDk1IDMwLjAzOGgtNTMuMjA5eiIvPiAgPC9nPjwvc3ZnPg==';
        $position = 100;

        add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    public function settings_page_content() {

        $helper = new RH_Easy_Helper();
        $applicationConfig = $helper->get_config();

        wp_enqueue_script( 'roi-hunter-easy-admin', RH_EASY_URL . 'assets/js/admin.min.js' );
        wp_enqueue_style( 'roi-hunter-easy-admin', RH_EASY_URL . 'assets/css/admin.min.css' );

        ?>

        <script type="application/javascript">
            let goostavApplicationConfig = JSON.parse('<?= json_encode($applicationConfig) ?>');
        </script>

        <div class="roi-paper">
            <h2><?= __( 'Welcome to ROI Hunter Easy!', 'roi-hunter-easy' ) ?></h2>
            <p><?= __( 'Thank you for joining our ever-growing <strong>community of +15000 merchants.</strong> has your journey just begun? Do you have an established store? Do not worry we have you covered, we help merchants of all sizes grow.', 'roi-hunter-easy' ) ?></p>
            <p><?= __( 'To automatize advertising and <strong>start growing your sales,</strong> just go hit the button below.', 'roi-hunter-easy' ) ?></p>
            <div class="button-wrap">
                <a id="roi-goto-goostav">
                    <?= __( 'Open ROI Hunter Easy', 'roi-hunter-easy' ) ?>
                </a>
            </div>
        </div>

        <?php
    }

}

return new WC_Settings_RH_Easy_New();
