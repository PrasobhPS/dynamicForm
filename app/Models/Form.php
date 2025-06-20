<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = 'forms';

    protected $fillable = [
        'form_title',
        'form_description',
    ];

    /**
     * Get the fields associated with the form.
     */
    public function fields()
    {
        return $this->hasMany(FormField::class, 'form_id');
    }
}
