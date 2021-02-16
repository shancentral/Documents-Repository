<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use App\Model\Project;
use Illuminate\Http\Request;

use Storage;

class ProjectsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
	}

    public function index(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        $projects = $customer->projects()->paginate(30);
        $index = $projects->firstItem();

        return view('projects.index', compact('customer', 'projects', 'index'));
    }

    public function create(Request $request)
    {
        $action = "add";

        $project = new Project();
		$project->customer_id = $request->customer_id;
		$project->custom_rows = [];
		$project->files = [];

        return view('projects.add_edit', compact('action', 'project'));
	}
	
	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required',
		]);

		// files
		$files = [];

		$filez = empty($request->file('files')) ? [] : $request->file('files');

		foreach ($filez as $file) {
			$file_name = $file->getClientOriginalName();
			array_push($files, $file_name);
		}

		// custom rows
		$custom_rows = [];
		
		$row = 0;
		foreach($request->custom_field as $field){
			if( !empty($field) ){
				$custom_rows[$field] = $request->custom_value[$row];
			}

			++$row;
		}
		
		$project = new Project();

		$project->project_name = $request->input('name');
		$project->server = $request->input('server');
		$project->database_names = $request->input('database_names');
		$project->git_repo_url = $request->input('git_repo_url');
		$project->custom_rows_json = json_encode($custom_rows);
		$project->files = json_encode($files);
		$project->customer_id = $request->customer_id;

		$project->save();

		// upload files
		$project_directory = "customer_" . $project->customer_id . "_project_" . $project->id;
		$uploads_directories = Storage::disk('local')->directories('uploads');

		if (!in_array($project_directory, $uploads_directories)) {
			Storage::makeDirectory('uploads/' . $project_directory);
		}

		foreach ($filez as $file) {
			$file_name = $file->getClientOriginalName();
			$file->storeAs('uploads/' . $project_directory, $file_name, 'local');
		}


		return redirect()->route('customers.projects', $project->customer_id)->with('success', 'Project saved Successfully');
	}

	public function edit(Request $request)
	{
		if( isset($request->info) ){
			$action = "info";
		}else{
			$action = "edit";
		}

		$project = Project::find($request->id)->first();
		$project->custom_rows = ( empty($project->custom_rows_json) ) ? [] : json_decode($project->custom_rows_json, true);
		$project->files = ( empty($project->files) ) ? [] : json_decode($project->files, true);

		return view('projects.add_edit', compact('action', 'project'));
	}

	public function update(Request $request)
	{
		$request->validate([
			'name' => 'required',
		]);

		$project = Project::find($request->id);

		// files
		$files = [];

		$project_directory = "customer_" . $project->customer_id . "_project_" . $project->id;
		$uploads_directories = Storage::disk('local')->directories('uploads');

		if( !in_array($project_directory, $uploads_directories) ){
			Storage::makeDirectory('uploads/' . $project_directory);
		}

		$filez = empty( $request->file('files') ) ? [] : $request->file('files');

		foreach($filez as $file){
			$file_name = $file->getClientOriginalName();
			array_push($files, $file_name);

			$file->storeAs('uploads/' . $project_directory, $file_name, 'local');
		}

		$files_existing = ( empty($request->files_existing) ) ? [] : $request->files_existing;
		$files = array_merge($files_existing, $files);

		// custom rows
		$custom_rows = [];
		
		$row = 0;
		foreach($request->custom_field as $field){
			if( !empty($field) ){
				$custom_rows[$field] = $request->custom_value[$row];
			}

			++$row;
		}

		$project->project_name = $request->input('name');
		$project->server = $request->input('server');
		$project->database_names = $request->input('database_names');
		$project->git_repo_url = $request->input('git_repo_url');
		$project->custom_rows_json = json_encode($custom_rows);
		$project->files = json_encode($files);

		$project->save();
		return redirect()->route('customers.projects', $project->customer_id)->with('success', 'Project saved Successfully');
	}

	public function destroy(Request $request)
	{
		$project = Project::find($request->id);
		$project->delete();

		return redirect()->route('customers.projects', $project->customer_id)->with('success', 'Project deleted Successfully');
	}

	public function download(Request $request)
	{
		$project_directory = "customer_" . $request->customer_id . "_project_" . $request->project_id;
		return Storage::download('uploads/' . $project_directory . '/' . $request->file);
	}
}
