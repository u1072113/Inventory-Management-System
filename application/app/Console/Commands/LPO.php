<?php

namespace App\Console\Commands;

use Auth;
use Inventory\Repository\PurchaseOrder\PurchaseOrderInterface;
use Inventory\Repository\User\UserInterface;
use Illuminate\Console\Command;
use Session;

class LPO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lpo:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrderInterface $purchase, UserInterface $user)
    {
        parent::__construct();
        $this->purchase = $purchase;
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->user->getUsersForLpoGenerate();
        foreach ($users as $user) {
            Session::flush();
            Auth::logout();
            Auth::loginUsingId($user->id);
            $purchases = $this->purchase->lpoReport();
            foreach ($purchases as $purchase) {
                $this->purchase->generatePdf($purchase->id);
            }
        }

    }
}
