<?php

namespace Sparclex\NovaImportCard\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
