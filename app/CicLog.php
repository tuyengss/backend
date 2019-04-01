<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CicLog extends Model
{
    public $table = 'cic_logs';

    protected $fillable = [
        'tctd_id', 'admin_note', 'status' , 'cs_id' ,'cs_name' 
    ];
}
