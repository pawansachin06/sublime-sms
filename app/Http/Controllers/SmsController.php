<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Sms;
use App\Models\SmsJob;
use App\Models\Template;
use Exception;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $current_user = $req->user();
        $profile_id = $current_user->getActiveProfile();
        if ($req->ajax()) {
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
                'name',
                'recipient',
                'send_at',
                'folder',
                'cost',
                'status'
            ]);
            if (!$current_user->isSuperAdmin()) {
                $query = $query->where('user_id', $profile_id);
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
            $data = $query->orderBy('id', 'DESC')->paginate(20);
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
                    $items[] = [
                        'id' => $row->id,
                        'message' => $row->message,
                        'to' => $row->to,
                        'send_at' => $row->send_at->format('d/m/Y h:i A'),
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
            'title' => ['required', 'string', 'max:255'],
            'send_at' => ['nullable', 'string'],
            'contact_id' => ['nullable'],
            'contact_id.*' => ['nullable', Rule::exists(Contact::class, 'id')],
            'contact_group_uid' => ['nullable'],
            'contact_group_uid.*' => ['nullable', Rule::exists(ContactGroup::class, 'id')],
            'message' => ['required', 'string'],
            'isTesting' => ['nullable'],
        ], []);

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

            SmsJob::create([
                'name' => $input['title'],
                'user_id' => $input['profile_id'],
                'send_at' => $send_at,
                'template_id' => $input['template_id'],
                'list_uids' => $list_uids,
                'numbers' => $numbers,
                'message' => $input['message'],
                'scheduled' => $scheduled,
                'status' => $isTesting ? 'TESTING' : 'PENDING',
            ]);
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
            // $e->getMessage();
        }
    }
}
