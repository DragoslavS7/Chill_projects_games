@extends('layouts.dashboard')
@section('page-title', 'Admin Analytics')
@section('dashboard-title', 'Admin Analytics')

@section('dashboard-main-content')
    <div class="col-xs-12 bg-white shadow wraper-background">
        <div class="row pt-30 ml-150">
            <div class="col-xs-4 pt-30">
                @include('client-portal.admin-analytics.partials.total-players-counter')
            </div>

            <div class="col-xs-4 pt-30">
                @include('client-portal.admin-analytics.partials.average-game-duration-counter')
            </div>

            <div class="col-xs-4 pt-30">
                @include('client-portal.admin-analytics.partials.average-game-score-counter')
            </div>
        </div>

        <div class="row pt-30 pb-30">
            @include('client-portal.admin-analytics.partials.quizzes-donut-counter-combined')
        </div>

        <div class="row pt-30 pb-30">
            @include('client-portal.admin-analytics.partials.games-bar-chart')
        </div>

        <div class="row pt-30 pb-30">
            @include('client-portal.admin-analytics.partials.admin-manager')
        </div>

        <div class="row pt-30 pb-30">
            @include('client-portal.admin-analytics.partials.quizzes_donut')
        </div>
    </div>
@endsection
