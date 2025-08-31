<?php

namespace Modules\Visual\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = 'visual_themes';
    protected $fillable = ['slug','title','tokens','config','is_default'];
    protected $casts = [
        'tokens' => 'array',
        'config' => 'array',
        'is_default' => 'boolean',
    ];
}
