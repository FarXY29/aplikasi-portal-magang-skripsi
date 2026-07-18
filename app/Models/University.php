<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_pddikti',
        'name',
        'acronym',
        'city',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke seluruh mahasiswa (users) yang berasal dari universitas ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
