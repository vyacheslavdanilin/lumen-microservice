<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonAka extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'person_aka';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'parent_uid',
        'first_name',
        'last_name',
        'category',
    ];

}
