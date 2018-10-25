@extends('layouts.dashboard')
@section('page-title', 'Clients')
@section('dashboard-title', 'Create New Client')

@section('dashboard-actions')

    <button id="save" title="Save client portal" class="btn btn-default pull-right">Save</button>
@endsection

@include('admin-portal.client-portals.partials.form')