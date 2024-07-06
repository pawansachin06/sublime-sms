<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contact_groups';

    protected $fillable = [
        'id', 'uid', 'name', 'user_id', 'status', 'meta'
    ];

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, Contact::$pivot_table, 'contact_group_id', 'contact_id');
    }
}
