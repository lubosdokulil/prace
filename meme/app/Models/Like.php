<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['id_uzivatel', 'prispevek_id'];

    public function prispevek()
    {
        return $this->belongsTo(Prispevek::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_uzivatel');
    }
}