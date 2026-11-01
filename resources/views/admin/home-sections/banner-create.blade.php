@extends('layouts.light.master')
@section('title', 'Create Banner Section')
@section('breadcrumb-title')<h3>Create Banner Section</h3>@endsection
@section('breadcrumb-items')<li class="breadcrumb-item">Create Banner Section</li>@endsection
@section('content')
    <div class="row justify-content-center mb-5"><div class="col-xl-10">
        <x-form :action="route('admin.home-sections.store', ['banner' => true])" method="POST" has-files class="card shadow-sm">
            @include('admin.home-sections.banner-form')
        </x-form>
    </div></div>
@endsection
