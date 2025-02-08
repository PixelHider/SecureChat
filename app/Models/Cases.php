<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'title',
        'description',
        'case_to',
        'case_number',
        'status',
        'user_id'
    ];

    protected $casts = [
        'title' => 'encrypted',
        'description' => 'encrypted',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
