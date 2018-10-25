@extends('layouts.dashboard')
@section('page-title', 'New Photobomb Question')
@section('dashboard-title', 'New Photobomb Question')
@section('photo-bombed-image',$question->question_video ? $question->question_video :'/images/app_icon.png')

@section('dashboard-actions')
    <button title="Save" id="save" class="btn btn-default pull-right">Save</button>
@endsection

@include('client-portal.photo-bombed.questions.partials.form')