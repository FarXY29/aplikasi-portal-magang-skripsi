<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn',
        'name',
        'level',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke seluruh siswa/mahasiswa (users) yang berasal dari sekolah/institusi ini.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
