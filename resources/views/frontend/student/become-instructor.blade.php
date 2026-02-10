@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="pt-5 pb-5">
        <div class="container">
            @include('frontend.student.partials.user-info')
            <!-- Content -->
            <div class="row mt-0 mt-md-4">
                <div class="col-lg-3 col-md-4 col-12">
                    @include('frontend.student.partials.side-navbar')
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card">
                        @include('frontend.student.partials.card-header')
                        <!-- Card body -->
                        <div class="card-body">
                            <form class="row needs-validation" novalidate="" method="POST"
                                action="{{ route('student.become.instructor.submit', auth()->user()->id) }} "
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 col-lg-6 col-md-12 col-12">
                                    <label class="form-label" for="document">Resume</label>
                                    <input type="file" id="document" name="document" class="form-control"
                                        placeholder="Upload your resume" required="">
                                    <div class="invalid-feedback">Please upload your resume.</div>
                                    <x-input-error :messages="$errors->get('document')" class="mt-2 text-danger" />
                                    <button class="btn btn-primary mt-3" type="submit">Submit Application</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
