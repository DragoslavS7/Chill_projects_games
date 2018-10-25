@extends('layouts.dashboard')
@section('page-title', 'Edit Team Member')
@section('dashboard-title', 'Edit Team Member')

@section('dashboard-actions')
    <button title="Update" id='update' class="btn btn-default pull-right">Update</button>
    <a href="{{ route('client-portal.team-members.index') }}">
        <button title="Cancel" class="btn btn-default pull-right  mr-10">Cancel</button>
    </a>
@endsection

@include('client-portal.team-members.partials.form')