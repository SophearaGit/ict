<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            margin: 0;
            font-family: notosanskhmer, sans-serif;
        }

        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
        }

        /* Background certificate */
        .bg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .text {
            position: absolute;
            z-index: 1;
            font-size: 14px;
        }

        /* English */
        .name-en {
            top: 95mm;
            left: 150mm;
        }

        .dob-en {
            top: 105mm;
            left: 150mm;
        }

        .gender-en {
            top: 115mm;
            left: 150mm;
        }

        .nation-en {
            top: 115mm;
            left: 190mm;
        }

        /* Khmer */
        .name-kh {
            top: 95mm;
            left: 55mm;
        }

        .dob-kh {
            top: 105mm;
            left: 55mm;
        }

        .gender-kh {
            top: 115mm;
            left: 55mm;
        }

        .nation-kh {
            top: 115mm;
            left: 95mm;
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- Background PDF as image -->
        <img class="bg" src="{{ public_path('default-images/user/both.jpg') }}">

        <!-- Khmer -->
        <div class="text name-kh">{{ $student->kh_name ?? $student->name }}</div>
        <div class="text dob-kh">{{ $student->dob ?? '-' }}</div>
        <div class="text gender-kh">{{ $student->gender ?? '-' }}</div>
        <div class="text nation-kh">{{ $student->nationality ?? '-' }}</div>

        <!-- English -->
        <div class="text name-en">{{ $student->name }}</div>
        <div class="text dob-en">{{ $student->dob ?? '-' }}</div>
        <div class="text gender-en">{{ $student->gender ?? '-' }}</div>
        <div class="text nation-en">{{ $student->nationality ?? '-' }}</div>

    </div>
</body>

</html>
