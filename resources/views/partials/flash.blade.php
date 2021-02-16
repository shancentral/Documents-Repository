@if( session('success') )
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    {{ session('success')  }}
                </div>
            </div>
        </div>
    </div>
@elseif( session('failure') )
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    {{ session('failure')  }}
                </div>
            </div>
        </div>
    </div></div>
@endif
