<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'card_id', 'name', 'phone'];

    protected $hidden = ['created_at', 'updated_at'];



    // Relations
    public function card()
    {
        return $this->hasOne(Card::class, 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
