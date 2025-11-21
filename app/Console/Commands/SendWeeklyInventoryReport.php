<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendWeeklyInventoryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-weekly-inventory-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
{
    $alerts = app(InventoryAlertService::class)->getAlerts();

    $pdf = PDF::loadView('reports.weekly', compact('alerts'));
    Mail::to('admin@pharmacy.com')->send(new WeeklyReportEmail($pdf));
}

}
