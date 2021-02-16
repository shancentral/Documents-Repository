@extends('layouts.app')

@section('content')
    <div class="container">
		<div class="row pb-4">
			<div class="col-12">
			<a href="{{ route('projects.create', $customer->id) }}" class="btn btn-primary float-right">Add Project</a>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.no</th>
							<th>Project</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <td>{{ $index++ }}</td>
							<td>{{ $project->project_name }}</td>
                            <td>
								<a href="{{ route('projects.info', [$project->id, 'info']) }}" class="btn btn-outline-info">
                                    Info
								</a>
								
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-outline-dark">
                                    Edit
                                </a>

                                <form method="post" action="{{ route('projects.destroy', $project->id) }}" class="d-inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                    <button class="btn btn-outline-dark">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                {{ $projects->links() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
