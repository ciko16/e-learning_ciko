<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    protected $fillable = ['name', 'description', 'lecturer_id'];

public function dosen() {
    return $this->belongsTo(User::class, 'lecturer_id');
}
public function mahasiswa() {
    return $this->belongsToMany(User::class);
}
use softDeletes;
}
