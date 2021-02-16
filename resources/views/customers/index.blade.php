@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Customer</th>
							<th>Description</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td>{{ $customer->name }}</td>
							<td>{{ $customer->description }}</td>
                            <td>
								<a href="{{ route('customers.info', [$customer->id]) }}" class="btn btn-outline-info">
                                    Info
								</a>

								 <a href="{{ route('customers.projects', $customer->id) }}" class="btn btn-outline-info">
                                    Projects
								</a>
								
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-dark">
                                    Edit
                                </a>

                                <form method="post" action="{{ route('customers.destroy', $customer->id) }}" class="d-inline">
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
                                {{ $customers->links() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
