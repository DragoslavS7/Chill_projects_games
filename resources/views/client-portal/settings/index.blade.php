@extends('layouts.dashboard')
@section('page-title', 'Settings')
@section('dashboard-title', 'Settings')


@section('dashboard-actions')
    <button id="save" class="btn btn-default pull-right colored-button-font">Save</button>
@endsection

@include('client-portal.settings.partials.form')
