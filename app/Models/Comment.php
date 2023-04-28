<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'card_id', 'contact_id', 'name', 'body', 'sent_at'];

    protected $hidden = ['created_at', 'updated_at'];


    // Relations
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }


}
