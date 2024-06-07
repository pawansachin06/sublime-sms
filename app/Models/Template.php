<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $table = 'templates';

    protected $fillable = [
        'name', 'profile_id', 'message', 'status', 'meta'
    ];
}
