<div class="col-md-12">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                    <strong>{!! $message !!}</strong>
            </div>            
        @endif
        @if ($message = Session::get('danger'))
            <div class="alert alert-danger alert-block">
                    <strong>{!! $message !!}</strong>
            </div>               
        @endif
</div>

@if(count($errors) > 0 )
<div class="col-sm-12">         
    <div class="alert alert-danger alert-block">
            @foreach($errors->all() as $error)                    
                <strong>{{ $error }}</strong>
                <br>
            @endforeach
    </div>
</div>
@endif
