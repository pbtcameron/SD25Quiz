<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $primaryKey='InstructorID';
    protected $fillable=['FirstName','LastName','id'];
    public $timestamps=false;

}
