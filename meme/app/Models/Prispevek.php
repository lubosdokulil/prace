<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Prispevek extends Model
{
    public $timestamps = true;
    protected $fillable = ['fotka', 'id_uzivatel'];
    
    protected $table = 'prispevek';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_uzivatel');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('id_uzivatel', $userId)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Komentar::class, 'prispevek_id');
    }
}