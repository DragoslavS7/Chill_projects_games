@extends('auth.not-authorized')
@section('errorMessage', $exception->getMessage())