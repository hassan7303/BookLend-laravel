<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FineRule extends Model
{
    protected $fillable = ['per_day_amount','currency','active'];
}
