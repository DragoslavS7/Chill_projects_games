@extends('layouts.dashboard')
@section('page-title', 'Edit Quiz')
@section('dashboard-title', 'Edit Quiz')

@section('dashboard-actions')
    <button title="Update" id='update' class="btn btn-default pull-right">Update</button>
    <a href="{{ route('client-portal.quizzes.create') }}">
        <button title="New Quiz" class="btn btn-default pull-right  mr-10">New Quiz</button>
    </a>
@endsection

@include('client-portal.quizzes.partials.form')