@extends('app')

@section('content')
    <?php use Carbon\Carbon; ?>

    <div class="rightside bg-grey-100">
        <div class="page-head bg-grey-100 padding-top-15 no-padding-bottom">
            @include('flash::message')
        </div>
        <div class="container-fluid">

            <div class="row"><!-- Main row -->
                <div class="col-md-12"><!-- Main Col -->
                    <div class="panel no-border ">
                        <div class="panel-title">
                            <div class="panel-head font-size-20">Scanning Fingerprint</div>

                                    </div>
                        <div class="panel-body">
                            <div class="row">
                                <a class="btn btn-success" onclick="check()">
                                    <span>Check Fingerprint Status</span>
                                </a>
                                <a class="btn btn-danger" href="{{ action('MembersController@cancelFPreg',['id' => $member->id]) }}">
                                    <span>Cancel</span>
                                </a>
                                <!--Main row start-->
                              <img style=" width: 29%;"src="https://media.istockphoto.com/id/1069269208/vector/finger-print-scan.jpg?s=612x612&w=0&k=20&c=mbprbDIuqYUBnYr4pKv1JCXq0XDTodJW8vplxIc__fs=">

                            </div>   <!-- End Of Main Row -->
                        </div>
                                </div>   <!-- End of Outer Column -->



                            </div>   <!-- End Of Main Row -->
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script>
        function  check(){

            $.ajax({
                url: '{{ action('MembersController@checkFP',['id' => $member->id]) }}',
                type: 'get',
                data: {},
                success: function(data) {
                    if (data.message === 'Success') {

                        alert("Successfully scanned fingerprint");
                        window.location.href = "{{ action('MembersController@show',['id' => $member->id]) }}";

                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    </script>
@stop
