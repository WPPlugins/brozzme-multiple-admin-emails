<?php
defined('ABSPATH') or exit();

class bfsl_page_plugins {


    public function __construct() {

        add_action('admin_menu', array($this, 'add_admin_plugins_groupe_menu'));
        add_action('admin_head', array($this, 'add_admin_plugins_groupe_css'));


    }

    // ...
    public function add_admin_plugins_groupe_menu() {
        if (empty($GLOBALS['admin_page_hooks'][BFSL_PLUGINS_DEV_GROUPE_ID])):
            add_menu_page(BFSL_PLUGINS_DEV_GROUPE, 
                BFSL_PLUGINS_DEV_GROUPE, 
                'manage_options', 
                BFSL_PLUGINS_DEV_GROUPE_ID, 
                array($this, 'add_admin_plugins_groupe_page'),
                'dashicons-screenoptions',
                61
            );
        endif;
    }

    // ...
    public function add_admin_plugins_groupe_page() {
        ?>
        <div class="wrap">


            <a class="apropos-link" style="text-decoration:none; font-weight:bold;" href="#openModal">
                <span class="dashicons dashicons-info"></span>
            </a>

            <div id="openModal" class="apropos">
                <div>
                    <a href="#close" title="Close" class="close"><span class="dashicons dashicons-dismiss"></span></a>
                    <h2><?php _e('About', BMAE_TEXT_DOMAIN) ?></h2>
                    <p><span class="dashicons dashicons-admin-users"></span> Benoît Faure - benoti</p>
                    <p><span class="dashicons dashicons-email-alt"></span> dev@brozzme.com</p>
                    <p><span class="dashicons dashicons-admin-site"></span> <?php _e('More information', BMAE_TEXT_DOMAIN); ?> <a href="https://brozzme.com/" target="_blank">Brozzme</a></p>
                    <p><span class="dashicons dashicons-admin-tools"></span> <?php _e('Development for the web: WordPress plugins, theming, Php front & back-end developper...', BMAE_TEXT_DOMAIN) ?></p>
                    <p><span class="dashicons dashicons-networking"></span> <?php _e('Contact slack: ', BMAE_TEXT_DOMAIN) ?>@benoti</p>

                </div>
            </div>
            <h2><?= __('Plugins by Brozzme.', BMAE_TEXT_DOMAIN); ?></h2>

            <p><?= __('A dedicated set of tools to improve your website. Focus on design and simply tricks.', BMAE_TEXT_DOMAIN); ?></p>
            <?php

            require_once( BMAE_DIR . 'admin/remote_plugins.php');
            $oPlugins = new bfsl_remote_plugins();
            
            ?>
            <div class="accordeon">
                <?php
                if (!empty($oPlugins->plugins['actif'])):
                    foreach ($oPlugins->plugins['actif'] as $plugin):
                        $titre = ucwords(str_replace(array('-'), ' ', $plugin[0]));
                        $file = ABSPATH . 'wp-content/plugins/' . $plugin[0] . '/' . $plugin[0] . '.php';
                        ?>
                        <a class="titre" style="border-left:5px solid #0072aa;" href="#seccion-<?= $plugin[0] ?>">
                            <?php echo $titre ?>
                            <span style="font-weight:normal; float:right;"><span class="dashicons dashicons-admin-plugins" <?= ($plugin[1][0] === $this->get_version($file)) ? 'style="color:LimeGreen;"' : 'style="color:Tomato;"' ?>></span> <?= $this->get_version($file); ?></span>
                        </a>
                        <p id="seccion-<?= $plugin[0] ?>" style="border-left: 5px solid #0072aa;">
                            <?php echo '<a class="link" href="' . admin_url('admin.php?page=' . $plugin[0]) . '"><span class="dashicons dashicons-admin-settings"></span></a>'; ?>
                            <span class="contentTitre"><?= $titre ?></span>
                            <?= ($plugin[1][0] === $this->get_version($file)) ? '<span class="dashicons dashicons-flag" style="color:LimeGreen;"></span> ' : '<span class="dashicons dashicons-flag" style="color:Tomato;"></span> ' ?>
                            <?= $plugin[1][0] . ' ' . __('[Latest version]', BMAE_TEXT_DOMAIN) . ' - <a href="' . $plugin[1][1] . '">' . __('Plugin page', BMAE_TEXT_DOMAIN) . '</a><br />'; ?>
                            <?= $plugin[1][2] ?>
                        </p>
                        <?php
                    endforeach;
                    ?>
                    <br />
                    <?php
                endif;

                if (!empty($oPlugins->plugins['noActif'])):
                    foreach ($oPlugins->plugins['noActif'] as $plugin):
                        $titre = ucwords(str_replace(array('-'), ' ', $plugin[0]));
                        $file = ABSPATH . 'wp-content/plugins/' . $plugin[0] . '/' . $plugin[0] . '.php';
                        ?>
                        <a class="titre" style="border-left:5px solid Tomato;" href="#seccion-<?= $plugin[0] ?>">
                            <?= $titre ?>
                            <span style="font-weight:normal; float:right;"><span class="dashicons dashicons-admin-plugins" <?= ($plugin[1][0] === $this->get_version($file)) ? 'style="color:LimeGreen;"' : 'style="color:Tomato;"' ?>></span> <?= $this->get_version($file); ?></span>
                        </a>
                        <p id="seccion-<?= $plugin[0] ?>" style="border-left: 5px solid Tomato;">
                            <?php echo '<a class="link" href="' . admin_url('admin.php?page=' . $plugin[0]) . '"><span class="dashicons dashicons-admin-settings"></span></a>'; ?>
                            <span class="contentTitre"><?= $titre ?></span>
                            <?= ($plugin[1][0] === $this->get_version($file)) ? '<span class="dashicons dashicons-flag" style="color:LimeGreen;"></span> ' : '<span class="dashicons dashicons-flag" style="color:Tomato;"></span> ' ?>
                            <?= $plugin[1][0] . ' ' . __('[Latest version]', BMAE_TEXT_DOMAIN) . '  - <a href="' . $plugin[1][1] . '">' . __('Plugin page', BMAE_TEXT_DOMAIN) . '</a><br />'; ?>
                            <?= $plugin[1][2] ?>
                        </p>
                        <?php
                    endforeach;
                    ?>
                    <br />
                    <?php
                endif;

                if (!empty($oPlugins->plugins['noInstall'])):
                    foreach ($oPlugins->plugins['noInstall'] as $plugin):
                        $titre = ucwords(str_replace(array('-'), ' ', $plugin[0]));
                        ?>
                        <a class="titre" style="border-left:5px solid #32373c;" href="#seccion-<?= $plugin[0] ?>">
                            <?= $titre ?>
                            <span style="font-weight:normal; float:right;"><span class="dashicons dashicons-migrate"></span> <?= $plugin[1][0] ?></span>
                        </a>
                        <p id="seccion-<?= $plugin[0] ?>" style="border-left: 5px solid #32373c;">
                            <span class="contentTitre"><?= $titre ?></span>
                            <span class="dashicons dashicons-flag"></span>
                            <?= $plugin[1][0] . ' - <a href="' . $plugin[1][1] . '">' . __('Plugin page', BMAE_TEXT_DOMAIN) . '</a><br />' ?>
                            <?= $plugin[1][2] ?>
                        </p>
                        <?php
                    endforeach;

                endif;
                ?>
            </div>
        </div>
        <?php
    }

    // ...
    public function get_version($p) {
        $plugin_data = get_plugin_data($p);
        $plugin_version = $plugin_data['Version'];
        return $plugin_version;
    }

    // ...
    public function add_admin_plugins_groupe_css() {
        echo '<style>
            #toplevel_page_bfsl-brozzme-plugins .wp-submenu .wp-first-item {
                display: none;
            }

            .accordeon {
                background-color: #eee;
            }
            .accordeon a{
                text-decoration: none;
            }
            .accordeon a.titre {
                color:#333;
                padding: 1em;
                display: block;
                font-weight: bold;
                background-color: #f9f9f9;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            .accordeon a.link {
                float: left;
                margin-top: 6px;
                margin-right: 5px;
                font-weight: bold;
            }
            .accordeon p {
                padding: 1em;
                margin: 0;
                display: none;
                background-color: #f7fcfe;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            .contentTitre{
                display: block;
                font-size: 25px;
                font-weight: bold;
                line-height: 30px;
                margin-bottom: 15px;
            }
            .accordeon p:target {
                display: block;
            }
            .apropos-link{
                position: relative;
                float: right;
            }
            .apropos {
                    position: fixed;
                    font-family: Arial, Helvetica, sans-serif;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0,0,0,0.8);
                    z-index: 99999;
                    opacity:0;
                    -webkit-transition: opacity 250ms ease-in;
                    -moz-transition: opacity 250ms ease-in;
                    transition: opacity 250ms ease-in;
                    pointer-events: none;
            }

            .apropos:target {
                    opacity:1;
                    pointer-events: auto;
            }

            .apropos > div {
                width: 400px;
                background: #fff;
                margin: 10% auto;
                position: relative;
                border-radius: 10px;
                padding: 5px 20px 13px 20px;
                background: -o-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
                background: -moz-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
                background: -webkit-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
            }

            .close {
                    top: 10px;
                    right: 10px;
                    font-weight: bold;
                    position: absolute;
                    text-align: center;
                    text-decoration: none;
            }

            .close:hover { color: #333; }
        </style>';
    }

}

new bfsl_page_plugins();


