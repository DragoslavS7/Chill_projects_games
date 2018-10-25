@extends('layouts.dashboard')
@section('page-title', 'New Team Member')
@section('dashboard-title', 'New Team Member')

@section('dashboard-actions')
    <button title='Save' id="save" class="btn btn-default pull-right">Save</button>
@endsection

@include('admin-portal.team-members.partials.form')