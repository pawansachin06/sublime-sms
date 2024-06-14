<?php

namespace App\Http\Controllers;

use App\Enums\ModelStatusEnum;
use App\Exports\ContactGroupsExport;
use App\Models\ContactGroup;
use App\Services\SMSApi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Log;

class ContactGroupController extends Controller
{
    protected $smsApi;

    function __construct(SMSApi $smsApi)
    {
        $this->smsApi = $smsApi;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $keyword = $req->keyword;
            $query = ContactGroup::query();
            if (!empty($keyword)) {
                $query = $query->where('name', 'like', '%' . $keyword . '%');
            }
            $data = $query->with('author:id,name')->orderBy('name', 'asc')->get(['id', 'uid', 'user_id', 'name', 'created_at']);
            $items = [];
            if (!empty($data) && count($data)) {
                foreach ($data as $item) {
                    $items[] = [
                        'id' => $item->id,
                        'uid' => $item->uid,
                        'name' => $item->name,
                        'createdBy' => 'Created by ' . $item->author->name . ' ' . $item->author->lastname,
                        'createdOn' => 'Created on ' . $item->created_at->format('jS \of F Y'),
                        'profile' => '',
                    ];
                }
            }
            return response()->json([
                'success' => true,
                'items' => $items,
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
            'name' => ['required', 'string', 'max:255']
        ], [
            'name.required' => 'Group name is required'
        ]);
        $input['user_id'] = $user->id;
        $input['status'] = ModelStatusEnum::PUBLISHED;
        $id = $req->id;
        $name = $input['name'];
        $is_updating = false;
        $item = null;
        if (!empty($id)) {
            $is_updating = true;
            $item = ContactGroup::where('id', $id)->first();
        } else {
            $duplicateItem = ContactGroup::where('name', $name)->first();
            if (!empty($duplicateItem)) {
                return response()->json(['message' => 'Name already exists'], 422);
            }
        }
        $msg = 'Created new group';

        try {
            if ($is_updating && !empty($item)) {
                $item->name = $name;
                $item->update();
                $msg = 'Updated group';
            } else {
                // add contact group to SMS API
                $res = $this->smsApi->add_list($name);
                if (!empty($res['id'])) {
                    $input['id'] = $res['id'];
                    $input['uid'] = $res['id'];
                    $item = ContactGroup::create($input);
                } else {
                    if(!empty($res['error']['description'])){
                        return response()->json([
                            'message' => $res['error']['description'],
                        ], 500);
                    } else {
                        return response()->json(['message'=> 'Something went wrong'], 500);
                    }
                }
            }
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'uid'=> $item->uid,
                    'name' => $item->name,
                    'createdBy' => 'Created by ' . $user->name . ' ' . $user->lastname,
                    'createdOn' => 'Created on ' . $item->created_at->format('jS \of F Y'),
                    'profile' => '',
                ],
                'reset' => true,
                'message' => $msg,
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

    public function exportDownload(Request $req)
    {
        $user = $req->user();
        $filename = 'contact-groups-' . date('Y-m-d-H-i-s') . '.xlsx';
        $query = ContactGroup::query();
        $query = $query->withTrashed()->with('author:id,username')->orderBy('uid');
        return (new ContactGroupsExport($query))->download($filename);
    }

    public function delete(Request $req)
    {
        $id = $req->id;
        try {
            $item = ContactGroup::where('id', $id)->first();
            if ($item) {
                $res = $this->smsApi->remove_list($item->uid);
                if(!empty($res['error']) && !empty($res['code']) && $res['code'] !== 'SUCCESS'){
                    $msg = $res['error'];
                    return response()->json([
                        'message' => !empty($msg['description']) ? $msg['description'] : 'Something went wrong',
                    ], 500);
                }
                $item->forceDelete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Deleted Contact Group',
            ]);
        } catch (Exception $e) {
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
