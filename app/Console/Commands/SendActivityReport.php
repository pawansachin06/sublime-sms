<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Models\Sms;
use App\Models\Setting;
use App\Exports\SmsExport;
use Illuminate\Console\Command;
use App\Mail\SmsActivityReport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendActivityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-activity-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send activity report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $settings = Setting::where('key', 'activity-report-settings')->first();
        $settings = !empty($settings['value']) ? @json_decode($settings['value'], true) : [];

        $emails = (!empty($settings['emails']) && is_array($settings['emails'])) ? $settings['emails'] : [];
        $times = (!empty($settings['times']) && is_array($settings['times'])) ? $settings['times'] : [];

        if (in_array('daily', $times)) {
            // Send the CSV file daily
            $this->sendCsv('daily', $emails);
        }

        if (in_array('weekly', $times)) {
            // Send the CSV file weekly (e.g., every Monday, or Friday)
            if ($now->isMonday()) {
                $this->sendCsv('weekly', $emails);
            }
        }

        if (in_array('monthly', $times)) {
            // Send the CSV file monthly (e.g., on the 1st of every month)
            if ($now->day == 1) {
                $this->sendCsv('monthly', $emails);
            }
        }
    }

    public function sendCsv($frequency = 'daily', $emails = [])
    {
        try {
            $csvFilePath = $this->generateCsv($frequency);
            Mail::to($emails)->send(new SmsActivityReport($csvFilePath, $frequency));
            Log::info('SMS CSV ' . $frequency . ' mail sent');
        } catch (Exception $e) {
            Log::info('SMS CSV ' . $frequency . ': ' . $e->getMessage());
        }
    }

    public function generateCsv($frequency = 'daily')
    {
        $this->info($frequency);

        $query = Sms::query()->select([
            'id',
            'message',
            'to',
            'countrycode',
            'from',
            'from_name',
            'name',
            'user_id',
            'recipient',
            'send_at',
            'folder',
            'cost',
            'delivered_at',
            'status'
        ])->with('sender:id,email,name');

        if ($frequency == 'monthly') {
            // Get the start of the last month
            $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
            // Get the end of the last month
            $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
            // take between the dates of month
            $query = $query->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth]);
        } elseif ($frequency == 'weekly') {
            // Get the start of the current week (Monday)
            $startOfWeek = Carbon::now()->startOfWeek()->subWeek();
            // Get the end of the last week (Sunday)
            $endOfWeek = Carbon::now()->startOfWeek()->subDay();
            // take between the dates of week
            $query = $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        } else {
            $yesterday = Carbon::yesterday();
            $query = $query->whereDate('created_at', $yesterday);
        }
        $query = $query->orderBy('id', 'DESC');

        $filename = 'activity-' . $frequency . '-' . date('Y-m-d-H-i-s') . '.xlsx';
        $filePath = 'cache/' . $filename;
        // return (new SmsExport($query))->download($filename);
        Excel::store(new SmsExport($query), $filePath, 'local');
        return $filePath;
    }
}
