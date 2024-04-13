<?php
// The code to create settings page goes here
// This includes add_menu_page, register_setting, add_settings_section, etc.

// Always check for defined ABSPATH to prevent direct access
defined('ABSPATH') or die('Unauthorized access is not allowed.');

function myplugin_add_admin_menu()
{
    add_menu_page(
        'Mode Paywall Settings', // Page title
        'Mode Paywall', // Menu title
        'manage_options', // Capability
        'mode-paywall', // Menu slug
        'myplugin_settings_page', // Function to display the settings page
        'dashicons-lock' // Icon URL
    );
}
function myplugin_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Mode Paywall Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('myplugin_settings');
            $options = get_option('myplugin_contract_address'); ?>
            <p>add contract address</p>
        <input type='text' size="50" name='myplugin_contract_address' value='<?php echo $options; ?>'>
        <?php
            submit_button();?>
        </form>
    </div>
    <?php
}
function myplugin_settings_init() {
    register_setting('myplugin_settings', 'myplugin_contract_address', 'sanitize_text_field');

    add_settings_section(
        'myplugin_settings_section', // ID used to identify this section and with which to register options
        __('Ethereum Contract Settings', 'text-domain'), // Title to be displayed on the administration page
        'myplugin_settings_section_callback', // Callback used to render the description of the section
        'myplugin_settings' // Page on which to add this section of options
    );

    add_settings_field(
        'myplugin_contract_address', // ID used to identify the field throughout the theme
        __('Contract Address', 'text-domain'), // The label to the left of the option interface element
        'myplugin_contract_address_render', // The name of the function responsible for rendering the option interface
        'myplugin_settings', // The page on which this option will be displayed
        'myplugin_settings_section', // The name of the section to which this field belongs
        array( // The array of arguments to pass to the callback. In this case, the description.
            'description' => __('Enter the Ethereum contract address here.', 'text-domain'),
        )
    );
}
function myplugin_settings_section_callback()
{
    echo 'Enter your Ethereum contract address.';
}

function myplugin_contract_address_render()
{
    $options = get_option('myplugin_contract_address'); ?>
        <input type='text' name='myplugin_contract_address' value='<?php echo $options; ?>'>
        <?php
}

add_action('admin_menu', 'myplugin_add_admin_menu');
add_action('admin_init', 'myplugin_settings_init');