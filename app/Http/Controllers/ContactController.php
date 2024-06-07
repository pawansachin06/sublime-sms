<?php

namespace App\Http\Controllers;

use App\Enums\ModelStatusEnum;
use App\Models\Contact;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\ContactsExport;
use Exception;

class ContactController extends Controller
{
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

            $data = $query->latest()->with('groups:id,name')->paginate(10, [
                'id', 'name', 'lastname', 'company', 'phone', 'country','comments',
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
            'contact_group_id' => ['required'],
            'contact_group_id.*' => ['required', Rule::exists(ContactGroup::class, 'id')],
        ], [
            'contact_group_id.required' => 'Select at least one Group to save'
        ]);

        $input['status'] = ModelStatusEnum::PUBLISHED;
        $message = 'Saved new contact successfully';
        $is_updating = false;

        try {

            if(!empty($input['id'])){
                $is_updating = true;
                $item = Contact::findOrFail($input['id']);
                $item->update($input);
                $item->groups()->sync($input['contact_group_id']);
                $message = 'Updated contact successfully';
            } else {

                $duplicate_item = Contact::where('country', $input['country'])
                                    ->where('phone', $input['phone'])->first();
                if(!empty($duplicate_item)){
                    return response()->json([
                        'success'=> false,
                        'message'=> 'Contact with same phone number already exists',
                    ]);
                }

                $item = Contact::create($input);
                $item->groups()->sync($input['contact_group_id']);
            }

            return response()->json([
                'success' => true,
                'reload'=> !$is_updating,
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
        // code...
    }

    public function exportDownload(Request $req)
    {
        $user = $req->user();
        $id = $req->id;
        $item = ContactGroup::select('id')->where('id', $id)->firstOrFail();
        $filename = 'contacts-' . date('Y-m-d-H-i-s') . '.xlsx';
        $query = Contact::query();
        $query = $query->withTrashed()->orderBy('name');
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
