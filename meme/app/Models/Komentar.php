<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $fillable = ['text', 'prispevek_id', 'id_uzivatel'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_uzivatel');
    }

    public function prispevek()
    {
        return $this->belongsTo(Prispevek::class, 'prispevek_id');
    }
}