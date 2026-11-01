@extends('layouts.light.master')
@section('title', 'Edit Banner Section')
@section('breadcrumb-title')<h3>Edit Banner Section</h3>@endsection
@section('breadcrumb-items')<li class="breadcrumb-item">Edit Banner Section</li>@endsection
@section('content')
    <div class="row justify-content-center mb-5"><div class="col-xl-10">
        <x-form :action="route('admin.home-sections.update', [$section, 'banner' => true])" method="PATCH" has-files class="card shadow-sm">
            @include('admin.home-sections.banner-form')
        </x-form>
    </div></div>
@endsection
