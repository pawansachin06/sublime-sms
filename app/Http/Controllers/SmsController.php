<?php

namespace App\Http\Controllers;

use App\Exports\SmsExport;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Sms;
use App\Models\User;
use App\Models\SmsJob;
use App\Models\Template;
use Exception;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\SenderNumber;
use App\Imports\SmsImport;
use App\Services\Appy;


class SmsController extends Controller
{
    protected $appy;

    function __construct(Appy $appy)
    {
        $this->appy = $appy;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $current_user = $req->user();
        $profile_id = $current_user->getActiveProfile();
        $profile_ids = $current_user->allProfileIds();
        $is_export = $req->export;

        if ($req->ajax() || !empty($is_export)) {
            $keyword = $req->keyword;
            $keywordRecipient = $req->keywordRecipient;
            $filterStatus = $req->filterStatus;
            $filterFolder = $req->filterFolder;
            $filterStartDate = $req->filterStartDate;
            $filterEndDate = $req->filterEndDate;

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
            if (!$current_user->isSuperAdmin()) {
                $query = $query->whereIn('user_id', $profile_ids);
            }

            if (!empty($keyword)) {
                $query = $query->where('message', 'like', '%' . $keyword . '%');
            }
            if (!empty($filterStatus)) {
                $query = $query->where('status', $filterStatus);
            }
            if (!empty($filterFolder)) {
                $query = $query->where('folder', $filterFolder);
            }
            if (!empty($keywordRecipient)) {
                $query = $query->where(function ($q) use ($keywordRecipient) {
                    $q->where('name', 'like', '%' . $keywordRecipient . '%')
                        ->orWhere('recipient', 'like', '%' . $keywordRecipient . '%');
                });
            }
            if (!empty($filterStartDate)) {
                $query = $query->where('send_at', '>=', $filterStartDate);
            }
            if (!empty($filterEndDate)) {
                $query = $query->where('send_at', '<=', $filterEndDate);
            }
            $query = $query->orderBy('id', 'DESC');

            if ($is_export) {
                $query = $query->take(500);
                $filename = 'activity-' . date('Y-m-d-H-i-s') . '.xlsx';
                return (new SmsExport($query))->download($filename);
            }


            $data = $query->paginate(20);
            $items = [];
            $perPage = $data->perPage();
            $totalPages = $data->lastPage();
            $totalRows = $data->total();
            $page = $data->currentPage();
            if ($page > $totalPages) {
                $page = $totalPages;
            }
            if (!empty($data->items())) {
                foreach ($data->items() as $row) {
                    $from_name = $row->from_name;
                    if(trim($from_name) == '') {
                        $from_name = $row?->sender?->name;
                    }
                    $items[] = [
                        'id' => $row->id,
                        'message' => $row->message,
                        'to' => $row->to,
                        'from_number' => $row->from,
                        'from' => $row?->sender?->email,
                        'from_name' => $from_name,
                        'send_at' => $row?->send_at?->format('d/m/Y h:i A'),
                        'cost' => $row->cost,
                        'recipient' => !empty($row->recipient) ? $row->recipient : $row->name,
                        'status' => strtoupper($row->status),
                        'folder' => $row->folder,
                    ];
                }
            }
            return response()->json([
                'items' => $items,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages,
                'totalRows' => $totalRows,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $input = $req->validate([
            'profile_id' => ['required', 'integer'],
            'template_id' => ['nullable', 'integer', Rule::exists(Template::class, 'id')],
            'send_at' => ['nullable', 'string'],
            'contact_id' => ['nullable'],
            'contact_id.*' => ['nullable', Rule::exists(Contact::class, 'id')],
            'contact_group_uid' => ['nullable'],
            'contact_group_uid.*' => ['nullable', Rule::exists(ContactGroup::class, 'id')],
            'message' => ['required', 'string'],
            'from' => ['required', Rule::exists(SenderNumber::class, 'phone')],
            'isTesting' => ['nullable'],
        ], []);

        $input['title'] = 'No title';
        $isTesting = !empty($input['isTesting']) ? true : false;

        if (empty($input['contact_group_uid']) && empty($input['contact_id'])) {
            return response()->json(['message' => 'Please select recipient'], 422);
        }

        $send_at = date('Y-m-d H:i:s');
        $list_uids = [];
        $numbers = [];
        $scheduled = false;
        try {
            if (!empty($input['send_at'])) {
                $send_at_obj = new DateTime($input['send_at']);
                $send_at = $send_at_obj->format('Y-m-d H:i:s');
                $scheduled = true;
            }

            if (!empty($input['contact_group_uid'])) {
                $list_uids = $input['contact_group_uid'];
            }

            if (!empty($input['contact_id'])) {
                $numbers = $input['contact_id'];
            }


            $job = SmsJob::create([
                'name' => $input['title'],
                'user_id' => $input['profile_id'],
                'send_at' => $send_at,
                'template_id' => $input['template_id'],
                'list_uids' => $list_uids,
                'numbers' => $numbers,
                'from' => $input['from'],
                'message' => $input['message'],
                'scheduled' => $scheduled,
                'status' => $isTesting ? 'TESTING' : 'PENDING',
            ]);

            // generate all sms here
            $dlr_callback = route('api.sms.callback.dlr');
            $message = $job->message;
            $is_scheduled = $job->scheduled;
            $is_teting = ($job->status != 'PENDING') ? true : false;
            $send_at = $job->send_at;
            $user_id = $job->user_id;

            $numbers = $job->numbers;
            $list_uids = $job->list_uids;
            if (!empty($numbers)) {
                foreach ($numbers as $number) {
                    // send sms to number changing placeholders
                    $cnt = Contact::where('id', $number)->first();
                    if (!empty($cnt) && !empty($cnt->phone)) {
                        $contact_firstname = $cnt->name;
                        $contact_lastname = $cnt->lastname;
                        $contact_phone = $cnt->phone;
                        $contact_company = $cnt->company;
                        $contact_country = $cnt->country;
                        $to = $contact_phone;

                        $formatted_msg = $message;
                        $formatted_msg = str_replace('[Firstname]', $contact_firstname, $formatted_msg);
                        $formatted_msg = str_replace('[Lastname]', $contact_lastname, $formatted_msg);
                        $formatted_msg = str_replace('[Mobile]', $contact_phone, $formatted_msg);
                        $formatted_msg = str_replace('[Company]', $contact_company, $formatted_msg);

                        $recipient_name = $contact_firstname . ' ' . $contact_lastname;
                        $recipient_group_name = '';

                        if (!empty($formatted_msg)) {
                            // send to api
                            if ($is_teting) {
                                $api_res = [];
                            } else {
                            }

                            $sms = Sms::create([
                                'sms_job_id' => $job->id,
                                'sms_id' => !empty($api_res['message_id']) ? $api_res['message_id'] : '',
                                'message' => $formatted_msg,
                                'to' => $to,
                                'name' => $recipient_name,
                                'recipient' => $recipient_group_name,
                                'list_id' => 0,
                                'user_id' => $user_id,
                                'countrycode' => $contact_country,
                                'from' => $input['from'],
                                'send_at' => !empty($is_scheduled) ? $send_at : NULL,
                                'dlr_callback' => $dlr_callback,
                                'cost' => isset($api_res['cost']) ? $api_res['cost'] : '',
                                'folder' => 'outbox',
                                'status' => 'pending',
                                'local_status' => !empty($is_teting) ? 'SENT' : 'PENDING',
                            ]);
                        }
                    }
                }
            }

            if (!empty($list_uids)) {
                foreach ($list_uids as $list_id) {
                    $list = ContactGroup::where('id', $list_id)->with('contacts')->first(['id', 'name']);
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

                                $recipient_name = $contact_firstname . ' ' . $contact_lastname;
                                $recipient_group_name = $list->name;

                                if (!empty($formatted_msg)) {
                                    // send to api
                                    if ($is_teting) {
                                        $api_res = [];
                                    } else {
                                    }

                                    $sms = Sms::create([
                                        'sms_job_id' => $job->id,
                                        'sms_id' => !empty($api_res['message_id']) ? $api_res['message_id'] : '',
                                        'message' => $formatted_msg,
                                        'to' => $to,
                                        'name' => $recipient_name,
                                        'recipient' => $recipient_group_name,
                                        'list_id' => $list_id,
                                        'user_id' => $user_id,
                                        'countrycode' => $contact_country,
                                        'from' => $input['from'],
                                        'send_at' => !empty($is_scheduled) ? $send_at : NULL,
                                        'dlr_callback' => $dlr_callback,
                                        'cost' => isset($api_res['cost']) ? $api_res['cost'] : '',
                                        'folder' => 'outbox',
                                        'status' => 'pending',
                                        'local_status' => !empty($is_teting) ? 'SENT' : 'PENDING',
                                    ]);
                                }
                            }
                        } else {
                            $msg = 'SMS JOB: Group has no contacts ' . $list->id . ' ' . $list->name;
                        }
                    } else {
                        $msg = 'SMS JOB: Group not found ' . $list_id;
                    }
                }
                $job->status = 'COMPLETE';
                $job->save();
            } else {
                $job->status = 'COMPLETE';
                $job->save();
                $msg = 'SMS JOB: Groups not added, Marked Complete ' . $job->id . ' ' . $job->name;
            }


            if (!empty($send_at) && !empty($send_at_obj)) {
                return response()->json([
                    'scheduled' => true,
                    'message' => 'Horray! Your SMS has been scheduled for',
                    'message2' => $send_at_obj->format('D M j g:i A Y'),
                ]);
            } else {
                return response()->json([
                    'scheduled' => false,
                    'message' => 'Your Message has been Sent',
                    'message2' => date('D M j g:i A Y'),
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sms $sms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sms $sms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sms $sms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sms $sms)
    {
        //
    }

    public function dlr_callback(Request $req)
    {
        $sms_id           = $req->message_id;
        $sms_delivered_at = $req->datetime;
        $sms_status       = $req->status;
        $sms_user_id      = $req->user_id;

        $inputs = $req->all();

        try {
            $sms = Sms::select([
                'id',
                'sms_id',
                'user_id',
                'to',
                'status',
                'delivered_at',
                'sender_id',
            ])->where('sms_id', $sms_id)->first();
            if (!empty($sms)) {
                $sms->delivered_at = $sms_delivered_at;
                $sms->status = $sms_status;
                $sms->sender_id = $sms_user_id;
                $sms->save();
            }
            Log::info(json_encode($inputs));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function reply_callback(Request $req)
    {
        try {


            $inputs = $req->all();
            Log::info('Replay Callback: ');
            Log::info(json_encode($inputs));
            $x = $inputs;
            if (!empty($x['message_id']) && !empty($x['mobile']) && !empty($x['longcode'])) {
                $user_id = 1;
                $sender_number = SenderNumber::where('phone', $x['longcode'])->select('id')->first();
                $children_ids = [];
                $admin_name = '';
                if (!empty($sender_number)) {
                    $user = User::where('sender_number', $sender_number->id)->where('role', 'ADMIN')->select('id', 'name', 'lastname', 'company')->first();
                    if (!empty($user)) {
                        $user_id = $user->id;
                        $admin_name = $user->name . ' ' . $user->lastname;
                        if (trim($admin_name)) {
                            $admin_name = $user->company;
                        }
                        $childrens = $user->children();
                        if (!empty($childrens)) {
                            $children_ids = $childrens->pluck('id');
                        }
                    }
                }

                $message = !empty($x['response']) ? $x['response'] : 'no text';
                $sender_mobile = !empty($x['mobile']) ? $x['mobile'] : 'NA';
                $admin_mobile = !empty($x['longcode']) ? $x['longcode'] : '';
                if (empty($admin_name)) {
                    $admin_name = $admin_mobile;
                }
                $sender_name = $sender_mobile;
                if (!empty($sender_mobile)) {
                    $sender = Contact::where('phone', 'like', '%' . $sender_mobile . '%')->first();
                    if(!empty($sender)) {
                        $sender_name = $sender->name . ' ' . $sender->lastname;
                    }
                }

                $sms = Sms::create([
                    'sms_job_id' => 0,
                    'sms_id' => !empty($x['message_id']) ? $x['message_id'] : '',
                    'message' => $message,
                    'to' => $admin_mobile,
                    'name' => $admin_name,
                    'recipient' => $admin_name,
                    'list_id' => !empty($x['list_id']) ? $x['list_id'] : '',
                    'user_id' => $user_id,
                    'countrycode' => '',
                    'from' => $sender_mobile,
                    'from_name' => $sender_name,
                    'send_at' => !empty($x['datetime_entry']) ? $x['datetime_entry'] : NULL,
                    'dlr_callback' => '',
                    'cost' => isset($api_res['cost']) ? $api_res['cost'] : '',
                    'folder' => 'inbox',
                    'status' => 'received',
                    'local_status' => 'RECEIVED',
                ]);

                if (!empty($children_ids)) {
                    foreach ($children_ids as $child_id) {
                        $this->appy->sendNotification($child_id, 'SMS from: ' . $sender_mobile, $message);
                    }
                }

                return response()->json([$sms, $children_ids]);
            }
            return response()->json(['message' => 'message id and mobile number are missing'], 422);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
