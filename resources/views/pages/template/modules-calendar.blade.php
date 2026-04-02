@extends('layouts.app')

@section('title', 'Calendar')

@push('style')
    <!-- CSS Libraries -->
<<<<<<< HEAD:resources/views/pages/template/modules-calendar.blade.php
    <link rel="stylesheet" href="{{ asset('assets/library/fullcalendar/dist/fullcalendar.min.css') }}">
=======
    <link rel="stylesheet"
        href="{{ asset('library/fullcalendar/dist/fullcalendar.min.css') }}">
>>>>>>> 524fbaa5e5e1e1a8ae05a0ba09c0756db7937190:resources/views/pages/modules-calendar.blade.php
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Calendar</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Modules</a></div>
                    <div class="breadcrumb-item">Calendar</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Calendar</h2>
                <p class="section-lead">
                    We use 'Full Calendar' made by @fullcalendar. You can check the full documentation <a
                        href="https://fullcalendar.io/">here</a>.
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Calendar</h4>
                            </div>
                            <div class="card-body">
                                <div class="fc-overflow">
                                    <div id="myEvent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
<<<<<<< HEAD:resources/views/pages/template/modules-calendar.blade.php
    <script src="{{ asset('assets/library/fullcalendar/dist/fullcalendar.min.js') }}"></script>
=======
    <script src="{{ asset('library/fullcalendar/dist/fullcalendar.min.js') }}"></script>
>>>>>>> 524fbaa5e5e1e1a8ae05a0ba09c0756db7937190:resources/views/pages/modules-calendar.blade.php

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-calendar.js') }}"></script>
@endpush
