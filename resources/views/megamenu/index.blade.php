@extends('layouts.app')

@section('megamenu', 'active border-start border-3 border-primary')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-primary-soft border-0 p-3">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($menus as $menu)
                                <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex">
                                    <a href="{{ $menu['route'] }}" class="btn megamenu-primary rounded-4 w-100 h-100">
                                        <div class="card border-0 bg-transparent h-100">
                                            <div class="row g-0 h-100">
                                                <div class="col-2 d-flex justify-content-center align-items-center">
                                                    <span class="bg-primary-subtle p-2 rounded-circle">
                                                        <i class="{{ $menu['icon'] }} fa-2x text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="col-10">
                                                    <div class="card-body text-start">
                                                        <h5 class="card-title text-primary mb-0">{{ $menu['title'] }}</h5>
                                                        <p class="card-text text-muted fw-bold">
                                                            <small>{{ $menu['description'] }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
