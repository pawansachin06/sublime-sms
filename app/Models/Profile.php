<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory;
    use UuidTrait;
    use SoftDeletes;

    protected $table = 'profiles';

    public static $pivot_table = 'user_pivot_profile';


}
