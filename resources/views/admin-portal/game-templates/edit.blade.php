@extends('layouts.dashboard')
@section('page-title', 'Edit Game Template')
@section('dashboard-title', 'Edit Game Template')

@section('dashboard-actions')
    <button id='update' title='Update' class="btn btn-default pull-right">Update</button>
    <a href="{{ route('admin-portal.game-templates.create') }}">
        <button title="New game template" class="btn btn-default pull-right  mr-10">New game template</button>
    </a>
@endsection

@include('admin-portal.game-templates.partials.form')