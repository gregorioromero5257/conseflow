<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FolioDBP extends Model
{
    protected $connection = 'csct';

    protected $table = 'folios';
    public $timestamps = false;
}
