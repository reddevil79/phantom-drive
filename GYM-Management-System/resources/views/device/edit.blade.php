@extends('app')
@section('content')

    <div class="rightside bg-grey-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::Open(['method' => 'POST','id' => 'devicesform','files'=>'true','action' => ['DeviceController@update',$device->id]]) !!}

                    <div class="panel no-border">
                        <div class="panel-title bg-white no-border">
                            <div class="panel-head">Enter Details of the user</div>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('name','Name') !!}
                                        {!! Form::text('name',$device->name,['class'=>'form-control', 'id' => 'name']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('serial','Serial') !!}
                                        {!! Form::text('serial',$device->serial,['class'=>'form-control', 'id' => 'serial']) !!}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('ip','IP') !!}
                                        {!! Form::text('ip',$device->ip,['class'=>'form-control', 'id' => 'ip']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('area','Area') !!}
                                        {!! Form::text('area',$device->area,['class'=>'form-control', 'id' => 'area']) !!}
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-2 pull-right">
                            <div class="form-group">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary pull-right']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::Close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer_scripts')
    <script src="{{ URL::asset('assets/js/userUpdate.js') }}" type="text/javascript"></script>
@stop
