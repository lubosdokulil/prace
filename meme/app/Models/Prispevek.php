<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Prispevek extends Model
{
    public $timestamps = false;
    protected $fillable = ['fotka', 'id_uzivatel'];
    
        protected $table = 'prispevek';
}