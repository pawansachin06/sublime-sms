<?php

namespace App\Http\Controllers;

use App\Enums\ModelStatusEnum;
use App\Models\ContactGroup;
use Exception;
use Illuminate\Http\Request;

class ContactGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        if($req->ajax()){
            $keyword = $req->keyword;
            $query = ContactGroup::query();
            if(!empty($keyword)){
                $query = $query->where('name', 'like', '%' . $keyword . '%');
            }
            $data = $query->with('author:id,name')->orderBy('name', 'asc')->get(['id', 'user_id', 'name', 'created_at']);
            $items = [];
            if(!empty($data) && count($data)){
                foreach ($data as $item) {
                    $items[] = [
                        'id'=> $item->id,
                        'name'=> $item->name,
                        'createdBy'=> 'Created by '. $item->author->name .' '. $item->author->lastname,
                        'createdOn'=> 'Created on '. $item->created_at->format('jS \of F Y'),
                        'profile'=> '',
                    ];
                }
            }
            return response()->json([
                'success'=> true,
                'items'=> $items,
            ]);
        } else {
            return view('contact-groups.index', []);
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
        $user = $req->user();
        $input = $req->validate([
            'name'=> ['required', 'string', 'max:255']
        ], [
            'name.required'=> 'Group name is required'
        ]);
        $input['user_id'] = $user->id;
        $input['status'] = ModelStatusEnum::PUBLISHED;
        $id = $req->id;
        $is_updating = false;
        $item = null;
        if(!empty($id)){
            $is_updating = true;
            $item = ContactGroup::where('id', $id)->first();
        }

        try {
            if($is_updating && !empty($item)){
                $item->name = $input['name'];
                $item->update();
            } else {
                $item = ContactGroup::create($input);
            }
            return response()->json([
                'success'=> true,
                'item'=> [
                    'id'=> $item->id,
                    'name'=> $item->name,
                    'createdBy'=> 'Created by '. $user->name .' '. $user->lastname,
                    'createdOn'=> 'Created on '. $item->created_at->format('jS \of F Y'),
                    'profile'=> 'BGCG Fixed Income Solutions',
                ],
                'reset'=> true,
                'message'=> 'Created new group'
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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

    public function delete(Request $req)
    {
        $id = $req->id;
        try {
            $item = ContactGroup::where('id', $id)->first();
            if($item) {
                $item->delete();
            }
            return response()->json([
                'success'=> true,
                'message'=> 'Deleted Contact Group',
            ]);
        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactGroup $contatGroup)
    {
        //
    }
}
