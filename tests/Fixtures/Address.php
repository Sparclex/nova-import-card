<?php

namespace Sparclex\NovaImportCard\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
