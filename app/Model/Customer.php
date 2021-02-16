<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
		'name',
		'description'
	];

	public function projects()
    {
        return $this->hasMany('App\Model\Project');
    }
}
