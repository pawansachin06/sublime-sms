<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    use UuidTrait;

    protected $fillable = [ 'id', 'key', 'value', 'type', 'tag' ];
}
