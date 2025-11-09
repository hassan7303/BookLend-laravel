<?php
namespace App\Models;

use App\Enums\BookCopiesEnums;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $fillable = ['book_id','barcode','status'];

    protected $casts = [
        'status' => BookCopiesEnums::class,
    ];
    public function book() { return $this->belongsTo(Book::class); }
    public function borrows() { return $this->hasMany(Borrow::class); }
}
