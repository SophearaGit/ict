@extends('admin.layouts.master')
@section('page_title', isset($page_title) ? $page_title : 'Page Title Here')
@push('styles')
    <style>
        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: linear-gradient(180deg, #3b0fe0, #5b1ff2);
        }

        .main-content {
            flex: 1;
            padding: 20px 22px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #3b2fd1;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 18px;
        }

        .top-card,
        .info-card,
        .subject-section,
        .rating-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.03);
        }

        .top-card {
            display: flex;
            align-items: center;
            gap: 22px;
            padding: 20px;
            margin-bottom: 18px;
        }

        .profile-img {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
        }

        .profile-text h1 {
            font-size: 21px;
            margin-bottom: 8px;
            color: #111;
        }

        .tags {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .tag {
            background: #d9e1ff;
            color: #3554c7;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status {
            display: inline-block;
            background: green;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 999px;
            margin-bottom: 10px;
        }

        .profile-text p {
            color: #666;
            font-size: 14px;
            max-width: 760px;
            line-height: 1.6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .info-card {
            padding: 18px 16px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #222;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #ececec;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .icon-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .blue {
            background: #dce7ff;
            color: #3357d9;
        }

        .pink {
            background: #ffd9f0;
            color: #d94ba3;
        }

        .green {
            background: #dff7d7;
            color: #54b84d;
        }

        .orange {
            background: #ffe3d4;
            color: #e58a4e;
        }

        .info-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #222;
            font-weight: 600;
        }

        .subject-section {
            padding: 16px;
            margin-bottom: 18px;
        }

        .subject-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #222;
        }

        .subject-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .subject-card {
            border-radius: 14px;
            padding: 14px;
            background: #fff;
            border: 2px solid #f1f1f1;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .subject-card.red {
            border-bottom: 4px solid #f1b7b7;
        }

        .subject-card.blue {
            border-bottom: 4px solid #b9c8ff;
        }

        .subject-card.green {
            border-bottom: 4px solid #bceeb7;
        }

        .subject-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .subject-badge {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            background: #d9e1ff;
            color: #3554c7;
        }

        .progress-circle {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #666;
            font-weight: bold;
            background: conic-gradient(#f25d5d 0 80%, #f2dede 70% 100%);
            position: relative;
        }

        .progress-circle.green-circle {
            background: conic-gradient(#7deb63 0 100%, #dff7d7 100% 100%);
        }

        .progress-circle::before {
            content: "";
            position: absolute;
            width: 42px;
            height: 42px;
            background: #fff;
            border-radius: 50%;
        }

        .progress-circle span {
            font-size: 9px;
            position: relative;
            z-index: 1;
        }

        .subject-details p {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }

        .subject-details b {
            color: #222;
        }

        .rating-card {
            padding: 16px;
        }

        .rating-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 14px;
            color: #222;
        }

        .rating-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stars {
            color: #f6c62d;
            font-size: 18px;
            letter-spacing: 2px;
            white-space: nowrap;
        }

        .rating-number {
            font-size: 26px;
            font-weight: 700;
            color: #333;
            min-width: 42px;
        }

        .rating-bar {
            flex: 1;
            height: 18px;
            background: #e5e5e5;
            border-radius: 999px;
            overflow: hidden;
        }

        .rating-fill {
            width: 78%;
            height: 100%;
            background: #f1c232;
            border-radius: 999px;
        }

        .rating-score {
            font-size: 16px;
            font-weight: 700;
            color: #222;
            white-space: nowrap;
        }

        @media (max-width: 992px) {
            .subject-cards {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .top-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .sidebar {
                display: none;
            }
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">

            <div class="main-content">
                {{-- back --}}
                <a href="{{ route('admin.instructor.index') }}" class="back-link">Back to Teacher</a>

                <div class="top-card">
                    <img class="profile-img"
                        src="{{ $instructor->image == 'no-img.jpg' ? asset('\default-images\user\both.jpg') : asset($instructor->image) }}"
                        alt="Teacher" />

                    <div class="profile-text">
                        <h1>
                            @if ($instructor->gender == 'male')
                                Mr. {{ $instructor->name }}
                            @else
                                Mrs. {{ $instructor->name }}
                            @endif
                        </h1>

                        @if ($instructor->courses->isNotEmpty())
                            <div class="tags">
                                @foreach ($instructor->courses->unique('title') as $course)
                                    <span class="tag">
                                        {{ $course->title }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        @if ($instructor->status == 'active')
                            <div class="status">Active</div>
                        @elseif($instructor->status == 'on_leave')
                            <div class="status" style="background: #f39c12">On Leave</div>
                        @endif

                        <p>
                            {{ $instructor->bio ?? 'No biography available for this instructor.' }}
                        </p>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="card-title">Contact Information</div>

                        <div class="info-item">
                            <div class="icon-box blue">✉</div>
                            <div>
                                <div class="info-label">Email</div>
                                <div class="info-value">
                                    {{ $instructor->email }}
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box pink">📍</div>
                            <div>
                                <div class="info-label">Location</div>
                                <div class="info-value">
                                    {{ $instructor->location ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box green">📞</div>
                            <div>
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">
                                    {{ $instructor->phone ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="card-title">Teacher Details</div>

                        <div class="info-item">
                            <div class="icon-box orange">🪪</div>
                            <div>
                                <div class="info-label">Teacher ID</div>
                                <div class="info-value">
                                    {{ $instructor->teacher_id ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box green">👤</div>
                            <div>
                                <div class="info-label">Gender</div>
                                @if ($instructor->gender == 'male')
                                    <div class="info-value">Male</div>
                                @else
                                    <div class="info-value">Female</div>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box blue">🎓</div>
                            <div>
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value">
                                    {{ $instructor->dob ? \Carbon\Carbon::parse($instructor->dob)->format('d M, Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon-box pink">📄</div>
                            <div>
                                <div class="info-label">Degree / Qualification</div>
                                <div class="info-value">Bachelor of Computer science and engineering</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="subject-section">
                    <div class="subject-title">Subject Taught</div>

                    <div class="subject-cards">
                        @forelse ($instructor->courses as $course)
                            <div class="subject-card red">
                                <div class="subject-top">
                                    <div class="subject-badge">
                                        {{ $course->title }}
                                    </div>
                                    @php
                                        $ath = $athByCourse[$course->id]->ath ?? 0;
                                        $duration = (float) $course->duration ?? 0;

                                        // prevent overflow (ATH > duration)
                                        $ath = min($ath, $duration);

                                        $percent = $duration > 0 ? ($ath / $duration) * 100 : 0;
                                        $percent = round($percent);
                                    @endphp

                                    <div class="progress-circle
                                        {{ $percent >= 100 ? 'green-circle' : '' }}"
                                        style="background: conic-gradient(
                                            {{ $percent >= 100 ? '#7deb63' : '#f25d5d' }} 0 {{ $percent }}%,
                                            #f2dede {{ $percent }}% 100%
                                        );">
                                        <span>
                                            @php
                                                $format = function ($num) {
                                                    return rtrim(rtrim(number_format($num, 2), '0'), '.');
                                                };
                                            @endphp

                                            <span>
                                                {{ $format($ath) }}/{{ $format($duration) }} hr
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="subject-details">
                                    {{-- <p><b>Room :</b> A</p> --}}
                                    <p><b>Class Start :</b>
                                        {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d M, Y') : 'N/A' }}
                                    </p>
                                    {{-- <p><b>Class End :</b> 10/April/2026</p> --}}
                                    {{-- <p><b>Day :</b> Mon-Wed-Fri</p>
                                    <p><b>Time :</b> 18:00-20:00 PM</p> --}}
                                    <p><b>Duration :</b>
                                        {{ $course->duration ? $format($course->duration) . ' hr' : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p style="color: #555; font-size: 14px;">No subjects assigned to this instructor.</p>
                        @endforelse

                        {{-- <div class="subject-card blue">
                            <div class="subject-top">
                                <div class="subject-badge">Python</div>
                                <div class="progress-circle green-circle">
                                    <span>✓</span>
                                </div>
                            </div>

                            <div class="subject-details">
                                <p><b>Room :</b> C</p>
                                <p><b>Class Start :</b> 15/jan/2026</p>
                                <p><b>Class End :</b> 15/April/2026</p>
                                <p><b>Day :</b> Tue-Thu</p>
                                <p><b>Time :</b> 18:00-20:00 PM</p>
                                <p><b>Duration :</b> 48hr</p>
                            </div>
                        </div>

                        <div class="subject-card green">
                            <div class="subject-top">
                                <div class="subject-badge">C#</div>
                                <div class="progress-circle">
                                    <span>18/48 hr</span>
                                </div>
                            </div>

                            <div class="subject-details">
                                <p><b>Room :</b> B</p>
                                <p><b>Class Start :</b> 20/jan/2026</p>
                                <p><b>Class End :</b> 15/April/2026</p>
                                <p><b>Day :</b> Sunday</p>
                                <p><b>Time :</b> 13:30-16:30 PM</p>
                                <p><b>Duration :</b> 48hr</p>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="rating-card">
                    <div class="rating-title">Performance Rating</div>

                    <div class="rating-row">
                        <div class="stars">★★★★★</div>
                        <div class="rating-number">3.5</div>
                        <div class="rating-bar">
                            <div class="rating-fill"></div>
                        </div>
                        <div class="rating-score">3.5/5.0</div>
                    </div>
                </div>
            </div>



        </div>
    </div>

@endsection
@push('scripts')
@endpush
