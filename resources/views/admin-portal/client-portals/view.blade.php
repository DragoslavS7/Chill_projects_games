@extends('layouts.dashboard')
@section('page-title', 'View '.$clientPortal->company_name)
@section('dashboard-title', $clientPortal->company_name)

@section('dashboard-actions')
    <a href="{{ route('admin-portal.client-portals.edit', $clientPortal->id ) }}">
        <button id='update' title="Edit client" class="btn btn-default pull-right">Edit Client</button>
    </a>
@endsection

@include('admin-portal.client-portals.partials.info')