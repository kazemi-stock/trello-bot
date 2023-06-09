<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'card_id', 't_action_id', 'type', 'data', 'date'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'data' => 'array',
        'date' => 'datetime',
    ];

    // Relations
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
