<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    public static $pivot_table = 'contact_pivot_contact_group';

    protected $fillable = [
        'name', 'lastname', 'phone', 'company', 'country', 'status', 'profile_id', 'comments', 'meta'
    ];

    public function profile()
    {
        return $this->belongsTo(User::class, 'profile_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(ContactGroup::class, $this::$pivot_table, 'contact_id', 'contact_group_id');
    }

}
