@extends('layouts.dashboard')
@section('page-title', 'View '.$gameTemplate->name)
@section('dashboard-title', $gameTemplate->name)

@section('dashboard-actions')
    <a href="{{ route('admin-portal.game-templates.edit',$gameTemplate->id) }}">
        <button title="Edit game template" class="btn btn-default pull-right  mr-10">Edit game template</button>
    </a>
@endsection

@include('admin-portal.game-templates.partials.info')