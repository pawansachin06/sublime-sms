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
            $data = Contact::latest()->with('groups:id,name')->paginate(2, [
                'id', 'name', 'lastname', 'company', 'phone', 'country'
            ])->withQueryString();
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

        try {
            $item = Contact::create($input);
            $item->groups()->sync($input['contact_group_id']);
            return response()->json([
                'success' => true,
                'reset' => true,
                'close' => true,
                'message' => 'Saved contact successfully'
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
