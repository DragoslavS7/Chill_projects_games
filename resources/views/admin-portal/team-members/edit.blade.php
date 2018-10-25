@extends('layouts.dashboard')
@section('page-title', 'Edit Team Member')
@section('dashboard-title', 'Edit Team Member')

@section('dashboard-actions')
    <button id='update' title="Update" class="btn btn-default pull-right">Update</button>
    <a href="{{ route('admin-portal.team-members.index') }}">
        <button title="Cancel" class="btn btn-default pull-right  mr-10">Cancel</button>
    </a>
@endsection

@include('admin-portal.team-members.partials.form')