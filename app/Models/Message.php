<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'from', 'to', 'body', 'type'];

    protected $hidden = ['created_at', 'updated_at'];



    // Relations
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
