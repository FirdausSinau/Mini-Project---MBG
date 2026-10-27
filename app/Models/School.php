<?php

namespace App\Models;

use Database\Factories\SchoolFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'alamat', 'kota'])]
class School extends Model
{
    /** @use HasFactory<SchoolFactory> */
    use HasFactory;
}
