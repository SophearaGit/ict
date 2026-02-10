<!DOCTYPE html>
<html lang="en" style="margin:0; padding:0;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Instructor Approval</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            color: #333333;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            color: #999999;
            font-size: 12px;
            margin-top: 20px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <div style="padding: 20px;">
        <div class="container">
            <h1>You're Approved, {{ $instructor_detail->name }}!</h1>
            <p>Congratulations! Your instructor registration with <strong>ICT Professional Training Center</strong> has been
                reviewed and approved by our admin team.</p>

            <p>Here are your registration details:</p>

            <ul>
                <li><strong>Name:</strong> {{ $instructor_detail->name }}</li>
                <li><strong>Email:</strong> {{ $instructor_detail->email }}</li>
                {{-- <li><strong>Username:</strong> [Username]</li> --}}
                <li><strong>Role:</strong> {{ $instructor_detail->role }}</li>
            </ul>

            <p>You can now log in, set up your instructor profile, and start creating amazing content for your students.
            </p>

            <a href="{{ url('/instructor/dashboard') }}" class="button">Get Started</a>

            <p style="margin-top: 30px;">If you have any questions or need help, feel free to reply to this email or
                contact our support team.</p>

            <div class="footer">
                &copy; {{ date('Y') }} ICT Professional Training Center. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>
