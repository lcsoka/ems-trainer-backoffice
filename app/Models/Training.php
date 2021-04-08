<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = ['length', 'user_id', 'training_mode'];

    protected $hidden = ['training_values'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingValues()
    {
        return $this->hasOne(TrainingValues::class);
    }
}
