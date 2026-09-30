<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prediction</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #e7ebf0;
            overflow: hidden;
        }
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            background: #e7ebf0;
            box-shadow: 15px 15px 30px rgba(163, 177, 198, .65), -15px -15px 30px rgba(255, 255, 255, .9);
            z-index: -1;
        }
        .bg1 { width: 190px; height: 190px; top: 7%; left: 7%; }
        .bg2 { width: 130px; height: 130px; right: 8%; bottom: 8%; }
        
        .card {
            width: 400px;
            max-width: 92%;
            padding: 35px 30px;
            border-radius: 35px;
            background: #e7ebf0;
            box-shadow: 20px 20px 40px rgba(163, 177, 198, .65), -20px -20px 40px rgba(255, 255, 255, .9);
        }
        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 24px;
            color: #0072ff;
            background: #e7ebf0;
            box-shadow: 8px 8px 16px rgba(163, 177, 198, .65), -8px -8px 16px rgba(255, 255, 255, .9);
        }
        h1 { text-align: center; font-size: 22px; color: #303943; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #7c858e; font-size: 12px; margin-bottom: 25px; }
        
        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }
        .input-group input {
            width: 100%;
            height: 50px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: #e7ebf0;
            color: #1a202c;
            font-size: 15px;
            padding: 0 18px;
            box-shadow: inset 4px 4px 8px rgba(163, 177, 198, .65), inset -4px -4px 8px rgba(255, 255, 255, .95);
        }
        .input-group label {
            position: absolute;
            left: 18px;
            top: -10px;
            color: #0072ff;
            font-size: 11px;
            background: #e7ebf0;
            padding: 0 4px;
            border-radius: 4px;
        }
        
        .main-btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 15px;
            background: #e7ebf0;
            color: #0072ff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 6px 6px 12px rgba(163, 177, 198, .65), -6px -6px 12px rgba(255, 255, 255, .95);
            margin-top: 5px;
        }
        .main-btn:active { transform: scale(.97); }
        
        .nav-links {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            font-size: 13px;
        }
        a { text-decoration: none; color: #0072ff; font-weight: 600; }
    </style>
</head>
<body>

    <div class="bg-circle bg1"></div>
    <div class="bg-circle bg2"></div>

    <div class="card">
        <div class="logo">✏️</div>
        <h1>Edit Record</h1>
        <p class="subtitle">Update score and study hours</p>

        <form action="/ExamAgent/update/<?= $prediction['id'] ?>" method="post">
            <div class="input-group">
                <input type="number" name="score" id="score" value="<?= esc($prediction['score']) ?>" min="0" max="100" required>
                <label for="score">Student Score (0-100)</label>
            </div>
            
            <div class="input-group">
                <input type="number" name="hours" id="hours" value="<?= esc($prediction['study_hours']) ?>" min="0" required>
                <label for="hours">Study Hours</label>
            </div>
            
            <button type="submit" class="main-btn">Update Record</button>
        </form>

        <div class="nav-links">
            <a href="/ExamAgent/dashboard">&larr; Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
