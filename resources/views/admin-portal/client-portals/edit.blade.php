@extends('layouts.dashboard')
@section('page-title', 'Edit Client')
@section('dashboard-title', 'Edit Client')

@section('dashboard-actions')
    <button id='update' title="Update client portal" class="btn btn-default pull-right">Update</button>
    <a href="{{ route('admin-portal.client-portals.create') }}">
        <button title="Create new client" class="btn btn-default pull-right  mr-10">New Client</button>
    </a>
@endsection

@include('admin-portal.client-portals.partials.form')