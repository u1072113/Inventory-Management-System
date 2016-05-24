<?php


namespace Inventory\Repository\Setting;


interface SettingsInterface
{
    /**
     * Gets black,cyan,magenta,yellow color
     * @return mixed
     */
    public function getLineColors();

    /**
     * Get Settings for a user
     * @return mixed
     */
    public function getSettings();

    /**
     * Set settings for a user
     * @param $settings
     * @return mixed
     */
    public function setSettings($settings);
}