<?php

class Settings
{
    private $yoyow_plugin_options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'yoyow_plugin_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'yoyow_plugin_page_init' ) );
    }

    public function yoyow_plugin_add_plugin_page() {
        add_options_page(
            'YOYOW WP',
            'YOYOW WP', // menu_title
            'manage_options', // capability
            'yoyow-plugin', // menu_slug
            array( $this, 'yoyow_plugin_create_admin_page' ) // function
        );
    }

    public function yoyow_plugin_create_admin_page() {
        $this->yoyow_plugin_options = get_option( 'yoyow_plugin_config' ); ?>

        <div class="wrap">
            <script type="text/javascript">
                function yoyow_plugin_connection_check() {
                    var config_error = config_check();
                    if(config_error){
                        document.getElementById("yoyow_plugin_check_place").innerHTML = config_error;
                        document.getElementById("yoyow_plugin_check_place").style.color = 'red';
                        return;
                    }
                    var rpcUrl =  document.getElementById("rpc_url").value;
                    var chain_id =  document.getElementById("chain_id").value;
                    var httpRequest = new XMLHttpRequest();
                    httpRequest.open('POST', rpcUrl, true);
                    httpRequest.setRequestHeader("content-type","application/json");
                    var data = {id: 1, jsonrpc: "2.0", method: "get_chain_id", params: [], id:1};
                    httpRequest.send(JSON.stringify(data));
                    httpRequest.onreadystatechange = function () {
                        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                            var result = JSON.parse(httpRequest.responseText);
                            if (result.result == chain_id) {
                                document.getElementById("yoyow_plugin_check_place").innerHTML ="Connection successful!";
                                document.getElementById("yoyow_plugin_check_place").style.color = '#2271b1';
                            } else {
                                document.getElementById("yoyow_plugin_check_place").innerHTML ="Unmatched chain_id!";
                                document.getElementById("yoyow_plugin_check_place").style.color = 'red';
                            }
                            
                        } else {
                            document.getElementById("yoyow_plugin_check_place").innerHTML ="Connection failed!";
                            document.getElementById("yoyow_plugin_check_place").style.color = 'red';
                        }
                    };
                }

                function config_check(){
                    var error = null;
                    var rpc_url = document.getElementById("rpc_url").value;
                    var chain_id = document.getElementById("chain_id").value;
                    var platform_id = document.getElementById("platform_id").value;
                    var poster_id = document.getElementById("poster_id").value;
                    var platform_secondary_key = document.getElementById("platform_secondary_key").value;
                    var content_type = document.getElementById("content_type").value;
                    if (!platform_secondary_key || platform_secondary_key.length != 51) {
                        error = "Invalid Platform Secondary Key！";
                    }
                    var regPos = /^[1-9]+[0-9]*$/;
                    if (!platform_id  || !regPos.test(platform_id)) {
                        error = "Invalid platform id！";
                    }
                    if (!poster_id || !regPos.test(poster_id)) {
                        error = "Invalid poster id！";
                    }
                    if (!chain_id || chain_id.length != 64) {
                        error = "Invalid chain id！";
                    }
                    var reg = /^(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?/;
                    if (!rpc_url || !reg.test(rpc_url)) {
                        error = "Invalid rpc url！";
                    }
                    if (content_type < 0 || content_type > 3){
                        error = "Invalid content type";
                    }
                    return error;
                }

                window.onload = function() {
                    var submit = document.getElementById('submit');
                    if (submit != null && submit != 'undefined') {
                        var oldSubmit = submit.onclick;
                        submit.onclick = function(event) {
                            event.preventDefault();
                            var setting_error = document.getElementById('setting-error-settings_updated');
                            if (setting_error != null && setting_error != 'undefined') {
                                setting_error.remove();
                            }
                            var errorMessage = config_check();
                            if (!errorMessage) {
                                submit.onclick = oldSubmit;
                                submit.click(event);
                            } else {
                                var errorHtml = `<div id="setting-error-settings_updated" class="notice notice-error settings-error is-dismissible">
<p><strong>${errorMessage}</strong></p><button type="button" onclick="closeErrorNotice()" class="notice-dismiss"><span class="screen-reader-text">Ignore</span></button></div>`;
                                var errorContent = document.getElementById('error-content');
                                errorContent.innerHTML = errorHtml;
                            }
                        }
                    }
                }
                function closeErrorNotice() {
                    var errorContent = document.getElementById('error-content');
                    errorContent.innerHTML = "";
                }
            </script>
            <h2>YOYOW-WP Plugin</h2>
            <p id='error-content'></p>

            <form method="post" action="options.php">
                <?php
                settings_fields( 'yoyow_plugin_option_group' );
                do_settings_sections( 'yoyow-plugin-admin' );
                submit_button();
                ?>
            </form>
            <p>
                <button class="button button-primary" id="yoyow_plugin_check_link" onclick="yoyow_plugin_connection_check()">Test connection</button>
                <span id="yoyow_plugin_check_place" style="font-size: 14px;font-weight:400;color: #0c0c0c;display: inline-block;margin-left: 5px"></span>
            </p>
        </div>
    <?php }

    public function yoyow_plugin_page_init() {
        register_setting(
            'yoyow_plugin_option_group', // option_group
            'yoyow_plugin_config', // option_name
            array( $this, 'yoyow_plugin_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'yoyow_plugin_setting_section', // id
            '', // title
            array( $this, 'yoyow_plugin_section_info' ), // callback
            'yoyow-plugin-admin' // page
        );

        add_settings_field(
            'rpc_url', // id
            'RPC URL', // title
            array( $this, 'rpc_url_callback' ), // callback
            'yoyow-plugin-admin', // page
            'yoyow_plugin_setting_section' // section
        );

        add_settings_field(
            'chain_id', // id
            'CHAIN ID', // title
            array( $this, 'chain_id_callback' ), // callback
            'yoyow-plugin-admin', // page
            'yoyow_plugin_setting_section' // section
        );

        add_settings_field(
            'platform_id', // id
            'Platform ID', // title
            array( $this, 'platform_id_callback' ), // callback
            'yoyow-plugin-admin', // page
            'yoyow_plugin_setting_section' // section
        );

        add_settings_field(
            'poster_id', // id
            'Poster ID', // title
            array( $this, 'poster_id_callback' ), // callback
            'yoyow-plugin-admin', // page
            'yoyow_plugin_setting_section' // section
        );

        add_settings_field(
            'platform_secondary_key', // id
            'Platform Secondary Key', // title
            array( $this, 'platform_secondary_key_callback' ), // callback
            'yoyow-plugin-admin', // page
            'yoyow_plugin_setting_section' // section
        );

        add_settings_field(
            'content_type', // id
            'Onchain Content Type', // title
            array( $this, 'content_type_callback' ), // callback
            'yoyow-plugin-admin', // page
            'yoyow_plugin_setting_section' // section
        );
    }

    public function yoyow_plugin_sanitize($input) {
        $sanitary_values = array();
        if ( isset( $input['rpc_url'] ) ) {
            $sanitary_values['rpc_url'] = sanitize_text_field( $input['rpc_url'] );
        }

        if ( isset( $input['chain_id'] ) ) {
            $sanitary_values['chain_id'] = sanitize_text_field( $input['chain_id'] );
        }

        if ( isset( $input['platform_id'] ) ) {
            $sanitary_values['platform_id'] = sanitize_text_field( $input['platform_id'] );
        }

        if ( isset( $input['poster_id'] ) ) {
            $sanitary_values['poster_id'] = sanitize_text_field( $input['poster_id'] );
        }

        if ( isset( $input['platform_secondary_key'] ) ) {
            $sanitary_values['platform_secondary_key'] = sanitize_text_field( $input['platform_secondary_key'] );
        }

        if ( isset( $input['content_type'] ) ) {
            $sanitary_values['content_type'] = sanitize_text_field( $input['content_type'] );
        }

        return $sanitary_values;
    }

    public function yoyow_plugin_section_info() {

    }

    public function rpc_url_callback() {
        printf(
            '<input class="regular-text" type="text" name="yoyow_plugin_config[rpc_url]" id="rpc_url" value="%s">',
            isset( $this->yoyow_plugin_options['rpc_url'] ) ? esc_attr( $this->yoyow_plugin_options['rpc_url']) : ''
        );
    }

    public function chain_id_callback() {
        printf(
            '<input class="regular-text" type="text" name="yoyow_plugin_config[chain_id]" id="chain_id" value="%s">',
            isset( $this->yoyow_plugin_options['chain_id'] ) ? esc_attr( $this->yoyow_plugin_options['chain_id']) : ''
        );
    }

    public function platform_id_callback() {
        printf(
            '<input class="regular-text" type="text" name="yoyow_plugin_config[platform_id]" id="platform_id" value="%s">',
            isset( $this->yoyow_plugin_options['platform_id'] ) ? esc_attr( $this->yoyow_plugin_options['platform_id']) : ''
        );
    }

    public function poster_id_callback() {
        printf(
            '<input class="regular-text" type="text" name="yoyow_plugin_config[poster_id]" id="poster_id" value="%s">',
            isset( $this->yoyow_plugin_options['poster_id'] ) ? esc_attr( $this->yoyow_plugin_options['poster_id']) : ''
        );
    }

    public function platform_secondary_key_callback() {
        printf(
            '<input class="regular-text" type="text" name="yoyow_plugin_config[platform_secondary_key]" id="platform_secondary_key" value="%s">',
            isset( $this->yoyow_plugin_options['platform_secondary_key'] ) ? esc_attr( $this->yoyow_plugin_options['platform_secondary_key']) : ''
        );
    }

    public function content_type_callback() {
        $type_val = isset( $this->yoyow_plugin_options['content_type'] ) ? esc_attr( $this->yoyow_plugin_options['content_type']) : null;
        $types = ['URL', 'Full Post', 'HASH256', 'MD5'];
        $output = '<select class="widefat" name="yoyow_plugin_config[content_type]" id="content_type">';
        for($i=0; $i < sizeof($types); $i++){
            $output .= '<option value=' . $i . ($type_val == $i ? ' selected="selected" ' : '' ) . ' > ' . $types[$i] . '</option>';
        }
        $output .= '</select>';
        print($output);
    }

}