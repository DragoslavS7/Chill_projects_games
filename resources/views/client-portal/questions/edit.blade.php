@extends('layouts.dashboard')
@section('page-title', 'Edit Question')
@section('dashboard-title', 'Edit Question')

@section('dashboard-actions')
    <button title="Update" id='update' class="btn btn-default pull-right">Update</button>
    <a href="{{ route('client-portal.questions.create') }}">
        <button class="btn btn-default pull-right  mr-10">New Question</button>
    </a>
@endsection

@include('client-portal.questions.partials.form')