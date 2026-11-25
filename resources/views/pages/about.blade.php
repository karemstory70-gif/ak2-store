@extends('layouts.store')

@section('title', $page?->meta_title ?: $page?->title ?: $defaults['title'])
@section('description', $page?->meta_description ?: $defaults['description'])

@section('content')
    @include('pages.partials.storefront-content')
@endsection
