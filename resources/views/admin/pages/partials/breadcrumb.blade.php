@php
    $isLanguage = Route::is('admin.course-language.*');
    $isLevel = Route::is('admin.course-level.*');
    $isCategory = Route::is('admin.course-category.*');
    $isCourses = Route::is('admin.courses.*');

    if ($isLanguage) {
        $pageTitle = 'Course Language';
        $pageButton = [
            'text' => 'Add New Language',
            'target' => '#newCatgory',
        ];
        $indexRoute = route('admin.course-language.index');
    } elseif ($isLevel) {
        $pageTitle = 'Course Level';
        $pageButton = [
            'text' => 'Add New Level',
            'target' => '#newLevel',
        ];
        $indexRoute = route('admin.course-level.index');
    } elseif ($isCategory) {
        $pageTitle = 'Course Category';
        $pageButton = [
            'text' => 'Add New Category',
            'target' => '#newCategory',
        ];
        $indexRoute = route('admin.course-category.index');
    } elseif ($isCourses) {
        $pageTitle = 'Courses';
        $pageButton = null;
        $indexRoute = route('admin.courses.index');
    } else {
        $pageTitle = '';
        $pageButton = null;
        $indexRoute = null;
    }
@endphp

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
            <div class="mb-3 mb-md-0">
                <h1 class="mb-1 h2 fw-bold">
                    {{ $pageTitle }}
                </h1>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        @if ($indexRoute)
                            <li class="breadcrumb-item active">
                                <a href="{{ $indexRoute }}">
                                    Course Management
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $pageTitle }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                @if ($pageButton)
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="{{ $pageButton['target'] }}">
                        {{ $pageButton['text'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
