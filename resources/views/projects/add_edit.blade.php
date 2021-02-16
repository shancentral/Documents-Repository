@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
				<div class="card-header">@if( $action == 'add' ) {{ __('Add Project') }} @elseif($action == 'info') {{ __('View Project') }} @else {{ __('Edit Project') }} @endif</div>

                    <div class="card-body">
                        <form method="post" action="@if( $action == 'add' ) {{ route('projects.store', $project->customer_id) }} @else {{ route('projects.update', $project->id) }} @endif" enctype="multipart/form-data">
                            @csrf

							@if( $action == 'edit' )
							<input type="hidden" name="_method" value="put">
							@endif

							<div class="card mb-4">
								<div class="card-body">
									<div class="form-group row">
										<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Project Name') }}</label>

										<div class="col-md-6">
											<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $project->project_name) }}" required autocomplete="off" autofocus @if($action == 'info') disabled @endif>

											@error('name')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>
								</div>
							</div>
							
							<div class="card mb-4">
								<div class="card-body">
									<p class="h5 pb-3">Main data</p>

									<div class="form-group row">
										<label for="server" class="col-md-4 col-form-label text-md-right">{{ __('Server') }}</label>

										<div class="col-md-6">
											<input id="server" type="text" class="form-control" name="server" value="{{ old('server', $project->server) }}" autocomplete="off" @if($action == 'info') disabled @endif>
										</div>
									</div>
									
									<div class="form-group row">
										<label for="database_names" class="col-md-4 col-form-label text-md-right">{{ __('Database Name(s)') }}</label>

										<div class="col-md-6">
											<input id="database_names" type="text" class="form-control" name="database_names" value="{{ old('database_names', $project->database_names) }}" autocomplete="off" @if($action == 'info') disabled @endif>
										</div>
									</div>
									
									@if( $action == 'info' )
									<div class="form-group row align-items-center">
										<label for="git_repo_url" class="col-md-4 col-form-label text-md-right">{{ __('Git Repo Url') }}</label>

										<div class="col-md-6">
											@if( !empty(old('git_repo_url', $project->git_repo_url)) )
												<a href="{{ old('git_repo_url', $project->git_repo_url) }}" target="_blank">
													{{ old('git_repo_url', $project->git_repo_url) }}
												</a>
											@endif
										</div>
									</div>

									@else
									<div class="form-group row">
										<label for="git_repo_url" class="col-md-4 col-form-label text-md-right">{{ __('Git Repo Url') }}</label>

										<div class="col-md-6">
											<input id="git_repo_url" type="text" class="form-control" name="git_repo_url" value="{{ old('git_repo_url', $project->git_repo_url) }}" autocomplete="off">
										</div>
									</div>
									@endif
								</div>
							</div>

							<div class="card mb-4">
								<div class="card-body" id="custom-fields-container">
									<p class="h5 pb-3">Additional Info (custom fields)</p>

									<div class="form-group row d-none" id="custom-fields-template">
										<div class="col-md-4">
											<input type="text" class="form-control" name="custom_field[]" value="" autocomplete="off">
										</div>

										<div class="col-md-6">
											<input type="text" class="form-control" name="custom_value[]" value="" autocomplete="off">
										</div>

										<div class="col-md-2">
											<div class="btn btn-danger remove-custom-row">Remove</div>
										</div>
									</div>

									@foreach($project->custom_rows as $field => $value)
										<div class="form-group row">
											<div class="col-md-4">
												<input type="text" class="form-control" name="custom_field[]" value="{{ $field }}" autocomplete="off" @if($action == 'info') disabled @endif>
											</div>

											<div class="col-md-6">
											<input type="text" class="form-control" name="custom_value[]" value="{{ $value }}" autocomplete="off" @if($action == 'info') disabled @endif>
											</div>

											@if($action != 'info')
											<div class="col-md-2">
												<div class="btn btn-danger remove-custom-row">Remove</div>
											</div>
											@endif
										</div>
									@endforeach

									@if($action != 'info')
									<div class="form-group row justify-content-start" id="add-row-parent">
										<div class="col-md-2">
											<div class="btn btn-dark" id="add-row">Add Row</div>
										</div>
									</div>
									@endif
								</div>
							</div>

							<div class="card mb-4">
								<div class="card-body">
									@if($action != 'info')
									<div class="form-group row">
										<label for="files" class="col-md-4 col-form-label text-md-right">{{ __('Files') }}</label>

										<div class="col-md-6">
											<div class="input-group mb-3">
												<div class="custom-file-parent">
													<input type="file" id="files" class="custom-file-input" name="files[]" multiple>
													<label class="custom-file-label" for="files">Choose file(s)</label>
												</div>
											</div>
										</div>
									</div>
									@endif

									@foreach($project->files as $file)
									<div class="form-group row">
										<label for="files" class="col-md-4 col-form-label text-md-right">{{ $file }}</label>
										<input type="hidden" name="files_existing[]" value={{ $file }}>

										<div class="col-md-6">
											<a class="btn btn-secondary" target="_blank" href="{{ route('projects.download', [$project->customer_id, $project->id, $file]) }}">
												Download
											</a>

											@if($action != 'info')
											<div class="btn btn-danger remove-file">Remove</div>
											@endif
										</div>
									</div>
									@endforeach
								</div>
							</div>

							@if( $action != 'info' ) 
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
										@if( $action == 'add' ) 
											{{ 'Add' }}
										@else 
											{{ 'Edit' }}
										@endif
                                    </button>
                                </div>
							</div>
							@endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
	
<script type="application/javascript">
	window.onload = function(event){
		// vars
		var content = document.querySelector('#custom-fields-template').innerHTML;
		var add_row_parent = document.querySelector('#add-row-parent');

		// add custom field row
		$('#add-row').click(function(){
			let div = document.createElement("div");

			div.classList.add('form-group', 'row');
			div.innerHTML = content;

			add_row_parent.insertAdjacentElement( 'beforebegin', div );

			removeCustomRowEvent();
		});

		function removeCustomRowEvent(){
			$('.remove-custom-row').off();
			$('.remove-custom-row').on('click', removeRow);
		}

		function removeRow(){
			$(this).closest('.form-group').remove();
		}

		// custom file input fix
		$('.custom-file-input').change(function(e){
			let fileName = '';

			let index = 0;
			for(a in this.files){
				if( index > 0 ){
					fileName += ', ';
				}

				fileName += this.files[a].name;

				++index;
			}

			var nextSibling = e.target.nextElementSibling;
			nextSibling.innerText = fileName;
		});

		// on load triggers
		removeCustomRowEvent();
		removeFile();

		function removeFile(){
			$('.remove-file').click(function(){
				$(this).closest('.form-group').remove();
			})
		}

		// switch toggle
		$('.switch-toggle').bootstrapToggle();
	}
</script>
@endsection
