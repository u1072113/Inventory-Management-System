<?php namespace App\Http\ViewComposers;


use Inventory\Repository\Message\MessageInterface;
use Inventory\Repository\Setting\SettingsInterface;
use Illuminate\Contracts\View\View;
use Input;

class SettingsComposer
{

    /**
     * @var SettingsInterface
     */
    private $setting;


    public function __construct( SettingsInterface $setting)
    {
        $this->setting = $setting;
    }

    public function compose(View $view)
    {
        $settings = $this->setting->getSettings();
        $colors = json_encode(array_flatten($this->setting->getLineColors()));
        $view->with('lineColors', $colors);
        $view->with('theme', $settings->appTheme);
        $view->with('dailyMaximum', $settings->dailyMaximum);
        $view->with('dailyMinimum', $settings->dailyMinimum);
        $view->with('paperReam', $settings->paperReam);
        $view->with('barColor', json_encode($settings->barGraphdefaultColor));
        $view->with('settingid', $settings->id);
        $view->with('paginationSize', $settings->paginationDefault);
        $view->with('setting', $settings);
    }


}