@extends('layouts.dashboard')
@section('page-title', 'New Photobomb Quiz')
@section('dashboard-title', 'New Photobomb Quiz')

@section('dashboard-actions')
    <button title="Save" id="save" class="btn btn-default pull-right">Save</button>
@endsection

@include('client-portal.photo-bombed.quizzes.partials.form')