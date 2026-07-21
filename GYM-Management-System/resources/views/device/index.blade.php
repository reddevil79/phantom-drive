@extends('app')

@section('content')

    <div class="rightside bg-grey-100">
        <!-- BEGIN PAGE HEADING -->
        <div class="page-head bg-grey-100">
            @include('flash::message')
            <h1 class="page-title">Devices</h1>
            <a href="{{ action('DeviceController@create') }}" class="btn btn-primary active pull-right" role="button"> Add</a></h1>
        </div>

        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel no-border ">
                        <div class="panel-title bg-white no-border">
                        </div>
                        <div class="panel-body no-padding-top bg-white">
                            <table id="staffs" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Serial</th>
                                    <th class="text-center">IP</th>
                                    <th class="text-center">Area</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    @foreach ($devices as $device)
                                        <td class="text-center">{{ $device->name}}</td>
                                        <td class="text-center">{{ $device->serial}}</td>
                                        <td class="text-center">{{ $device->ip }}</td>
                                        <td class="text-center">{{ $device->area }}</td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Actions</button>
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{ action('DeviceController@edit',['id' => $device->id]) }}">
                                                            Edit details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ action('DeviceController@restart',['id' => $device->id]) }}">
                                                            Restart Device
                                                        </a>
                                                    </li>
                                                    @if(Auth::user()->id != $device->id)
                                                        <li>
                                                            <a href="#" class="delete-record" data-delete-url="{{ url('device/'.$device->id.'/delete') }}"
                                                               data-record-id="{{ $device->id }}">Delete user</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                </tr>

                                @endforeach


                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footer_script_init')
    <script type="text/javascript">
        $(document).ready(function () {
            gymie.deleterecord();
        });
    </script>
@stop
