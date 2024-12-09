@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Followup Intern
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/followups/form.css') }}">
@endpush

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Followup Intern</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('followup-interns.update', $followupIntern->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('followup-intern.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
