<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MajorCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }

    public function internshipPositions(): HasMany
    {
        return $this->hasMany(InternshipPosition::class, 'required_major_category_id');
    }
}
