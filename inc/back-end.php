<?php

add_action( 'admin_init', 'fpicp_style_admin_init' );
add_action( 'admin_menu', 'fpicp_add_menu_page' );
add_filter( 'plugin_action_links_chatbot-inteliwise/inteliwise-chat.php', 'fpicp_settings_page_url' );

function fpicp_style_admin_init() {
    wp_enqueue_style( 'fpicpPluginStyle', plugins_url( 'style.css', __DIR__ ) );
}

function fpicp_settings_page_url( $links ) {

    $url = esc_url( add_query_arg(
        'page',
        'inteliwise-chat-settings',
        get_admin_url() . 'admin.php'
    ) );

    $settings_link = '<a href="' . $url . '">' . __( 'Settings' ) . '</a>';
    $links = array_merge([$settings_link], $links);
    return $links;
}

function fpicp_add_menu_page() {

    $iconUrl = 'inteliwise_icon.png';
    if (!empty($_GET['page']) && $_GET['page'] == 'inteliwise-chat-settings') {
        $iconUrl = 'inteliwise_icon_2.png';
    }

    add_menu_page(
        __( 'Chatbot InteliWISE', 'chatbot-inteliwise' ),
        __( 'Chatbot InteliWISE', 'chatbot-inteliwise' ),
        'manage_options',
        'inteliwise-chat-settings',
        'fpicp_settings_page_init',
        plugin_dir_url( __DIR__ ) . '/img/' . $iconUrl,
        999
    );
}

function fpicp_settings_page_init() {
    $lang = strtolower(substr(get_bloginfo('language'),0,2));
    if (!file_exists(plugin_dir_url( __DIR__ ).'img/'.$lang)) $lang = 'en';

    $code = get_option('inteliwise_code');
    $result = '';
    if (!empty($_POST)) {
        $resultClass = 'notice-error';
        $result = __('The changes were not saved. Please validate the code.', 'chatbot-inteliwise');
        if (!empty($_POST['inteliwise_code'])) {
            $codeInteliwise = wp_kses($_POST['inteliwise_code'], [
                'script' => [
                    'type' => [
                        'text/javascript'
                    ],
                    'src' => [],
                    'async' => []
                ]
            ]);


            preg_match('/src="([^"]*)"/', $codeInteliwise, $matches);
            if (!empty($matches[1]) && substr($matches[1], 0, 8) == 'https://' && substr_count($codeInteliwise, 'static.inteliwise.com')) {
                update_option('inteliwise_code', $codeInteliwise);
                $resultClass = 'notice-success';
                $result = __('Settings saved.', 'chatbot-inteliwise');
            }
        }
        $code = get_option('inteliwise_code');
    }

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e( 'Chatbot InteliWISE', 'chatbot-inteliwise' ); ?></h1>
        <?php if (!empty($result)) { ?>
            <div id="setting-error-settings_updated" class="notice <?php echo esc_html($resultClass); ?> settings-error is-dismissible"> <p><strong><?php echo esc_html($result); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'chatbot-inteliwise'); ?></span></button></div>
        <?php } ?>
        <div>
            <img src="<?php echo plugin_dir_url( __DIR__ ); ?>img/inteliwise_logo.png" alt="" style="max-width:280px">
        </div>
        <div class="inteliwise-wrap">
            <p><?php _e('This next-generation Artificial Intelligence (AI) software tool for your online shop or website will help you convert website traffic into revenue.', 'chatbot-inteliwise'); ?></p>
            <p><a href="<?php _e('https://inteliwise.com/leadomat/', 'chatbot-inteliwise'); ?>" target="_blank" class="button button-primary"><?php _e('Find out more', 'chatbot-inteliwise'); ?></a></p>
            <h2><?php _e('How to add Chatbot InteliWISE to your WordPress site?', 'chatbot-inteliwise'); ?></h2>

            <form method="post" action="">
                <p>
                    <ol>
                        <li><?php _e('Log in to our platform (if you do not have an account, fill in the form and activate your new account following the instructions):', 'chatbot-inteliwise'); ?><br><a href="<?php _e('https://my.inteliwise.com/auth/register?lang=EN&products=leadomat', 'chatbot-inteliwise'); ?>" target="_blank" class="button button-primary iw-btn-login"><?php _e('Log in', 'chatbot-inteliwise'); ?></a></li>
                        <li><?php _e('Go to the "Integrations" tab. Select "WordPress" and copy the embed code to the clipboard (you can do it classically CTR+C or use the "Copy to clipboard" button).', 'chatbot-inteliwise'); ?></li>
                        <li><?php _e('Return to the WordPress dashboard and paste the embed code to the indicated place.', 'chatbot-inteliwise'); ?><br><textarea name="inteliwise_code" id="inteliwise_code" class="iw-input-code large-text" rows="10" placeholder="<?php _e( 'Paste the embed code here', 'chatbot-inteliwise' ); ?>"><?php echo esc_html($code); ?></textarea>
                        </li>
                        <li><?php _e('Click "Save" then "Publish" your changes.', 'chatbot-inteliwise'); ?></li>
                        <li><?php _e('Done!', 'chatbot-inteliwise'); ?></li>
                    </ol>
                </p>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save' ); ?>"></p>
            </form>
        </div>
    </div>
    <?php
}
