<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title','author_id','category_id','description'];

    public function author() { return $this->belongsTo(Author::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function copies() { return $this->hasMany(BookCopy::class); }

    // available copies count
    public function availableCount()
    {
        return $this->copies()->where('status','available')->count();
    }
}
