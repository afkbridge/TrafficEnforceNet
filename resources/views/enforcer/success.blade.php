<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Ticket Submitted | TrafficEnforceNet</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            width: 100%;
            max-width: 430px;
            background: white;
            border-radius: 20px;
            padding: 40px 28px;
            text-align: center;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.10);
        }

        .logo {
            width: 85px;
            height: 85px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .success-icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #e8f7ee;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            font-weight: bold;
        }

        h1 {
            margin: 0 0 10px;
            color: #123b69;
            font-size: 28px;
        }

        .message {
            margin: 0 auto 28px;
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
        }

        .ticket-box {
            background: #f4f8fc;
            border: 1px solid #dbe5ef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .ticket-label {
            display: block;
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ticket-number {
            color: #123b69;
            font-size: 20px;
            font-weight: bold;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn-primary {
            background: #123b69;
            color: white;
            margin-bottom: 10px;
        }

        .btn-primary:hover {
            background: #0d2d50;
        }

        .btn-secondary {
            background: #eef3f8;
            color: #123b69;
        }

        .btn-secondary:hover {
            background: #e2eaf2;
        }

        @media (max-width: 480px) {
            .success-container {
                padding: 32px 22px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>


</head>

<body>


    <div class="success-container">

        <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo" class="logo">

        <div class="success-icon">
            ✓
        </div>

        <h1>Ticket Submitted!</h1>

        <p class="message">
            The traffic violation ticket has been successfully recorded
            in the TrafficEnforceNet system.
        </p>

        @if (isset($violation) && $violation)
            <div class="ticket-box">
                <span class="ticket-label">Ticket Number</span>
                <span class="ticket-number">
                    {{ $violation->ticket_number }}
                </span>
            </div>
        @endif

        <a href="/enforcer/issue-ticket" class="btn btn-primary">
            Issue Another Ticket
        </a>

        <a href="/dashboard" class="btn btn-secondary">
            Return to Home
        </a>
    </div>


</body>

</html>
