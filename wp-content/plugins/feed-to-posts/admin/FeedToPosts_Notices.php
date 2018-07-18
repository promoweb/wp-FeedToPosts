<?php

class FeedToPosts_Notices
{
    const NOTICES_OPTION_KEY = 'FeedToPosts_notices';

    public static function init()
    {
        add_action('admin_notices', [__CLASS__, 'displayNotices']);
    }
    /**
    * Checks for any stored notices and outputs them.
    */
    public static function displayNotices()
    {
        $notices = self::getNotices();
        if (empty($notices)) {
            return;
        }
        foreach ($notices as $type => $messages) {
            foreach ($messages as $message) {
                printf(
                    '<div class="notice notice-%1$s is-dismissible">
    <p>%2$s</p>
</div>',
                    $type,
                    $message
                );
            }
        }
        self::updateNotices([]);
    }
    /**
    * Retrieves any stored notices.
    *
    * @return array|void
    */
    private static function getNotices()
    {
        $notices = get_option(self::NOTICES_OPTION_KEY, []);
        return $notices;
    }
    /**
    * Update the stored notices in the options table with a new array.
    *
    * @param array $notices
    */
    private static function updateNotices(array $notices)
    {
        update_option(self::NOTICES_OPTION_KEY, $notices);
    }

    /**
     * Add notice
     *
     * @param $message
     * @param string $type
     */
    private static function addNotice($message, $type = 'success')
    {
        $notices = self::getNotices();
        $notices[$type][] = $message;
        self::updateNotices($notices);
    }
    /**
    * Success notice
    *
    * @param $message
    */
    public static function addSuccess($message)
    {
        self::addNotice($message, 'success');
    }

    /**
     * Error notice
     *
     * @param $message
     */
    public static function addError($message)
    {
        self::addNotice($message, 'error');
    }
}
FeedToPosts_Notices::init();
function FeedToPosts_notices_addSuccess($message)
{
    FeedToPosts_Notices::addSuccess($message);
}
function FeedToPosts_notices_addError($message)
{
    FeedToPosts_Notices::addError($message);
}
