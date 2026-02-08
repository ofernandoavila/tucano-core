<?php

namespace App\Models;

class ##{{ name }}## extends Model
{
    protected $table = '##{{ table_name }}##';

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [ 'id' ];
}
