<?php
/*
Plugin Name: Custom Contact Form Widget
Description: A custom widget plugin with a contact form for WordPress.
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Custom Contact Form Widget Class
class Custom_Contact_Form_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'custom_contact_form_widget',
            'Custom Contact Form Widget',
            array('description' => 'A custom widget with a contact form.')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];

        // Display the contact form
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="custom_contact_form">';
        echo '<input type="text" name="name" placeholder="Your Name" required><br>';
        echo '<input type="email" name="email" placeholder="Your Email" required><br>';
        echo '<textarea name="message" placeholder="Your Message" required></textarea><br>';
        echo '<input type="submit" value="Submit">';
        echo '</form>';

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
}

// Register the Custom Contact Form Widget
function register_custom_contact_form_widget() {
    register_widget('Custom_Contact_Form_Widget');
}
add_action('widgets_init', 'register_custom_contact_form_widget');

// Custom Contact Form Submission
function custom_contact_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'custom_contact_form') {
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

        // Perform additional actions with the submitted form data
        // For example, you can send an email or save the form data to a database

        // Redirect back to the page
        wp_redirect(esc_url(home_url()));
        exit;
    }
}
add_action('admin_post_nopriv_custom_contact_form', 'custom_contact_form_submission');
add_action('admin_post_custom_contact_form', 'custom_contact_form_submission');
