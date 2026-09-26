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

            /* Enforcer background */
            background: #F5F7FB;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        /* =====================================================
           SUCCESS CONTAINER
        ====================================================== */

        .success-container {
            width: 100%;
            max-width: 430px;

            background: #ffffff;

            border-radius: 24px;

            padding: 38px 28px 32px;

            text-align: center;

            box-shadow:
                0 12px 35px rgba(29, 95, 191, 0.12);

            border: 1px solid #E7EEF8;

            position: relative;
            overflow: hidden;
        }

        /* =====================================================
           TOP BLUE ACCENT
        ====================================================== */

        .success-container::before {
            content: "";
            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 6px;

            background: linear-gradient(135deg,
                    #1D5FBF,
                    #3B82F6);
        }

        /* =====================================================
           LOGO
        ====================================================== */

        .logo {
            width: 82px;
            height: 82px;

            object-fit: contain;

            display: block;
            margin: 0 auto 18px;
        }

        /* =====================================================
           SUCCESS ICON
        ====================================================== */

        .success-icon {
            width: 76px;
            height: 76px;

            margin: 0 auto 20px;

            border-radius: 50%;

            background: #EAF3FF;

            color: #1D5FBF;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 40px;
            font-weight: bold;

            border: 5px solid #F5F9FF;

            box-shadow:
                0 5px 18px rgba(29, 95, 191, 0.12);
        }

        /* =====================================================
           TITLE
        ====================================================== */

        h1 {
            margin: 0 0 10px;

            color: #1D5FBF;

            font-size: 28px;

            font-weight: 700;

            letter-spacing: -0.3px;
        }

        /* =====================================================
           MESSAGE
        ====================================================== */

        .message {
            margin: 0 auto 26px;

            max-width: 350px;

            color: #64748B;

            font-size: 15px;

            line-height: 1.6;
        }

        /* =====================================================
           TICKET BOX
        ====================================================== */

        .ticket-box {
            background: #F4F8FF;

            border: 1px solid #D9E7F8;

            border-radius: 14px;

            padding: 16px;

            margin-bottom: 24px;
        }

        .ticket-label {
            display: block;

            color: #64748B;

            font-size: 11px;

            font-weight: 600;

            margin-bottom: 6px;

            text-transform: uppercase;

            letter-spacing: 0.7px;
        }

        .ticket-number {
            color: #1D5FBF;

            font-size: 21px;

            font-weight: 700;

            letter-spacing: 0.3px;
        }

        /* =====================================================
           BUTTONS
        ====================================================== */

        .btn {
            display: block;

            width: 100%;

            padding: 14px 20px;

            border-radius: 12px;

            text-decoration: none;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;

            transition:
                background 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;

            border: none;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* =====================================================
           PRIMARY BUTTON
        ====================================================== */

        .btn-primary {
            background: linear-gradient(135deg,
                    #1D5FBF,
                    #2563EB);

            color: #ffffff;

            margin-bottom: 10px;

            box-shadow:
                0 6px 16px rgba(29, 95, 191, 0.20);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg,
                    #174F9F,
                    #1D5FBF);

            box-shadow:
                0 8px 20px rgba(29, 95, 191, 0.25);
        }

        /* =====================================================
           SECONDARY BUTTON
        ====================================================== */

        .btn-secondary {
            background: #EEF4FB;

            color: #1D5FBF;

            border: 1px solid #D8E5F3;
        }

        .btn-secondary:hover {
            background: #E4EEF9;

            color: #174F9F;
        }

        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 480px) {

            body {
                padding: 16px;
            }

            .success-container {
                padding: 34px 22px 28px;

                border-radius: 22px;
            }

            .logo {
                width: 76px;
                height: 76px;
            }

            .success-icon {
                width: 70px;
                height: 70px;

                font-size: 37px;
            }

            h1 {
                font-size: 25px;
            }

            .message {
                font-size: 14px;
            }

            .ticket-number {
                font-size: 19px;
            }

            .btn {
                padding: 13px 18px;
            }
        }
    </style>

</head>

<body>

    <div class="success-container">

        <!-- =====================================================
             LOGO
        ====================================================== -->

        <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo" class="logo">

        <!-- =====================================================
             SUCCESS ICON
        ====================================================== -->

        <div class="success-icon">
            ✓
        </div>

        <!-- =====================================================
             TITLE
        ====================================================== -->

        <h1>
            Ticket Submitted!
        </h1>

        <!-- =====================================================
             MESSAGE
        ====================================================== -->

        <p class="message">
            The traffic violation ticket has been successfully recorded
            in the TrafficEnforceNet system.
        </p>

        <!-- =====================================================
             TICKET NUMBER
        ====================================================== -->

        @if (isset($violation) && $violation)
            <div class="ticket-box">

                <span class="ticket-label">
                    Ticket Number
                </span>

                <span class="ticket-number">
                    {{ $violation->ticket_number }}
                </span>

            </div>
        @endif

        <!-- =====================================================
             ACTION BUTTONS
        ====================================================== -->

        <a href="/enforcer/issue-ticket" class="btn btn-primary">
            Issue Another Ticket
        </a>

        <a href="/dashboard" class="btn btn-secondary">
            Return to Home
        </a>

    </div>

</body>

</html>
