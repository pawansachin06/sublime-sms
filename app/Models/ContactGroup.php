<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactGroup extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $table = 'contact_group';
}
