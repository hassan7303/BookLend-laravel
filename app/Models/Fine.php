<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = ['borrow_id','days_late','amount','paid','paid_date'];

    public function borrow() { return $this->belongsTo(Borrow::class); }
}
