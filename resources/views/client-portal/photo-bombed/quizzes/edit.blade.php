@extends('layouts.dashboard')
@section('page-title', 'Edit Photobomb Quiz')
@section('dashboard-title', 'Edit Photobomb Quiz')

@section('dashboard-actions')
    <button title='Update' id='update' class="btn btn-default pull-right">Update</button>
    <a href="{{ route('client-portal.photo-bombed.quizzes.create') }}">
        <button title="New Photobomb Quiz" class="btn btn-default pull-right  mr-10">New Photobomb Quiz</button>
    </a>
@endsection

@include('client-portal.photo-bombed.quizzes.partials.form')