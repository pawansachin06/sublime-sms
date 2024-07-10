<?php

namespace App\Http\Controllers;

use App\Enums\ModelStatusEnum;
use App\Models\Contact;
use App\Models\ContactGroup;
// use App\Services\SMSApi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\ContactsExport;
use App\Imports\ContactsImport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    // protected $smsApi;

    function __construct(
        // SMSApi $smsApi
    )
    {
        // $this->smsApi = $smsApi;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $keyword = $req->keyword;
            $phone = $req->phone;
            $query = Contact::query();

            if (!empty($phone)) {
                $query = $query->where('phone', 'like', '%' . $phone . '%');
            }
            if (!empty($keyword)) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('lastname', 'like', '%' . $keyword . '%')
                        ->orWhere('company', 'like', '%' . $keyword . '%');
                });
            }

            $data = $query->latest()->with('groups:id,uid,name')->paginate(10, [
                'id',
                'name',
                'lastname',
                'company',
                'phone',
                'country',
                'comments',
            ]);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            $countries = config('countries');
            return view('contacts.index', [
                'countries' => $countries,
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
            'id' => ['nullable', 'string', Rule::exists(Contact::class, 'id')],
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'numeric',],
            'country' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string'],
            'contact_group_uid' => ['required'],
            'contact_group_uid.*' => ['required', Rule::exists(ContactGroup::class, 'uid')],
        ], [
            'contact_group_uid.required' => 'Select at least one Group to save'
        ]);

        $input['status'] = ModelStatusEnum::PUBLISHED;
        $message = 'Saved new contact successfully';
        $is_updating = false;

        try {

            if (!empty($input['id'])) {
                $is_updating = true;
                $item = Contact::findOrFail($input['id']);

                $old_uids = $item->groups->pluck('uid')->toArray();
                $new_uids = $input['contact_group_uid'];

                $update_uids = array_intersect($new_uids, $old_uids);
                $delete_uids = array_diff($old_uids, $new_uids);
                $create_uids = array_diff($new_uids, $old_uids);

                if (!empty($update_uids)) {
                    // foreach ($update_uids as $update_uid) {
                    //     $res = $this->smsApi->edit_list_member($update_uid, [
                    //         'phone' => $input['phone'],
                    //         'countrycode' => $input['country'],
                    //         'name' => $input['name'],
                    //         'lastname' => $input['lastname'],
                    //         'company' => $input['company'],
                    //         'comments' => $input['comments'],
                    //     ]);
                    // }
                    // if (!empty($res['msisdn'])) {
                    //     $input['phone'] = $res['msisdn'];
                    // }
                }

                if (!empty($delete_uids)) {
                    // foreach ($delete_uids as $delete_uid) {
                    //     $res = $this->smsApi->delete_from_list($delete_uid, [
                    //         'phone'=> $item->phone,
                    //         'country'=> $item->country
                    //     ]);
                    // }
                    // if (!empty($res['msisdn'])) {
                    //     $input['phone'] = $res['msisdn'];
                    // }
                }

                // if (!empty($create_uids)) {
                //     foreach ($create_uids as $create_uid) {
                //         $res = $this->smsApi->add_to_list($create_uid, [
                //             'phone' => $input['phone'],
                //             'countrycode' => $input['country'],
                //             'name' => $input['name'],
                //             'lastname' => $input['lastname'],
                //             'company' => $input['company'],
                //             'comments' => $input['comments'],
                //         ]);
                //     }
                //     if (!empty($res['msisdn'])) {
                //         $input['phone'] = $res['msisdn'];
                //     }
                // }

                $item->update($input);
                $item->groups()->sync($input['contact_group_uid']);
                $message = 'Updated contact successfully';
            } else {

                $duplicate_item = Contact::where('country', $input['country'])
                    ->where('phone', $input['phone'])->first();
                if (!empty($duplicate_item)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Contact with same phone number already exists',
                    ]);
                }

                // add contact to api list
                // $uids = $input['contact_group_uid'];
                // foreach ($uids as $uid) {
                //     $res = $this->smsApi->add_to_list($uid, [
                //         'phone'=> $input['phone'],
                //         'countrycode'=> $input['country'],
                //         'name'=> $input['name'],
                //         'lastname'=> $input['lastname'],
                //         'company'=> $input['company'],
                //         'comments'=> $input['comments'],
                //     ]);
                //     if(!empty($res['msisdn'])){
                //         $input['phone'] = $res['msisdn'];
                //     }
                // }

                $item = Contact::create($input);
                $item->groups()->sync($input['contact_group_uid']);
            }

            return response()->json([
                'success' => true,
                'reload' => !$is_updating,
                'reset' =>  !$is_updating,
                'close' => !$is_updating,
                'message' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, Contact $contact)
    {
        //
    }

    public function importUpload(Request $req)
    {
        $input = $req->validate([
            'group_id' => ['nullable', Rule::exists(ContactGroup::class, 'id')],
            'importFile' => ['required', 'file', 'mimes:xls,xlsx'],
            'newPhoneNumberAction' => ['nullable', 'in:update,ignore'],
            'step' => ['required', 'string'],
        ]);

        $step = $req->step;
        $newPhoneNumbersAction = 'unknown';
        if (!empty($input['newPhoneNumberAction'])) {
            $newPhoneNumbersAction = $input['newPhoneNumberAction'];
        }
        $group_id = $input['group_id'];
        try {
            $group = ContactGroup::select('name')->where('id', $group_id)->first();
            $group_name = '';
            if (!empty($group)) {
                $group_name = $group->name;
            }
            if ($step == 'upload') {
                $import = new ContactsImport($group_id, $newPhoneNumbersAction);
                Excel::import($import, $req->file('importFile'));
                $countHasNewPhoneNumbers = 0;
                if (is_iterable($import->hasNewPhoneNumbers)) {
                    $countHasNewPhoneNumbers = count($import->hasNewPhoneNumbers);
                }
                if ($countHasNewPhoneNumbers > 0) {
                    $msg = '(' . $countHasNewPhoneNumbers . ') Contact';
                    $msg .= ($countHasNewPhoneNumbers > 1 ? 's' : '');
                    $msg .= ' already exists. But, phone numbers do not match';
                    return response()->json([
                        'message' => $msg,
                        'status' => false,
                        'hasNewPhoneNumbers' => true,
                        'newPhoneNumbers' => $countHasNewPhoneNumbers,
                    ]);
                } else {
                    $newPhoneNumbersAction = 'ignore';
                    $import = new ContactsImport($group_id, $newPhoneNumbersAction);
                    Excel::import($import, $req->file('importFile'));
                    $contactsCount = $import->totalContactsImported;
                    $contactsUpdateCount = $import->totalContactsUpdated;
                    $msg = 'Hooray! ';
                    if (!empty($contactsUpdateCount)) {
                        $msg .= $contactsUpdateCount . ' contact' . ($contactsUpdateCount > 1 ? 's' : '') . ' updated';
                    }
                    if (!empty($contactsCount)) {
                        if (!empty($contactsUpdateCount)) {
                            $msg .= ', ';
                        }
                        $msg .= $contactsCount . ' contact' . ($contactsCount > 1 ? 's' : '') . ' added ';
                    }
                    if (!empty($group_name)) {
                        $msg .= ' to group "' . $group_name . '"';
                    }
                    if (!empty($contactsCount) || !empty($contactsUpdateCount)) {
                        return response()->json(['message' => $msg]);
                    } else {
                        return response()->json([
                            'count' => $contactsCount,
                            'countUpdate' => $contactsUpdateCount,
                            'message' => 'No new contact to import',
                        ], 422);
                    }
                }
            } elseif ($step == 'hasNewPhoneNumbers') {
                $import = new ContactsImport($group_id, $newPhoneNumbersAction);
                Excel::import($import, $req->file('importFile'));
                $contactsCount = $import->totalContactsImported;
                $contactsUpdateCount = $import->totalContactsUpdated;
                $msg = 'Hooray! ';
                if (!empty($contactsUpdateCount)) {
                    $msg .= $contactsUpdateCount . ' contact' . ($contactsUpdateCount > 1 ? 's' : '') . ' updated';
                }
                if (!empty($contactsCount)) {
                    if (!empty($contactsUpdateCount)) {
                        $msg .= ', ';
                    }
                    $msg .= $contactsCount . ' contact' . ($contactsCount > 1 ? 's' : '') . ' added ';
                }
                if (!empty($group_name)) {
                    $msg .= ' to group "' . $group_name . '"';
                }
                if (!empty($contactsCount) || !empty($contactsUpdateCount)) {
                    return response()->json(['message' => $msg]);
                } else {
                    return response()->json([
                        'count' => $contactsCount,
                        'countUpdate' => $contactsUpdateCount,
                        'message' => 'No new contact to import',
                    ], 422);
                }
            } else {
                return response()->json(['message' => 'Unknow import, please refresh page'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function exportDownload(Request $req)
    {
        $user = $req->user();
        $id = $req->id;
        $item = ContactGroup::select('id')->where('id', $id)->firstOrFail();
        $filename = 'contacts-' . date('Y-m-d-H-i-s') . '.xlsx';
        // $query = Contact::query();
        $query = $item->contacts()->orderBy('name');
        return (new ContactsExport($query))->download($filename);
    }

    public function delete(Request $req)
    {
        $user = $req->user();
        $id = $req->id;
        if (empty($id)) {
            return response()->json([
                'message' => 'Contact ID is missing'
            ], 422);
        }
        $item = Contact::findOrFail($id);
        try {

            // $res = $this->smsApi->delete_from_list(0, [
            //     'phone' => $item->phone,
            //     'country' => $item->country
            // ]);
            // if (!empty($res['error']) && !empty($res['code']) && $res['code'] !== 'SUCCESS') {
            //     $msg = $res['error'];
            //     return response()->json([
            //         'message' => !empty($msg['description']) ? $msg['description'] : 'Something went wrong',
            //     ], 500);
            // }

            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted contact',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
