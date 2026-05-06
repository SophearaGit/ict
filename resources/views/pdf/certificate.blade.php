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
            color: #1F3864;
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

        /* ================= BASE ================= */

        .text {
            position: fixed;
            font-size: 12pt;
            color: #1F3864;
        }

        .text-special {
            position: fixed;
            font-size: 12pt;
            color: black;
        }

        .center {
            text-align: center;
        }

        .en {
            font-family: 'Times New Roman', Times, serif;
        }

        .bold {
            font-weight: bold;
        }

        /* ================= KHMER ================= */

        .khmer-name {
            top: 82mm;
            left: 36mm;
            width: 110mm;
            color: black;
        }

        .khmer-dob {
            top: 92mm;
            left: 48mm;
            width: 96mm;
        }

        .khmer-gender {
            top: 102mm;
            left: 26mm;
            width: 31mm;
        }

        .khmer-nationality {
            top: 102mm;
            left: 73mm;
            width: 72mm;
        }

        .khmer-course {
            top: 132mm;
            left: 30mm;
            width: 112mm;
        }

        .khmer-duration {
            top: 143mm;
            left: 45mm;
            width: 99mm;
        }

        .khmer-issue-day {
            top: 163mm;
            left: 60mm;
            width: 10mm;
        }

        .khmer-issue-month {
            top: 163mm;
            left: 72mm;
            width: 20mm;
        }

        .khmer-issue-year {
            top: 163mm;
            left: 94mm;
            width: 15mm;
        }

        /* ================= ENGLISH ================= */

        .name {
            top: 82mm;
            left: 178mm;
            width: 101mm;
            color: black;
            text-transform: capitalize;
        }

        .dob {
            top: 91mm;
            left: 179mm;
            width: 100mm;
        }

        .gender {
            top: 100mm;
            left: 163mm;
            width: 33mm;
        }

        .nationality {
            top: 100mm;
            left: 217mm;
            width: 62mm;
        }

        .course {
            top: 128mm;
            left: 170mm;
            width: 105mm;
        }

        .duration {
            top: 136mm;
            left: 172mm;
            width: 105mm;
        }

        .issue-date {
            top: 155mm;
            left: 190mm;
            width: 32mm;
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
         * Khmer month name
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
         * Khmer full date
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
            return toKhmerDigits(\Carbon\Carbon::parse($date)->day);
        }

        function formatKhmerMonth($date): string
        {
            return khmerMonth(\Carbon\Carbon::parse($date)->month);
        }

        function formatKhmerYear($date): string
        {
            return toKhmerDigits(\Carbon\Carbon::parse($date)->year);
        }

        /**
         * Khmer gender
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
         * Khmer nationality
         */
        function khmerNationality(?string $nationality): string
        {
            return match (strtolower($nationality ?? '')) {
                'khmer', 'cambodian' => 'ខ្មែរ',
                default => $nationality ?? '-',
            };
        }

        /**
         * Khmer duration
         */
        function formatDurationKh(int|float $hours): string
        {
            return toKhmerDigits((int) $hours) . ' ម៉ោង';
        }

        $issueDate = now();
        $issueDateEn = $issueDate->format('d F Y');

        $issueDayKh = formatKhmerDay($issueDate);
    @endphp

    @foreach ($students as $student)
        <img class="bg" src="{{ public_path('certificates/bg.jpg') }}" />

        <!-- ================= KHMER ================= -->

        <div class="text-special khmer-name center">
            <strong>{{ $student->khmer_name ?? $student->name }}</strong>
        </div>

        <div class="text khmer-dob center">
            {{ $student->dob ? formatKhmerDate($student->dob) : '-' }}
        </div>

        <div class="text khmer-gender center">
            {{ khmerGender($student->gender) }}
        </div>

        <div class="text khmer-nationality center">
            {{ khmerNationality($student->nationality) }}
        </div>

        <div class="text khmer-course center en bold">
            {{ $course->title ?? 'Course Name' }}
        </div>

        <div class="text khmer-duration center">
            {{ formatDurationKh($course->duration ?? 0) }}
        </div>

        <div class="text khmer-issue-day center">
            {{ $issueDayKh }}
        </div>

        <div class="text khmer-issue-month center">
            {{ formatKhmerMonth($issueDate) }}
        </div>

        <div class="text khmer-issue-year center">
            {{ formatKhmerYear($issueDate) }}
        </div>

        <!-- ================= ENGLISH ================= -->

        <div class="text-special name center en">
            <strong>{{ $student->name }}</strong>
        </div>

        <div class="text dob center en">
            {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d F Y') : '-' }}
        </div>

        <div class="text gender center en">
            {{ ucfirst(strtolower($student->gender ?? '-')) }}
        </div>

        <div class="text nationality center en">
            {{ ucfirst(strtolower($student->nationality ?? '-')) }}
        </div>

        <div class="text course center en bold">
            {{ $course->title ?? 'Course Name' }}
        </div>

        <div class="text duration center en">
            {{ number_format($course->duration ?? 0) }}h
        </div>

        <div class="text issue-date center en">
            {{ $issueDateEn }}
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
