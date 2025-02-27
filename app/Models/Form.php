<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'background_color', 'font_family', 'form_labels'];

    public function fields()
    {
        return $this->hasMany(FormField::class);
    }
}
