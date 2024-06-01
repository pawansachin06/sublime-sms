<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Laravel\Jetstream\Membership as JetstreamMembership;

class Membership extends JetstreamMembership
{

    use UuidTrait;

}
