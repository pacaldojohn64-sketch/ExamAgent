<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Grading System - Auth</title>
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
        /* Background Animations */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            background: #e7ebf0;
            box-shadow: 15px 15px 30px rgba(163, 177, 198, .65), -15px -15px 30px rgba(255, 255, 255, .9);
            z-index: -1;
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .bg1 { width: 190px; height: 190px; top: 7%; left: 7%; }
        .bg2 { width: 130px; height: 130px; right: 8%; bottom: 8%; animation-delay: 1.5s; }
        
        /* Card & 3D Flip Mechanics */
        .auth-wrapper {
            width: 400px;
            max-width: 92%;
            perspective: 1600px;
        }
        .auth-card {
            position: relative;
            width: 100%;
            min-height: 540px;
            transform-style: preserve-3d;
            transition: transform 1s cubic-bezier(.68, -0.55, .27, 1.55);
        }
        .auth-card.flipped { transform: rotateY(180deg); }
        
        .form-panel {
            position: absolute;
            inset: 0;
            padding: 30px 25px;
            border-radius: 35px;
            background: #e7ebf0;
            box-shadow: 20px 20px 40px rgba(163, 177, 198, .65), -20px -20px 40px rgba(255, 255, 255, .9);
            backface-visibility: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .signup-panel {
            transform: rotateY(180deg);
        }
        
        /* Logo & Typography */
        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 24px;
            color: #0072ff;
            background: #e7ebf0;
            box-shadow: 8px 8px 16px rgba(163, 177, 198, .65), -8px -8px 16px rgba(255, 255, 255, .9);
        }
        h1 { text-align: center; font-size: 24px; color: #303943; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #7c858e; font-size: 12px; margin-bottom: 20px; }
        
        /* Flash messages */
        .alert-error { color: #dc3545; font-size: 12px; text-align: center; margin-bottom: 10px; font-weight: 600; }
        .alert-success { color: #28a745; font-size: 12px; text-align: center; margin-bottom: 10px; font-weight: 600; }

        /* Inputs - Fixed for mobile visibility */
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
            transition: box-shadow .25s ease;
        }
        .input-group input:focus {
            box-shadow: inset 2px 2px 4px rgba(163, 177, 198, .55), inset -2px -2px 4px rgba(255, 255, 255, .95);
        }
        .input-group input::placeholder { color: transparent; }
        .input-group label {
            position: absolute;
            left: 18px;
            top: 15px;
            color: #7d8790;
            font-size: 13px;
            pointer-events: none;
            transition: all .25s ease;
            background: transparent;
        }
        .input-group input:focus ~ label, 
        .input-group input:not(:placeholder-shown) ~ label {
            top: -10px;
            left: 12px;
            font-size: 11px;
            color: #0072ff;
            background: #e7ebf0;
            padding: 0 4px;
            border-radius: 4px;
        }
        
        /* Buttons & Toggles */
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
            transition: .3s ease;
        }
        .main-btn:active { transform: scale(.97); }
        
        .switch-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 18px;
            font-size: 13px;
            color: #7d8790;
        }
        .switch-circle {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background: #e7ebf0;
            color: #0072ff;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 5px 5px 10px rgba(163, 177, 198, .65), -5px -5px 10px rgba(255, 255, 255, .95);
            transition: .35s ease;
        }
        .switch-circle:hover {
            color: #fff;
            background: #0072ff;
            transform: rotate(180deg) scale(1.08);
            box-shadow: 0 6px 20px rgba(0, 114, 255, .35);
        }
    </style>
</head>
<body>

    <div class="bg-circle bg1"></div>
    <div class="bg-circle bg2"></div>

    <div class="auth-wrapper">
        <div class="auth-card" id="authCard">
            
            <!-- ====== FRONT: LOGIN PANEL ====== -->
            <div class="form-panel">
                <div class="logo">🔒</div>
                <h1>Welcome Back</h1>
                <p class="subtitle">Login to your exam system</p>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="/ExamAgent/login" method="post">
                    <div class="input-group">
                        <input type="text" name="username" id="log_user" placeholder=" " required>
                        <label for="log_user">Username</label>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="log_pass" placeholder=" " required>
                        <label for="log_pass">Password</label>
                    </div>
                    <button type="submit" class="main-btn">Login</button>
                </form>

                <div class="switch-area">
                    <span>Don't have an account?</span>
                    <button type="button" class="switch-circle" onclick="toggleCard()">➔</button>
                </div>
            </div>

            <!-- ====== BACK: SIGNUP PANEL ====== -->
            <div class="form-panel signup-panel">
                <div class="logo">📝</div>
                <h1>Create Account</h1>
                <p class="subtitle">Join the Grading System</p>

                <form action="/ExamAgent/storeUser" method="post">
                    <div class="input-group">
                        <input type="text" name="username" id="reg_user" placeholder=" " required>
                        <label for="reg_user">Username</label>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="reg_pass" placeholder=" " required>
                        <label for="reg_pass">Password</label>
                    </div>
                    <button type="submit" class="main-btn">Register</button>
                </form>

                <div class="switch-area">
                    <span>Already have an account?</span>
                    <button type="button" class="switch-circle" onclick="toggleCard()">➔</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Script to Handle the 3D Flip -->
    <script>
        function toggleCard() {
            document.getElementById('authCard').classList.toggle('flipped');
        }
    </script>
</body>
</html>
