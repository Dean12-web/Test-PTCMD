<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'income'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
