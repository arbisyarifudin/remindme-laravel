<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Event Reminder</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body#remindme {
      margin: 0 !important;
      padding: 0 !important;
      background-color: #003049 !important;
    }

    #remindme-wrapper {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      background-color: #003049 !important;
      padding: 30px 20px;
    }

    #remindme-wrapper .container {
      max-width: 600px;
      margin: 30px auto 0;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;
    }

    #remindme-wrapper .header {
      text-align: center;
      margin-bottom: 30px;
    }

    #remindme-wrapper h1 {
      color: #003049;
      font-size: 15px;
      margin: 0;
      border: 2px dashed #003049;
      display: inline-block;
      padding: 5px 10px;
      margin-bottom: 20px;
    }

    #remindme-wrapper p {
      color: #003049;
      font-size: 16px;
      margin: 0;
    }

    #remindme-wrapper .reminder-details {
      margin-bottom: 30px;
      padding: 20px;
      border: 2px solid #003049;
      border-radius: 8px;
      backround-color: #f5f5f5;
    }

    #remindme-wrapper .reminder-title {
      color: #003049;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    #remindme-wrapper .reminder-description {
      color: #06486b;
      font-size: 15px;
      margin-bottom: 20px;
    }

    #remindme-wrapper .reminder-date {
      color: #06486b;
      font-size: 15px;
    }

    #remindme-wrapper .warning {
      text-align: center;
      color: #003049;
      font-size: 16px;
      font-weight: 500;
    }

    #remindme-wrapper .footer {
      text-align: center;
      color: #999;
      font-size: 12px;
      margin-top: 30px;
    }
  </style>
</head>

<body id="remindme">
  <div id="remindme-wrapper">
    <div class="container">
      <div class="header">
        {{-- <h1>🔔 Event Reminder</h1> --}}
        <p style="font-size: 20px;">Hi <strong>{{$userName}}</strong>!</p>
        <p>This is a friendly reminder about your event:</p>
      </div>
      <div class="reminder-details">
        <div class="reminder-title">📌 {{ $title }}</div>
        @if ($description)
        <p class="reminder-description">{{ $description }}</p>
        @endif
        <div class="reminder-date">
          📅 <strong>Date:</strong> {{ $eventDate }}, {{ $eventTime }}
        </div>

      </div>

      <div class="warning">
        <div>⚠ Dont't miss it!</div>
      </div>

      <div class="footer">
        <p>Sent by ⏲ RemindMe</p>
      </div>
    </div>
  </div>
</body>

</html>
