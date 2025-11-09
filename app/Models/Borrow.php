<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = ['user_id','book_copy_id','borrow_date','due_date','return_date','status'];

    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function bookCopy() { return $this->belongsTo(BookCopy::class); }
}
