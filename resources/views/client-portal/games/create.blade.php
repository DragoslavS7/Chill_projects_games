@extends('layouts.dashboard')
@section('page-title', 'Create New Game')
@section('dashboard-title', 'Create New Game')

@section('dashboard-actions')
    <button id="save" title="Save" class="btn btn-default pull-right">Save</button>
@endsection

@include('client-portal.games.partials.form')