<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use App\Exports\ContactsExport;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contacts.index');
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
        //
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
