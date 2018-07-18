<?php

/**
 * Class FeedToPosts_Serializer
 */
class FeedToPosts_Serializer
{
    public function FeedToPosts_init()
    {
        add_action('admin_post', array( $this, 'FeedToPosts_save' ));
    }

    /**
     * Validates the incoming nonce value, verifies the current user has
     * permission to save the value from the options page and saves the
     * option to the database.
     */
    public function FeedToPosts_save()
    {

        // First, validate the nonce and verify the user as permission to save.
        if (! ($this->FeedToPosts_hasValidNonce() && current_user_can('manage_options'))) {
            echo 'You can\'t access this page';
        }

        // If the above are valid, sanitize and save the option.
        if (null !== wp_unslash($_POST['Feed']) && null !== wp_unslash($_POST['user']) && null !== wp_unslash($_POST['status']) && null !== wp_unslash($_POST['category'])) {
            $Feed = sanitize_text_field($_POST['Feed']);
            $user = sanitize_text_field($_POST['user']);
            $status = sanitize_text_field($_POST['status']);
            $category = sanitize_text_field($_POST['category']);

            update_option('Feed', $Feed);
            update_option('user', $user);
            update_option('status', $status);
            update_option('category', $category);
        }
        $this->FeedToPosts_redirect();
    }

    /**
     * Determines if the nonce variable associated with the options page is set
     * and is valid.
     *
     * @access private
     *
     * @return boolean False if the field isn't set or the nonce value is invalid otherwise, true.
     */
    public function FeedToPosts_hasValidNonce()
    {

        // If the field isn't even in the $_POST, then it's invalid.
        if (! isset($_POST['FeedToPosts_nonce']) && ! isset($_POST['user']) && ! isset($_POST['status']) && ! isset($_POST['category'])) { // Input var okay.
            return false;
        }

        $fields  = [
            wp_unslash($_POST['FeedToPosts_nonce']),
            wp_unslash($_POST['user']),
            wp_unslash($_POST['status']),
            wp_unslash($_POST['category']),
        ];
        $action = 'FeedToPosts_saveFeed';
        foreach ($fields as $field) {
            return wp_verify_nonce($field, $action);
        }
    }

    /**
     * Redirect to the page from which we came
     * @access private
     */
    public function FeedToPosts_redirect()
    {
        // Input var okay.
        if (! isset($_POST['_wp_http_referer'])) {
            $_POST['_wp_http_referer'] = wp_login_url();
        }

        $url = sanitize_text_field(
            wp_unslash($_POST['_wp_http_referer'])
        );
        // Finally, redirect back to the admin page.
        wp_safe_redirect(urldecode($url));
        exit;
    }
}
