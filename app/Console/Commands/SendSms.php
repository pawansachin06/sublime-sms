<?php

namespace App\Console\Commands;

use App\Models\ContactGroup;
use App\Models\SmsJob;
use App\Services\SMSApi;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSms extends Command
{
    protected $smsApi;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Scheduled SMS';

    /**
     * Execute the console command.
     */
    public function handle(SMSApi $smsApi)
    {
        try {
            $job = SmsJob::where('status', 'PENDING')->first();
            if (!empty($job)) {
                Log::info('JOB ' . $job->id . ' ' . $job->name);

                $numbers = $job->numbers;
                $list_uids = $job->list_uids;
                if (!empty($numbers)) {
                    foreach ($numbers as $number) {
                        // send sms to number without changing placeholders
                    }
                }

                if (!empty($list_uids)) {
                    foreach ($list_uids as $list_id) {
                        $list = ContactGroup::where('uid', $list_id)->first(['id', 'uid', 'name']);
                        if (!empty($list)) {
                            $this->info(json_encode($list));
                            $contacts = $list->contacts();
                            $this->info(json_encode($contacts));
                        }
                    }
                } else {
                    Log::info('JOB No Groups Found ' . $job->id . ' ' . $job->name);
                }
            }

            // $this->info(json_encode($job));
            // $balance = $smsApi->get_balance();
            // Log::info('BALANCE', $balance);
        } catch (Exception $e) {
            Log::info('ERROR ' . $e->getMessage());
        }
    }
}
