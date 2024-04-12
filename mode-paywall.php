<?php
/**
 * Plugin Name: Mode Paywall
 * Description: A simple Content paywall that hides content until a transaction from Mode network is confirmed.
 * Version: 1.0
 * Author: 0xAirdropfarmer
 */

function myplugin_register_shortcodes()
{
    add_shortcode('mode_paywall', 'myplugin_mode_paywall_shortcode');
}
add_action('init', 'myplugin_register_shortcodes');

function myplugin_mode_paywall_shortcode($atts, $content = null)
{
    // Shortcode attributes can be used to customize the paywall, e.g., contract address
    $atts = shortcode_atts(
        [
            'contract_address' => '0xEa9dD14e06E8b0FA8D6C0DC23821df290c8DF85d', // Default contract address
            // ... other defaults can be set here
        ],
        $atts
    );

    // Check if the user has already unlocked the content
    $has_access = false; // You'll need to implement this check based on your application's logic

    if ($has_access) {
        // If the user has access, just display the content
        return do_shortcode($content);
    } else {
        // If the user doesn't have access, show a button with centered styling
        ob_start();
        // Start output buffering
        ?>
        <style>
            #unlockContentButtonWrapper {
                display: flex;
                justify-content: center; /* Center button horizontally */
                margin-top: 20px; /* Add some space on the top */
            }
            #unlockContentButton {
                padding: 10px 20px;
                background-color: #3498db; /* Example button color */
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            #unlockContentButton:hover {
                background-color: #2980b9; /* Darker shade on hover */
            }
            #hiddenContent {
                display: none;
            }
        </style>
        <div id="unlockContentButtonWrapper">
            <button id="unlockContentButton"  >Unlock with Mode Network</button>
        </div>
        <div id="hiddenContent">
            <?php echo do_shortcode($content); ?>
        </div>
        
        <?php return ob_get_clean(); // Return the buffered content
    }
}

function mode_interaction()
{
    // First, enqueue web3.js from a CDN or your own hosted version
    wp_enqueue_script(
        'web3',
        'https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js',
        [],
        null,
        true
    );

    // Then, enqueue your custom script and make it dependent on web3.js
    wp_enqueue_script(
        'mode_interaction-js',
        plugin_dir_url(__FILE__) . 'js/mode_interaction.js',
        ['web3'],
        '1.0',
        true
    );

    // Localize the script with data needed by the JS
    wp_localize_script('mode_interaction-js', 'myplugin_params', [
        'contractAddress' => '0xEa9dD14e06E8b0FA8D6C0DC23821df290c8DF85d', // The smart contract address goes here
        // ... other data ...
    ]);
}
add_action('wp_enqueue_scripts', 'mode_interaction');
