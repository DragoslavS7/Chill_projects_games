@extends('layouts.dashboard')
@section('page-title', 'Edit Game')
@section('dashboard-title', 'Edit Game')

@section('dashboard-actions')
    <button id='update' title="Update" class="btn btn-default pull-right">Update</button>
    <a href="{{ route('client-portal.games.templates',$gameTemplateId) }}">
        <button title="New Game" class="btn btn-default pull-right  mr-10">New Game</button>
    </a>
@endsection

@include('client-portal.games.partials.form')