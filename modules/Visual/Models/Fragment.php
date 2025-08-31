<?php

namespace Modules\Visual\Models;

use Illuminate\Database\Eloquent\Model;

class Fragment extends Model
{
    protected $table = 'visual_fragments';
    protected $fillable = ['slug','title','type','zone','schema','data','html_cached','css_inline','is_active','updated_by'];
    protected $casts = [
        'schema' => 'array',
        'data' => 'array',
        'is_active' => 'boolean',
    ];
}
