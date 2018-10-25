@extends('layouts.dashboard')
@section('page-title', 'Edit Photobomb Question')
@section('dashboard-title', 'Edit Photobomb Question')
@section('photo-bombed-image',$question->question_image)
@section('dashboard-actions')
    <button title="Update" id='update' class="btn btn-default pull-right">Update</button>
    <a href="{{ route('client-portal.photo-bombed.questions.create') }}">
        <button title="New Photobomb Question" class="btn btn-default pull-right  mr-10">New Photobomb Question</button>
    </a>
@endsection

@include('client-portal.photo-bombed.questions.partials.form')