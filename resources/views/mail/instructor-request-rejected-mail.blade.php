<!DOCTYPE html>
<html lang="en" style="margin:0; padding:0;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Instructor Application Update</title>
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
            color: #d9534f;
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
            <h1>Hi {{ $instructor_detail->name }},</h1>
            <p>Thank you for your interest in becoming an instructor at <strong>ICT Professional Training Center</strong>.</p>

            <p>After careful consideration, we regret to inform you that your instructor registration has been
                <strong>rejected</strong> at this time.
            </p>

            <p>Here’s a summary of your submitted information:</p>
            <ul>
                <li><strong>Name:</strong> {{ $instructor_detail->name }}</li>
                <li><strong>Email:</strong> {{ $instructor_detail->email }}</li>
                <li><strong>Role Attempted:</strong> Instructor</li>
            </ul>

            <p>If you believe this was a mistake or would like to reapply with additional information, please don’t
                hesitate to contact our support team.</p>

            <p>We appreciate your effort and encourage you to stay engaged with ICT Professional Training Center for future opportunities.</p>

            <div class="footer">
                &copy; {{ date('Y') }} ICT Professional Training Center. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>
