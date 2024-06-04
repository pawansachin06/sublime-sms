<?php

namespace App\Http\Controllers;

use App\Models\ContactGroup;
use Illuminate\Http\Request;

class ContactGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contact-groups.index');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactGroup $contatGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactGroup $contatGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactGroup $contatGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactGroup $contatGroup)
    {
        //
    }
}
