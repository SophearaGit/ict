@extends('frontend.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@section('content')
    <section class="pt-5 pb-5">
        <div class="container">
            @include('frontend.instructor.partials.user-info')
            <!-- Content -->
            @php
                $user = auth()->user();
            @endphp
            <div class="row mt-0 mt-md-4">
                <div class="col-lg-3 col-md-4 col-12">
                    <!-- User profile -->
                    @include('frontend.instructor.partials.side-navbar')
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        @include('frontend.instructor.partials.card-header')
                        <!-- Card body -->
                        <div class="card-body">
                            <form action="{{ route('instructor.social.profile.update') }}" method="POST">
                                @csrf
                                <!-- Facebook -->
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>Facebook</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1"
                                            placeholder="Add your Facebook username (e.g. johnsmith)." name="facebook"
                                            value="{{ $user->facebook ?? '' }}">
                                        <x-input-error :messages="$errors->get('facebook')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <!-- Twitter -->
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>Twitter</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1"
                                            placeholder="Add your Twitter username (e.g. johnsmith)." name="x"
                                            value="{{ $user->x ?? '' }}">
                                        <x-input-error :messages="$errors->get('x')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <!-- Linked in -->
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>LinkedIn Profile URL</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1"
                                            placeholder="Add your LinkedIn profile URL (e.g. https://www.linkedin.com/in/johnsmith)."
                                            name="linkedin" value="{{ $user->linkedin ?? '' }}">
                                        <x-input-error :messages="$errors->get('linkedin')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                {{-- website --}}
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>Website URL</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1" placeholder="Add your website URL"
                                            name="website" value="{{ $user->website ?? '' }}">
                                        <x-input-error :messages="$errors->get('website')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                {{-- github --}}
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>GitHub Profile URL</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1"
                                            placeholder="Add your GitHub profile URL" name="github"
                                            value="{{ $user->github ?? '' }}">
                                        <x-input-error :messages="$errors->get('github')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <!-- Instagram -->
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>Instagram</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1" placeholder="Instagram Profile Name"
                                            name="instagram" value="{{ $user->instagram ?? '' }}">
                                        <x-input-error :messages="$errors->get('instagram')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <!-- Youtube -->
                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <h5>YouTube</h5>
                                    </div>
                                    <div class="col-lg-9 col-md-8 col-12">
                                        <input type="text" class="form-control mb-1" placeholder="YouTube Channel Name"
                                            name="youtube" value="{{ $user->youtube ?? '' }}">
                                        <x-input-error :messages="$errors->get('youtube')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <!-- Button -->
                                <div class="row">
                                    <div class="offset-lg-3 col-lg-6 col-12">
                                        <button type="submit" class="btn btn-primary">Save Social Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
