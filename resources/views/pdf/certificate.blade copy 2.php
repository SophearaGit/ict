<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        @page {
            margin: 0;
            size: 842mm 595mm landscape;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Battambang", sans-serif;
        }

        .page-break {
            page-break-after: always;
        }

        .bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 842mm;
            height: 595mm;
            z-index: -1;
        }

        .text {
            position: fixed;
            font-size: 12pt;
        }

        /* ================= KHMER ================= */
        .khmer-name {
            top: 82mm;
            left: 36mm;
            width: 110mm;
            text-align: center;
        }

        .khmer-dob {
            /* background: red; */
            top: 92mm;
            width: 96mm;
            left: 48mm;
            text-align: center;
        }

        .khmer-gender {
            /* background: red; */
            width: 31mm;
            top: 102mm;
            left: 26mm;
            text-align: center
        }

        .khmer-nationality {
            /* background: red; */
            width: 72mm;
            top: 102mm;
            left: 73mm;
            text-align: center;
        }

        .khmer-course {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            top: 132mm;
            left: 30mm;
            width: 112mm;
            text-align: center;
        }

        .khmer-duration {
            width: 99mm;
            text-align: center;
            top: 143mm;
            left: 45mm;
        }

        .khmer-issue-day {
            width: 10mm;
            text-align: center;
            /* background: red; */
            top: 163mm;
            left: 60mm;
        }

        /* month */
        .khmer-issue-month {
            width: 20mm;
            text-align: center;
            /* background: red; */
            top: 163mm;
            left: 72mm;
        }

        /* year */
        .khmer-issue-year {
            width: 15mm;
            text-align: center;
            /* background: red; */
            top: 163mm;
            left: 94mm;
        }

        /* ================= ENGLISH ================= */
        .name {
            color: black;
            text-transform: capitalize;
            font-family: 'Times New Roman', Times, serif;
            top: 82mm;
            left: 178mm;
            width: 101mm;
            text-align: center;
        }

        .dob {
            /* background: red; */
            font-family: 'Times New Roman', Times, serif;
            top: 91mm;
            left: 179mm;
            width: 100mm;
            text-align: center;
        }

        .gender {
            /* background: red; */
            width: 33mm;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            top: 100mm;
            left: 163mm;
        }

        .nationality {
            /* background: red; */
            width: 62mm;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            top: 100mm;
            left: 217mm;
        }

        /* ================= COURSE ================= */
        .course {
            font-family: 'Times New Roman', Times, serif;
            /* background: red; */
            font-weight: bold;
            top: 128mm;
            left: 170mm;
            width: 105mm;
            text-align: center;
        }

        .duration {
            /* background: red; */
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            width: 105mm;
            top: 136mm;
            left: 172mm;
        }

        .issue-date {
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            width: 32mm;
            top: 155mm;
            left: 190mm;
        }
    </style>
</head>

<body>
    @php
        /**
         * Convert Arabic digits to Khmer digits
         */
        function toKhmerDigits(string|int $value): string
        {
            $arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $khmer = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
            return str_replace($arabic, $khmer, (string) $value);
        }

        /**
         * Return the Khmer month name for a given month number (1–12)
         */
        function khmerMonth(int $month): string
        {
            return [
                1 => 'មករា',
                2 => 'កុម្ភៈ',
                3 => 'មីនា',
                4 => 'មេសា',
                5 => 'ឧសភា',
                6 => 'មិថុនា',
                7 => 'កក្កដា',
                8 => 'សីហា',
                9 => 'កញ្ញា',
                10 => 'តុលា',
                11 => 'វិច្ឆិកា',
                12 => 'ធ្នូ',
            ][$month] ?? '';
        }

        /**
         * Format a date string (Y-m-d) or Carbon instance as a Khmer date:
         * ១៥-កុម្ភៈ-២០០៣
         */
        function formatKhmerDate($date): string
        {
            $dt = \Carbon\Carbon::parse($date);
            $day = toKhmerDigits($dt->day);
            $month = khmerMonth($dt->month);
            $year = toKhmerDigits($dt->year);
            return "{$day}-{$month}-{$year}";
        }

        function formatKhmerDay($date): string
        {
            $dt = \Carbon\Carbon::parse($date);
            return toKhmerDigits($dt->day);
        }
        function formatKhmerMonth($date): string
        {
            $dt = \Carbon\Carbon::parse($date);
            $month = khmerMonth($dt->month);
            return "{$month}";
        }
        function formatKhmerYear($date): string
        {
            $dt = \Carbon\Carbon::parse($date);
            $year = toKhmerDigits($dt->year);
            return "{$year}";
        }

        /**
         * Translate gender to Khmer
         */
        function khmerGender(?string $gender): string
        {
            return match (strtolower($gender ?? '')) {
                'male' => 'ប្រុស',
                'female' => 'ស្រី',
                default => '-',
            };
        }

        /**
         * Translate nationality to Khmer (extend as needed)
         */
        function khmerNationality(?string $nationality): string
        {
            return match (strtolower($nationality ?? '')) {
                'khmer', 'cambodian' => 'ខ្មែរ',
                default => $nationality ?? '-',
            };
        }

        /**
         * Format duration: e.g. 48 → "48h" (EN) / "៤៨ ម៉ោង" (KH)
         */
        function formatDurationKh(int|float $hours): string
        {
            return toKhmerDigits((int) $hours) . ' ម៉ោង';
        }

        $issueDate = now();
        $issueDateEn = $issueDate->format('d F Y'); // 06 May 2026

        // Khmer issue date broken into parts
        $issueDayKh = formatKhmerDay($issueDate);
    @endphp

    @foreach ($students as $student)
        <img class="bg" src="{{ public_path('certificates/bg.jpg') }}" />

        <!-- ===== KHMER SIDE ===== -->
        <div class="text khmer-name">
            <strong>{{ $student->khmer_name ?? $student->name }}</strong>
        </div>

        <div class="text khmer-dob">
            {{ $student->dob ? formatKhmerDate($student->dob) : '-' }}
        </div>

        <div class="text khmer-gender">
            {{ khmerGender($student->gender) }}
        </div>

        <div class="text khmer-nationality">
            {{ khmerNationality($student->nationality) }}
        </div>

        <div class="text khmer-course">
            {{ $course->title ?? 'Course Name' }}
        </div>

        <div class="text khmer-duration">
            {{ formatDurationKh($course->duration ?? 0) }}
        </div>

        <div class="text khmer-issue-day">
            {{ $issueDayKh }}
        </div>
        {{-- month --}}
        <div class="text khmer-issue-month">
            {{ formatKhmerMonth($issueDate) }}
        </div>
        {{-- year --}}
        <div class="text khmer-issue-year">
            {{ formatKhmerYear($issueDate) }}
        </div>

        <!-- ===== ENGLISH SIDE ===== -->
        <div class="text name">
            <strong>{{ $student->name }}</strong>
        </div>

        <div class="text dob">
            {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d F Y') : '-' }}
        </div>

        <div class="text gender">
            {{ ucfirst(strtolower($student->gender ?? '-')) }}
        </div>

        <div class="text nationality">
            {{ ucfirst(strtolower($student->nationality ?? '-')) }}
        </div>

        <div class="text course">
            {{ $course->title ?? 'Course Name' }}
        </div>

        <div class="text duration">
            {{ number_format($course->duration ?? 0) }}h
        </div>

        <div class="text issue-date">
            {{ $issueDateEn }}
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
