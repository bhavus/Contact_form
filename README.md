# Contact_form
<?php
/**
 * Plugin Name: Contact Form 7 DB Addon
 * Description: Stores Contact Form 7 submissions in a custom database table and displays them in the admin area.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: contact-db-addon
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class ContactDBAddon {
    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'create_db_table']);
        add_action('wpcf7_mail_sent', [$this, 'store_submission']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function create_db_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_db';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name tinytext NOT NULL,
            email varchar(100) NOT NULL,
            time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function store_submission($contact_form) {
        $submission = WPCF7_Submission::get_instance();

        if ($submission) {
            $data = $submission->get_posted_data();
            $name = isset($data['your-name']) ? sanitize_text_field($data['your-name']) : '';
            $email = isset($data['your-email']) ? sanitize_email($data['your-email']) : '';

            if (!empty($name) && !empty($email)) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'contact_db';

                $wpdb->insert(
                    $table_name,
                    [
                        'name' => $name,
                        'email' => $email
                    ]
                );
            }
        }
    }

    public function add_admin_menu() {
        add_menu_page(
            'Contact Form Submissions',
            'Contact Submissions',
            'manage_options',
            'contact-submissions',
            [$this, 'admin_page'],
            'dashicons-email-alt',
            26
        );
    }

    public function admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_db';
        $results = $wpdb->get_results("SELECT * FROM $table_name");

        echo '<div class="wrap">';
        echo '<h1>Contact Form Submissions</h1>';
        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<thead><tr><th>Name</th><th>Email</th><th>Submitted On</th></tr></thead>';
        echo '<tbody>';

        if ($results) {
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . esc_html($row->name) . '</td>';
                echo '<td>' . esc_html($row->email) . '</td>';
                echo '<td>' . esc_html($row->time) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">No submissions found.</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}

new ContactDBAddon();
