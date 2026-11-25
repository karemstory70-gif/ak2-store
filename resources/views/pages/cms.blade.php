@extends('layouts.store')

@section('title', $page->meta_title ?: $page->title)
@section('description', $page->meta_description ?: $page->title)

@section('content')
    @include('pages.partials.storefront-content')
@endsection
