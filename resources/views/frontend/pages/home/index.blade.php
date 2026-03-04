@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    @include('frontend.pages.home.sections.hero')
    @include('frontend.pages.home.sections.counter')
    @include('frontend.pages.home.sections.course')
    @include('frontend.pages.home.sections.instructor-request')
    @include('frontend.pages.home.sections.review')
@endsection
