<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
		'project_name',
		'server',
		'database_names',
		'git_repo_url',
		'custom_rows_json',
		'files'
	];
}
