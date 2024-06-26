<?php

namespace App\Http\Controllers;

use App\Models\ContactGroup;
use App\Models\Sms;
use App\Models\SmsJob;
use App\Models\Template;
use Exception;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'profile_id' => ['required', 'string'],
            'template_id' => ['nullable', 'string', Rule::exists(Template::class, 'id')],
            'title' => ['nullable', 'string', 'max:255'],
            'send_at' => ['nullable', 'string'],
            'contact_group_uid' => ['nullable'],
            'contact_group_uid.*' => ['nullable', Rule::exists(ContactGroup::class, 'uid')],
            'separate_numbers' => ['nullable'],
            'message' => ['required', 'string'],
        ], []);

        if (empty($input['contact_group_uid']) && empty($input['separate_numbers'])) {
            return response()->json(['message' => 'Please select recipient'], 422);
        }

        $send_at = '';
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

            if (!empty($input['numbers'])) {
                $numbers = $input['numbers'];
            }

            $send_at = date('Y-m-d H:i:s');

            SmsJob::create([
                'name' => $input['title'],
                'profile_id' => $input['profile_id'],
                'send_at' => $send_at,
                'template_id' => $input['template_id'],
                'list_uids' => $list_uids,
                'numbers' => $numbers,
                'message' => $input['message'],
                'scheduled' => $scheduled,
                'status' => 'PENDING',
            ]);
            if (!empty($send_at) && !empty($send_at_obj)) {
                return response()->json([
                    'scheduled'=> true,
                    'message' => 'Horray! Your SMS has been scheduled for',
                    'message2' => $send_at_obj->format('D M j g:i A Y'),
                ]);
            } else {
                return response()->json([
                    'scheduled'=> false,
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
        $sms_phone        = $req->mobile;
        $sms_delivered_at = $req->datetime;
        $sms_status       = $req->status;
        $sms_user_id      = $req->user_id;

        dd($sms_id, $sms_phone, $sms_delivered_at, $sms_status, $sms_user_id);
    }
}