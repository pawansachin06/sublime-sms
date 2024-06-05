<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $table = 'contacts';

    public static $pivot_table = 'contact_pivot_contact_group';

}