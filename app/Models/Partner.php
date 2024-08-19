<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = [
        "name"
    ];
    
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
