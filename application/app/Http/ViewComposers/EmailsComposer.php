<?php namespace App\Http\ViewComposers;


use Inventory\Repository\Message\MessageInterface;
use Inventory\Repository\Setting\SettingsInterface;
use Illuminate\Contracts\View\View;
use Input;

class EmailsComposer
{

    /**
     * @var SettingsInterface
     */
    private $setting;
    /**
     * @var MessageInterface
     */
    private $message;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    public function compose(View $view)
    {
        $emails = $this->message->getSentEmails();
        $view->with('emails', $emails);
        $view->with('emailsCount', $emails->count());

    }


}