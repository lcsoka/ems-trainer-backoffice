<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $primaryKey = 'training_id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
