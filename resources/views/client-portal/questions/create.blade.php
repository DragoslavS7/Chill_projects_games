@extends('layouts.dashboard')
@section('page-title', 'New Question')
@section('dashboard-title', 'New Question')

@section('dashboard-actions')
    <button title="Save" id="save" class="btn btn-default pull-right">Save</button>
@endsection

@include('client-portal.questions.partials.form')