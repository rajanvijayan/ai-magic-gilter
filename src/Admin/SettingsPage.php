<?php
namespace AIMagicFilter\Admin;

class SettingsPage {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_settings_page() {
        add_options_page(
            'AI Magic Filter Settings',
            'AI Magic Filter',
            'manage_options',
            'ai-comment-moderator',
            [ $this, 'create_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting( 'ai_magic_filter_group', 'enabled_post_types' );
        register_setting( 'ai_magic_filter_group', 'api_key' );
    }

    public function create_settings_page() {
        ?>
        <div class="wrap">
            <h1>AI Magic Filter Settings</h1>
    
            <!-- Tabs for Basic Settings and API Settings -->
            <h2 class="nav-tab-wrapper">
                <a href="#basic-settings" class="nav-tab nav-tab-active">Basic Settings</a>
                <a href="#api-settings" class="nav-tab">API Settings</a>
            </h2>
    
            <form method="post" action="options.php">
                <?php settings_fields( 'ai_magic_filter_group' ); ?>
                <?php do_settings_sections( 'ai_magic_filter_group' ); ?>
    
                <!-- Basic Settings Tab -->
                <div id="basic-settings" class="settings-section" style="display:block;">
                    <h2>Basic Settings</h2>
                    <p>Select the post types where AI Magic Filter will be enabled.</p>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Enable for Post Types</th>
                            <td>
                                <?php
                                $post_types = get_post_types( ['public' => true], 'objects' );
                                $enabled_post_types = get_option( 'enabled_post_types', [] );
    
                                foreach ( $post_types as $post_type ) {
                                    $checked = in_array( $post_type->name, $enabled_post_types ) ? 'checked' : '';
                                    echo '<label>';
                                    echo '<input type="checkbox" name="enabled_post_types[]" value="' . esc_attr( $post_type->name ) . '" ' . $checked . '>';
                                    echo ' ' . esc_html( $post_type->label );
                                    echo '</label><br>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
    
                <!-- API Settings Tab -->
                <div id="api-settings" class="settings-section" style="display:none;">
                    <h2>API Settings</h2>
                    <p class="description">Enter your API key. Ensure it's correct to avoid errors in moderation.</p>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">API Key</th>
                            <td>
                                <input type="password" name="api_key" class="regular-text" value="<?php echo esc_attr( get_option( 'api_key' ) ); ?>" />
                                <p class="description">Enter your API Secret Key here. <a href="https://aistudio.google.com/app/apikey" target="_blank">Get your API key here</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
    
                <?php submit_button(); ?>
            </form>
        </div>
    
        <script>
            // JavaScript to handle the tab navigation
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
    
                    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
                    tab.classList.add('nav-tab-active');
    
                    document.querySelectorAll('.settings-section').forEach(section => section.style.display = 'none');
                    document.querySelector(tab.getAttribute('href')).style.display = 'block';
                });
            });
        </script>
        <?php
    }
}