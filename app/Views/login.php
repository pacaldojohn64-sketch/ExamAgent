<!DOCTYPE html>
<html>
<head>
    <title>Login - AI Exam Grader</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; max-width: 350px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; box-sizing: border-box; border-radius: 4px; border: 1px solid #ccc; }
        .btn-submit { background: #007bff; color: white; border: none; cursor: pointer; font-weight: bold; }
        .error { color: red; font-size: 14px; text-align: center; }
        .success { color: green; font-size: 14px; text-align: center; }
        a { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align: center;">System Login</h2>
        
        <?php if(session()->getFlashdata('error')): ?>
            <p class="error"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <p class="success"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>

        <form action="/ExamAgent/login" method="post">
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit" class="btn-submit">Login</button>
        </form>
        <a href="/ExamAgent/register">Don't have an account? Register</a>
    </div>
</body>
</html>
