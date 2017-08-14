<?php

defined('ABSPATH') or exit();

class bfsl_remote_plugins {

    public $url;
    public $body;
    public $status;
    public $remote = [];
    public $plugins = [];

    /**
     * 
     */
    public function __construct() {
        $this->url = 'https://api.brozzme.com/plugins.php?lang=' . get_locale();
        $this->_init();
    }

    /**
     * 
     */
    public function _init() {
        $this->remote_plugins();
    }

    /**
     * 
     */
    public function remote_plugins() {
        $remote = wp_remote_post(
                $this->url, array('decompress' => false)
        );
        if ( is_wp_error( $remote ) ) {
            $error_message = $remote->get_error_message();
            echo "Something went wrong: $error_message";
        } else {
            //echo 'Response:<pre>';
          //  print_r( $remote );
            $this->status = true;
            $this->remote = $remote;
            $this->get_remote_body();
           // echo '</pre>';
        }
        
    }

    /**
     * 
     */
    public function get_remote_body() {
        $code = $this->remote['response']['code'];
        $message = $this->remote['response']['message'];

        if (200 === $code && 'OK' === $message):
            $this->body_string_to_array();
        else:
            $this->get_remote_alternative();
        endif;
    }

    /**
     * 
     */
    public function body_string_to_array() {

        $aPlugins = explode("\n", $this->remote['body']);

        $i = 1;
        foreach ($aPlugins as $plugin):
            preg_match_all('#( \[(.*?)\])#', $plugin, $matches);


            $pluginId = str_replace($matches[0][0], '', $plugin);

            $data = explode('*', $matches[2][0]);
            $file = ABSPATH . 'wp-content/plugins/' . $pluginId . '/' . $pluginId . '.php';
            $file_fallback = ABSPATH . 'wp-content/plugins/' . $pluginId . '/' . $this->get_file_fallback_name($pluginId) . '.php';

            if (file_exists($file)):
                if (is_plugin_active($pluginId . '/' . $pluginId . '.php')) :
                    $aPlugins['actif'][$i] = [$pluginId, $data];
                else:
                    $aPlugins['noActif'][$i] = [$pluginId, $data];
                endif;
            elseif(file_exists($file_fallback)):
                if (is_plugin_active($pluginId . '/' . $this->get_file_fallback_name($pluginId) . '.php')) :
                    $aPlugins['actif'][$i] = [$pluginId, $data];
                else:
                    $aPlugins['noActif'][$i] = [$pluginId, $data];
                endif;
            else:
                
                $aPlugins['noInstall'][$i] = [$pluginId, $data];
            endif;
            $i++;
        endforeach;

        $this->plugins =  $aPlugins;
    }

    public function get_file_fallback_name($pluginId){

        $pluginId = str_replace('-', '_', $pluginId);

        return $pluginId;
    }
    /**
     * 
     */
    public function get_remote_alternative() {
        // @todo_slWsu : ajouter une alternative...
    }

}
