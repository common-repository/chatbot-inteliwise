<?php

function fpicp_in_wp_footer() {

    $codeInteliwise = get_option('inteliwise_code');

    if (!empty($codeInteliwise)) {
        $codeInteliwise = wp_kses($codeInteliwise, [
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
            echo wp_get_script_tag( [
                'type' => 'text/javascript',
                'src' => esc_url($matches[1]),
                'async' => true,
            ] );
        }
    }
}
add_action( 'wp_footer', 'fpicp_in_wp_footer' );