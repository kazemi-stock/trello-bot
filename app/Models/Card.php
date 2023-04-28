<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 't_board_id', 't_card_id', 'desc'];

    protected $hidden = ['created_at', 'updated_at'];

    // Relations
    public function contact()
    {
        return $this->hasOne(Contact::class, 'card_id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
