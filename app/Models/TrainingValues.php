<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingValues extends Model
{
    use HasFactory;

    protected $fillable = ['values', 'training_id'];

    protected $primaryKey = 'id';

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

}
