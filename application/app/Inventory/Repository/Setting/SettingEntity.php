<?php namespace Inventory\Repository\Setting;

use App\Setting;
use Auth;

class SettingEntity implements SettingsInterface
{
    /**
     * Gets black,cyan,magenta,yellow color
     * @return mixed
     */
    public function getLineColors()
    {
        return Setting::select('blackColor', 'magentaColor', 'cyanColor', 'yellowColor')->where('userId', '=',
            Auth::user()->id)->get()->toArray();
    }

    /**
     * Get Settings for a user
     * @return mixed
     */
    public function getSettings()
    {
        return Setting::firstOrCreate(['userId' => Auth::user()->id]);
    }

    /**
     * Set settings for a user
     * @param $settings
     * @return mixed
     */
    public function setSettings($settings)
    {
        return Setting::where('userId', '=', Auth::user()->id)->update($settings);
    }
}