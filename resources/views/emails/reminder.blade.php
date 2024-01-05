<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Reminder</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #003049;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto 0;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #003049;
            font-size: 15px;
            margin: 0;
            border: 2px dashed #003049;
            display: inline-block;
            padding: 5px 10px;
            margin-bottom: 20px;
        }
        p {
            color: #003049;
            font-size: 16px;
            margin: 0;
        }
        .task-details {
            margin-bottom: 30px;
            padding: 20px;
            border: 2px solid #003049;
            border-radius: 8px;
            backround-color: #f5f5f5;
        }
        .task-title {
            color: #003049;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .task-description {
            color: #06486b;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .task-date {
            color: #06486b;
            font-size: 15px;
        }

        .footer {
            text-align: center;
            color: #999;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- <h1>🔔 Event Reminder</h1> --}}
            <p style="font-size: 20px;">Hi <strong>{{$userName}}</strong>!</p>
            <p>This is a friendly reminder about your task:</p>
        </div>
        <div class="task-details">
            <div class="task-title">📌 {{ $title }}</div>
            <p class="task-description">{{ $description }}</p>
            <div class="task-date">
                📅 <strong>Date:</strong> {{ $eventDate }}, {{ $eventTime }}
            </div>

        </div>
        <p style="text-align: center">Remember to complete it on time!</p>
        <div class="footer">
            <p>Sent by  ⏲ RemindMe</p>
        </div>
    </div>
</body>
</html>
