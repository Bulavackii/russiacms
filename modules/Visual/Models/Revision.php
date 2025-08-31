<?php

namespace Modules\Visual\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $table = 'visual_revisions';
    protected $fillable = ['snapshot','created_by'];
    protected $casts = [
        'snapshot' => 'array',
    ];

    public function target()
    {
        return $this->morphTo();
    }
}
