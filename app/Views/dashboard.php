<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results Dashboard & Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            flex-direction: column;
            align-items: center;
            background: #e7ebf0;
            padding: 20px;
        }
        .card {
            width: 750px;
            max-width: 100%;
            padding: 30px;
            border-radius: 35px;
            background: #e7ebf0;
            box-shadow: 20px 20px 40px rgba(163, 177, 198, .65), -20px -20px 40px rgba(255, 255, 255, .9);
            margin-bottom: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        h2 { font-size: 20px; color: #303943; }
        
        /* Neumorphic Buttons */
        .btn-logout {
            background: #e7ebf0;
            color: #dc3545;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 5px 5px 10px rgba(163, 177, 198, .65), -5px -5px 10px rgba(255, 255, 255, .95);
        }
        .btn-export {
            background: #e7ebf0;
            color: #28a745;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 5px 5px 10px rgba(163, 177, 198, .65), -5px -5px 10px rgba(255, 255, 255, .95);
        }
        
        /* Chart Container */
        .chart-wrapper {
            width: 100%;
            max-width: 250px;
            margin: 0 auto 25px;
            padding: 15px;
            border-radius: 25px;
            background: #e7ebf0;
            box-shadow: inset 5px 5px 10px rgba(163, 177, 198, .65), inset -5px -5px 10px rgba(255, 255, 255, .95);
        }

        /* Filter Form */
        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #7d8790;
        }
        select {
            padding: 8px 12px;
            border-radius: 12px;
            border: none;
            background: #e7ebf0;
            color: #303943;
            box-shadow: inset 3px 3px 6px rgba(163, 177, 198, .65), inset -3px -3px 6px rgba(255, 255, 255, .95);
            outline: none;
        }
        .btn-filter {
            padding: 8px 16px;
            border-radius: 12px;
            border: none;
            background: #e7ebf0;
            color: #0072ff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 5px 5px 10px rgba(163, 177, 198, .65), -5px -5px 10px rgba(255, 255, 255, .95);
        }
        
        /* Table container */
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: inset 4px 4px 8px rgba(163, 177, 198, .65), inset -4px -4px 8px rgba(255, 255, 255, .95);
            padding: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
            color: #303943;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid rgba(163, 177, 198, .4);
        }
        th { color: #0072ff; font-weight: 600; }
        tr:last-child td { border-bottom: none; }
        
        /* CRUD Action Buttons */
        .btn-action {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-right: 4px;
            box-shadow: 3px 3px 6px rgba(163, 177, 198, .65), -3px -3px 6px rgba(255, 255, 255, .95);
        }
        .btn-edit { color: #0072ff; background: #e7ebf0; }
        .btn-delete { color: #dc3545; background: #e7ebf0; }
        
        .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        a { text-decoration: none; color: #0072ff; font-weight: 600; }
        .clear-link { font-size: 12px; color: #7c858e; }
              /* Pagination Styles */
        .pagination { 
            display: flex; list-style: none; justify-content: center; gap: 10px; margin-top: 15px; 
            flex-wrap: wrap; padding: 0;
        }
        .pagination li a, .pagination li span { 
            padding: 8px 14px; border-radius: 10px; text-decoration: none; font-size: 13px; font-weight: 600;
            color: #0072ff; background: #e7ebf0;
            box-shadow: 4px 4px 8px rgba(163,177,198,.65), -4px -4px 8px rgba(255,255,255,.95);
        }
        .pagination li.active a, .pagination li.active span {
            box-shadow: inset 3px 3px 6px rgba(163,177,198,.65), inset -3px -3px 6px rgba(255,255,255,.95);
            color: #303943;
        }

    </style>
</head>
<body>

    <div class="card">
                <div class="top-bar">
            <h2>Prediction Analytics</h2>
            <div style="display:flex; gap:10px; align-items:center;">
                <!-- Role Badge -->
                <span style="font-size:12px; font-weight:bold; color: <?= session()->get('role') === 'admin' ? '#dc3545' : '#0072ff' ?>;">
                    <?= strtoupper(session()->get('role')) ?>
                </span>
                
                <a href="/ExamAgent/export" class="btn-export">⬇ Export CSV</a>
                <a href="/ExamAgent/logout" class="btn-logout">Logout</a>
            </div>
        </div>
              <!-- Alerts for CSV Upload -->
        <?php if(session()->getFlashdata('success')): ?>
            <div style="background: #28a745; color: white; padding: 12px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-size: 13px; font-weight: 600;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div style="background: #dc3545; color: white; padding: 12px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-size: 13px; font-weight: 600;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>


        <!-- Chart.js Canvas Container -->
        <?php if (!empty($predictions)): ?>
            <div class="chart-wrapper">
                <canvas id="tierChart"></canvas>
            </div>
        <?php endif; ?>

        <!-- Filter Form -->
        <form action="/ExamAgent/dashboard" method="get" class="filter-form">
            <label><strong>Filter Tier:</strong></label>
            <select name="tier">
                <option value="">All Tiers</option>
                <option value="Beginner" <?= (isset($current_filter) && $current_filter == 'Beginner') ? 'selected' : '' ?>>Beginner</option>
                <option value="Intermediate" <?= (isset($current_filter) && $current_filter == 'Intermediate') ? 'selected' : '' ?>>Intermediate</option>
                <option value="Advanced" <?= (isset($current_filter) && $current_filter == 'Advanced') ? 'selected' : '' ?>>Advanced</option>
            </select>
            <button type="submit" class="btn-filter">Apply</button>
            <?php if(!empty($current_filter)): ?>
                <a href="/ExamAgent/dashboard" class="clear-link">Clear Filter</a>
            <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
            <tr>
                <th>Score</th>
                <th>Hours</th>
                <th>Predicted Tier</th>
                <th>Submitted By</th> <!-- New Header -->
                <th>Date</th>
                <th>Actions</th>
            </tr>

                <?php if (!empty($predictions)): ?>
                    <?php foreach ($predictions as $row): ?>
                    <tr>
                <td><?= esc($row['score']) ?></td>
                <td><?= esc($row['study_hours']) ?></td>
                <td><strong><?= esc($row['predicted_tier']) ?></strong></td>
                <td><?= esc($row['submitted_by'] ?? 'Unknown') ?></td> <!-- New Data Cell -->
                <td><?= esc(date('M d, Y', strtotime($row['created_at']))) ?></td>
                <td>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <a href="/ExamAgent/edit/<?= $row['id'] ?>" class="btn-action btn-edit">Edit</a>
                                <a href="/ExamAgent/delete/<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            <?php else: ?>
                                <span style="font-size: 11px; color: #7c858e;">View Only</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #7c858e;">No records found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
              <!-- Pagination Links -->
        <?php if (!empty($pager_links)): ?>
            <div style="margin-bottom: 20px;">
                <?= $pager_links ?>
            </div>
        <?php endif; ?>

        <div class="nav-links">
            <a href="/ExamAgent/predictor">&larr; Back to Predictor</a>
        </div>
    </div>

    <!-- Render Chart.js -->
    <script>
        <?php if (!empty($predictions)): ?>
        const ctx = document.getElementById('tierChart').getContext('2d');
        const tierChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Beginner', 'Intermediate', 'Advanced'],
                datasets: [{
                    data: [
                        <?= $tierCounts['Beginner'] ?>, 
                        <?= $tierCounts['Intermediate'] ?>, 
                        <?= $tierCounts['Advanced'] ?>
                    ],
                    backgroundColor: [
                        '#dc3545', // Red
                        '#f39c12', // Orange/Yellow
                        '#0072ff'  // Blue
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: "'Poppins', sans-serif", size: 11 }, color: '#303943' }
                    }
                },
                cutout: '65%'
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
