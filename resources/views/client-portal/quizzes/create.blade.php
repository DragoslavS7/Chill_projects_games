@extends('layouts.dashboard')
@section('page-title', 'New Quiz')
@section('dashboard-title', 'New Quiz')

@section('dashboard-actions')
    <button title="Save" id="save" class="btn btn-default pull-right">Save</button>
@endsection

@include('client-portal.quizzes.partials.form')
