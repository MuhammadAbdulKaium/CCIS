@extends('layouts.master')

@section('styles')
    <style>
        .select2-selection--single {
            height: 33px !important;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Accounts |<small>Ledger Summary</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="">Accounts</a></li>
                <li>Reports</li>
                <li class="active">Trial Balance</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center">
                    <a href="#" class="close"
                       style="text-decoration:none" data-dismiss="alert"
                       aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center">
                    <a href="#" class="close"
                       style="text-decoration:none" data-dismiss="alert"
                       aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center">
                    <a href="#" class="close"
                       style="text-decoration:none" data-dismiss="alert"
                       aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger alert-auto-hide" style="text-align: center">
                        <a href="#" class="close"
                           style="text-decoration:none" data-dismiss="alert"
                           aria-label="close">&times;</a>{{ $error }}</p>
                @endforeach
            @endif

            <div class="box box-solid">

                <div class="box-body">
                  <div class="p-2 ">


                  </div>

                    <div class="marks-table-holder table-responsive" id="trial-balance-table">
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



{{-- Scripts --}}
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            console.log("Hellow ...");
            const arr= {

            };

            arr.x="2";
            console.log(arr)

        });
        class car {
            na

        }


    </script>
@stop