@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
				<div class="card-header">@if( $action == 'add' ) {{ __('Add Customer') }} @elseif( $action == 'info' ) {{ __('View Customer') }} @else {{ __('Edit Customer') }} @endif</div>

                    <div class="card-body">
                        <form method="POST" action="@if( $action == 'add' ) {{ route('customers.store') }} @else {{ route('customers.update', ['customer' => $customer->id]) }}@endif">
                            @csrf

							@if( $action == 'edit' )
							<input type="hidden" name="_method" value="put">
							@endif

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $customer->name) }}" required autocomplete="off" autofocus @if( $action == 'info' ) disabled @endif>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
							
							<div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea id="email" class="form-control" name="description" @if( $action == 'info' ) disabled @endif>{{ old('description', $customer->description) }}</textarea>
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
@endsection
