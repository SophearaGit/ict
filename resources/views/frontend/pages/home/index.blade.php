@extends('frontend.layouts.master')
@section('content')
    @include('frontend.pages.home.sections.hero')
    @include('frontend.pages.home.sections.counter')
    @include('frontend.pages.home.sections.course')
    @include('frontend.pages.home.sections.instructor-request')
    @include('frontend.pages.home.sections.review')
@endsection
