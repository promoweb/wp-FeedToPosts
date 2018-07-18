<?php

class FeedToPosts_Deserializer
{

    /**
     * @param  string $optionKey
     * @return string
     */
    public function getValue($optionKey)
    {
        return get_option($optionKey, '');
    }
}
