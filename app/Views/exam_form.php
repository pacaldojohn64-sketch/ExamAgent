<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Exam Grader - Predictor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { padding: 0; margin: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }
        body { min-height: 100vh; display: flex; justify-content: center; align-items: center; background: #e7ebf0; padding: 20px; }
        
        .card {
            width: 400px;
            max-width: 100%;
            padding: 35px 30px;
            border-radius: 35px;
            background: #e7ebf0;
            box-shadow: 20px 20px 40px rgba(163, 177, 198, .65), -20px -20px 40px rgba(255, 255, 255, .9);
        }
        .logo {
            width: 70px; height: 70px; margin: 0 auto 10px; display: flex; justify-content: center; align-items: center;
            border-radius: 50%; font-size: 24px; color: #0072ff; background: #e7ebf0;
            box-shadow: 8px 8px 16px rgba(163, 177, 198, .65), -8px -8px 16px rgba(255, 255, 255, .9);
        }
        h1 { text-align: center; font-size: 22px; color: #303943; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #7c858e; font-size: 12px; margin-bottom: 25px; }
        
        .input-group { position: relative; width: 100%; margin-bottom: 20px; }
        .input-group input {
            width: 100%; height: 50px; border: none; outline: none; border-radius: 14px;
            background: #e7ebf0; color: #1a202c; font-size: 15px; padding: 0 18px;
            box-shadow: inset 4px 4px 8px rgba(163, 177, 198, .65), inset -4px -4px 8px rgba(255, 255, 255, .95);
        }
        .input-group input::placeholder { color: transparent; }
        .input-group label {
            position: absolute; left: 18px; top: 15px; color: #7d8790; font-size: 13px; pointer-events: none; transition: all .25s ease;
        }
        .input-group input:focus ~ label, .input-group input:not(:placeholder-shown) ~ label {
            top: -10px; left: 12px; font-size: 11px; color: #0072ff; background: #e7ebf0; padding: 0 4px; border-radius: 4px;
        }
        
        .main-btn {
            width: 100%; height: 50px; border: none; border-radius: 15px; background: #e7ebf0; color: #0072ff;
            font-size: 15px; font-weight: 700; cursor: pointer;
            box-shadow: 6px 6px 12px rgba(163, 177, 198, .65), -6px -6px 12px rgba(255, 255, 255, .95);
        }
        .main-btn:active { transform: scale(.97); }
        .btn-upload { color: #28a745; margin-top: 5px;}
        
        .divider { border: none; border-top: 2px solid rgba(163, 177, 198, .3); margin: 30px 0; }
        
        .nav-links { display: flex; justify-content: space-between; margin-top: 20px; font-size: 13px; }
        .nav-links a { text-decoration: none; color: #0072ff; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">📊</div>
        <h1>AI Exam Grader</h1>
        
        <!-- Single Entry Form -->
        <p class="subtitle">Enter single student evaluation</p>
        <form action="/ExamAgent/processForm" method="post">
            <div class="input-group">
                <input type="number" name="score" id="score" placeholder=" " min="0" max="100" required>
                <label for="score">Student Score (0-100)</label>
            </div>
            <div class="input-group">
                <input type="number" name="hours" id="hours" placeholder=" " min="0" required>
                <label for="hours">Study Hours</label>
            </div>
            <button type="submit" class="main-btn">Predict & Save</button>
        </form>

        <hr class="divider">

        <!-- Batch Upload Form -->
        <p class="subtitle" style="margin-bottom: 15px;">Or Bulk Import via CSV</p>
        <form action="/ExamAgent/uploadCsv" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <!-- File inputs are styled slightly differently -->
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required style="padding: 12px 18px; color: #7d8790; font-size: 13px;">
            </div>
            <button type="submit" class="main-btn btn-upload">Upload & Predict Batch</button>
        </form>

        <div class="nav-links">
            <a href="/ExamAgent/dashboard">&rarr; View Dashboard</a>
        </div>
    </div>
</body>
</html>

