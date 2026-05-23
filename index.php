<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Event Registration System</title>

    <style>
        /* Base Reset & Theme Matching */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #0d0e12; /* Dark theme background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Card Container matching login.php */
        .container {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
        }

        /* Dark Header Block */
        .card-header {
            background-color: #22252a; /* Matching Account Gateway Header */
            padding: 35px 25px;
            text-align: center;
            border-bottom: 4px solid #ff6600; /* Distinct orange accent divider */
        }

        .card-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .card-header p {
            color: #a0aec0;
            font-size: 14px;
        }

        /* Action Body Details */
        .card-body {
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Cleaned Up Button Styles */
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            text-align: center;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
        }

        /* Primary Action Button (Login) */
        .btn-primary {
            background: #ff6600;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #e05500;
        }

        /* Secondary Action Button (Register) */
        .btn-secondary {
            background: transparent;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f7fafc;
            color: #1a202c;
            border-color: #cbd5e0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card-header">
            <h1>Welcome Portal</h1>
            <p>Student Event Registration & Management System</p>
        </div>

        <div class="card-body">
            <a href="auth/login.php" class="btn btn-primary">Login to Workspace</a>
            <a href="auth/register.php" class="btn btn-secondary">Create Student Account</a>
        </div>
    </div>

</body>
</html>