@extends('layouts.app')

@section('content')
    @include('dashboard.sections.' . $page)
@endsection