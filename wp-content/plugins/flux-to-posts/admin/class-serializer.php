<?php
/**
 * Performs all sanitization functions required to save the option values to
 * the database.
 *
 * @package Custom_Admin_Settings
 */

/**
 * Performs all sanitization functions required to save the option values to
 * the database.
 *
 * This will also check the specified nonce and verify that the current user has
 * permission to save the data.
 *
 * @package Custom_Admin_Settings
 */
class Serializer
{

    /**
     * Initializes the function by registering the save function with the
     * admin_post hook so that we can save our options to the database.
     */
    public function init()
    {
        add_action('admin_post', array( $this, 'save' ));
    }

    /**
     * Validates the incoming nonce value, verifies the current user has
     * permission to save the value from the options page and saves the
     * option to the database.
     */
    public function save()
    {

        // First, validate the nonce and verify the user as permission to save.
        if (! ($this->has_valid_nonce() && current_user_can('manage_options'))) {
            echo 'You can\'t access this page';
        }

        // If the above are valid, sanitize and save the option.
        if (null !== wp_unslash($_POST['flux']) && null !== wp_unslash($_POST['user']) && null !== wp_unslash($_POST['status']) && null !== wp_unslash($_POST['category'])) {
            $flux = sanitize_text_field($_POST['flux']);
            $user = sanitize_text_field($_POST['user']);
            $status = sanitize_text_field($_POST['status']);
            $category = sanitize_text_field($_POST['category']);

            update_option('flux', $flux);
            update_option('user', $user);
            update_option('status', $status);
            update_option('category', $category);
        }

        $this->redirect();
    }

    /**
     * Determines if the nonce variable associated with the options page is set
     * and is valid.
     *
     * @access private
     *
     * @return boolean False if the field isn't set or the nonce value is invalid;
     *                 otherwise, true.
     */
    public function has_valid_nonce()
    {

        // If the field isn't even in the $_POST, then it's invalid.
        if (! isset($_POST['custom-flux']) && ! isset($_POST['user']) && ! isset($_POST['status']) && ! isset($_POST['category'])) { // Input var okay.
            return false;
        }

        $fields  = [
            wp_unslash($_POST['custom-flux']),
            wp_unslash($_POST['user']),
            wp_unslash($_POST['status']),
            wp_unslash($_POST['category']),
        ];
        $action = 'save-flux';
        foreach ($fields as $field) {
            return wp_verify_nonce($field, $action);
        }
        return $this;
    }

    /**
     * Redirect to the page from which we came (which should always be the
     * admin page. If the referred isn't set, then we redirect the user to
     * the login page.
     *
     * @access private
     */
    public function redirect()
    {

        // To make the Coding Standards happy, we have to initialize this.
        if (! isset($_POST['_wp_http_referer'])) { // Input var okay.
            $_POST['_wp_http_referer'] = wp_login_url();
        }

        // Sanitize the value of the $_POST collection for the Coding Standards.
        $url = sanitize_text_field(
            wp_unslash($_POST['_wp_http_referer']) // Input var okay.
        );

        // Finally, redirect back to the admin page.
        wp_safe_redirect(urldecode($url));
        exit;
    }
}
