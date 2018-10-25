@extends('layouts.dashboard')
@section('page-title', 'New Game Template')
@section('dashboard-title', 'New Game Template')

@section('dashboard-actions')
    <button id='save' title="Save game template" class="btn btn-default pull-right">Save</button>
@endsection

@include('admin-portal.game-templates.partials.form')