<?php

namespace App\Console\Commands;

use App\Models\ContactGroup;
use App\Models\Sms;
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
    protected $signature = 'send-sms {--show}';

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
        $show_msg = $this->option('show');
        try {
            $dlr_callback = route('api.sms.callback.dlr');
            if($show_msg) {
                $this->info('DELIVERY CALLBACK: ' . $dlr_callback);
            }

            $job = SmsJob::where('status', 'PENDING')->first();
            if (!empty($job)) {
                $msg = 'SMS JOB: started ' . $job->id . ' ' . $job->name;
                if ($show_msg) {
                    $this->info($msg);
                } else {
                    Log::info($msg);
                }

                $message = $job->message;
                $is_scheduled = $job->scheduled;
                $send_at = $job->send_at;

                $numbers = $job->numbers;
                $list_uids = $job->list_uids;
                if (!empty($numbers)) {
                    foreach ($numbers as $number) {
                        // send sms to number without changing placeholders
                    }
                }

                if (!empty($list_uids)) {
                    foreach ($list_uids as $list_id) {
                        $list = ContactGroup::where('uid', $list_id)->with('contacts')->first(['id', 'uid', 'name']);
                        if (!empty($list)) {
                            $contacts = $list->contacts;
                            if (!empty($contacts)) {
                                foreach ($contacts as $contact) {
                                    $contact_firstname = $contact->name;
                                    $contact_lastname = $contact->lastname;
                                    $contact_phone = $contact->phone;
                                    $contact_company = $contact->company;
                                    $contact_country = $contact->country;
                                    $to = $contact_phone;

                                    $formatted_msg = $message;
                                    $formatted_msg = str_replace('[Firstname]', $contact_firstname, $formatted_msg);
                                    $formatted_msg = str_replace('[Lastname]', $contact_lastname, $formatted_msg);
                                    $formatted_msg = str_replace('[Mobile]', $contact_phone, $formatted_msg);
                                    $formatted_msg = str_replace('[Company]', $contact_company, $formatted_msg);

                                    if (!empty($formatted_msg)) {
                                        $this->info($formatted_msg);
                                        // send to api
                                        // $is_scheduled if false then keep send_at empty
                                        // $api_res = $smsApi->send_sms([
                                        //     'to' => $to,
                                        //     'message' => $formatted_msg,
                                        //     'send_at' => !empty($is_scheduled) ? $send_at : '',
                                        //     'dlr_callback'=> $dlr_callback,
                                        // ]);
                                        $api_res = '';

                                        Log::info(json_encode($api_res));

                                        $sms = Sms::create([
                                            'sms_job_id' => $job->id,
                                            'sms_id' => !empty($api_res['message_id']) ? $api_res['message_id'] : '',
                                            'message' => $formatted_msg,
                                            'to' => $to,
                                            'list_id' => '',
                                            'countrycode' => $contact_country,
                                            'from' => '',
                                            'send_at'=> $send_at,
                                            'dlr_callback'=> $dlr_callback,
                                            'cost'=> !empty($api_res['cost']) ? $api_res['cost'] : '',
                                            'status'=> 'SENT',
                                            'local_status'=> 'SENT',
                                        ]);
                                        if($show_msg){
                                            $this->info(json_encode($api_res));
                                        } else {
                                            Log::info($api_res);
                                        }
                                    }
                                }
                            } else {
                                $msg = 'SMS JOB: Group has no contacts ' . $list->uid . ' ' . $list->name;
                                if ($show_msg) {
                                    $this->error($msg);
                                } else {
                                    Log::error($msg);
                                }
                            }

                            // $this->info($message);
                            // $this->info($send_at);
                            // $this->info($is_scheduled);


                        } else {
                            $msg = 'SMS JOB: Group not found ' . $list_id;
                            if ($show_msg) {
                                $this->error($msg);
                            } else {
                                Log::error($msg);
                            }
                        }
                    }
                    $job->status = 'COMPLETE';
                    $job->save();
                } else {
                    $job->status = 'COMPLETE';
                    $job->save();
                    $msg = 'SMS JOB: Groups not added, Marked Complete ' . $job->id . ' ' . $job->name;
                    if ($show_msg) {
                        $this->error($msg);
                    } else {
                        Log::error($msg);
                    }
                }
            }

            // $this->info(json_encode($job));
            // $balance = $smsApi->get_balance();
            // Log::info('BALANCE', $balance);
        } catch (Exception $e) {
            $msg = 'ERROR ' . $e->getMessage();
            $this->error($msg);
            Log::error($msg);
        }
    }
}
