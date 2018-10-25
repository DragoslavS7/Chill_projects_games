@extends('layouts.dashboard')
@section('page-title', 'View team members')
@section('dashboard-title', 'View team members')

@section('dashboard-actions')
    <a href="{{ route('client-portal.team-members.edit',$teamMember->id) }}">
        <button title="Edit team members" class="btn btn-default pull-right  mr-10">Edit team members</button>
    </a>
@endsection

@include('client-portal.team-members.partials.info')
