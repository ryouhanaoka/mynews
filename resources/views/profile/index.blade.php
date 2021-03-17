@extends('layouts.front')


@section('content')
    <div class="container">
        <hr color="#c0c0c0">
        @if (!is_null($headline))
            <div class="row">
                <div class="headline col-md-10 mx-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="caption mx-auto">
                                <div class="title p-2 mx-auto">
                                    <h1>プロフィール</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="name mx-auto">{{ str_limit($headline->name, 30) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="gender mx-auto">{{ str_limit($headline->gender, 30) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="hobby mx-auto">{{ str_limit($headline->hobby, 100) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="introduction mx-auto">{{ str_limit($headline->introduction, 400) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <hr color="#c0c0c0">
    </div>
    </div>
@endsection