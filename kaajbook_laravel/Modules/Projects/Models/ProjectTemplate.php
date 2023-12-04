<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    protected $fillable = ['template_name', 'tasks'];
}

