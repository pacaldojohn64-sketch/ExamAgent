<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ExamAgent extends Controller
{
    public function index()
    {
        return view('auth');
    }

    public function storeUser()
    {
        $username = $this->request->getPost('username');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $db = \Config\Database::connect();
        try {
            $db->table('users')->insert([
                'username' => $username,
                'password' => $password
            ]);
            return redirect()->to('/ExamAgent')->with('success', 'Account created! Please log in.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration Error: ' . $e->getMessage());
        }
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            session()->set('isLoggedIn', true);
            session()->set('username', $username);
            session()->set('role', $user['role']);
            return redirect()->to('/ExamAgent/predictor');
        }

        return redirect()->to('/ExamAgent')->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/ExamAgent');
    }

    public function predictor()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/ExamAgent');
        return view('exam_form');
    }

    public function processForm()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/ExamAgent');

        $score = $this->request->getPost('score');
        $hours = $this->request->getPost('hours');
        
        $scriptPath = WRITEPATH . 'scripts/agent.py';
        $command = sprintf('python3 %s %s %s', escapeshellarg($scriptPath), escapeshellarg($score), escapeshellarg($hours));
        $output = shell_exec($command);
        $result = json_decode($output, true);
        
        if ($result && isset($result['predicted_tier'])) {
            $db = \Config\Database::connect();
            $db->table('student_predictions')->insert([
                'score' => (int)$score,
                'study_hours' => (int)$hours,
                'predicted_tier' => $result['predicted_tier'],
                'submitted_by' => session()->get('username') // <-- AUDIT TRAIL ADDED HERE
            ]);
        }
        return redirect()->to('/ExamAgent/dashboard');
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/ExamAgent');

        $db = \Config\Database::connect();
        
        $chartBuilder = $db->table('student_predictions');
        $filterTier = trim($this->request->getVar('tier') ?? '');
        if (!empty($filterTier)) {
            $chartBuilder->where('predicted_tier', $filterTier);
        }
        $allChartData = $chartBuilder->select('predicted_tier')->get()->getResultArray();
        
        $tierCounts = ['Beginner' => 0, 'Intermediate' => 0, 'Advanced' => 0];
        foreach ($allChartData as $row) {
            $tier = trim($row['predicted_tier']);
            if (isset($tierCounts[$tier])) $tierCounts[$tier]++;
        }

        $tableBuilder = $db->table('student_predictions');
        if (!empty($filterTier)) {
            $tableBuilder->where('predicted_tier', $filterTier);
        }
        
        $totalRecords = $tableBuilder->countAllResults(false);
        $perPage = 5;
        $page = (int)($this->request->getVar('page') ?? 1);
        $offset = ($page - 1) * $perPage;
        
        $predictions = $tableBuilder->orderBy('created_at', 'DESC')
                                    ->limit($perPage, $offset)
                                    ->get()
                                    ->getResultArray();
                                    
        $pager = \Config\Services::pager();

        $data = [
            'predictions' => $predictions,
            'current_filter' => $filterTier,
            'tierCounts' => $tierCounts,
            'pager_links' => $pager->makeLinks($page, $perPage, $totalRecords)
        ];
        
        return view('dashboard', $data);
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/ExamAgent');
        if (session()->get('role') !== 'admin') return redirect()->to('/ExamAgent/dashboard');

        $db = \Config\Database::connect();
        $prediction = $db->table('student_predictions')->where('id', $id)->get()->getRowArray();
        if (!$prediction) return redirect()->to('/ExamAgent/dashboard');

        return view('edit_prediction', ['prediction' => $prediction]);
    }

    public function update($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/ExamAgent/dashboard');
        }

        $score = $this->request->getPost('score');
        $hours = $this->request->getPost('hours');

        $scriptPath = WRITEPATH . 'scripts/agent.py';
        $command = sprintf('python3 %s %s %s', escapeshellarg($scriptPath), escapeshellarg($score), escapeshellarg($hours));
        $output = shell_exec($command);
        $result = json_decode($output, true);
        $predictedTier = ($result && isset($result['predicted_tier'])) ? $result['predicted_tier'] : 'Intermediate';

        $db = \Config\Database::connect();
        $db->table('student_predictions')->where('id', $id)->update([
            'score' => (int)$score,
            'study_hours' => (int)$hours,
            'predicted_tier' => $predictedTier
        ]);

        return redirect()->to('/ExamAgent/dashboard');
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/ExamAgent/dashboard');
        }

        $db = \Config\Database::connect();
        $db->table('student_predictions')->where('id', $id)->delete();
        return redirect()->to('/ExamAgent/dashboard');
    }

    public function exportCsv()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/ExamAgent');

        $db = \Config\Database::connect();
        $records = $db->table('student_predictions')->orderBy('created_at', 'DESC')->get()->getResultArray();

        $filename = 'exam_predictions_' . date('Ymd_His') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');
        // Added "Submitted By" to the CSV Export header
        fputcsv($file, ["ID", "Score", "Study Hours", "Predicted Tier", "Submitted By", "Date Recorded"]);
        foreach ($records as $row) {
            fputcsv($file, [$row['id'], $row['score'], $row['study_hours'], $row['predicted_tier'], $row['submitted_by'], $row['created_at']]);
        }
        fclose($file);
        exit;
    }

    public function uploadCsv()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/ExamAgent');

        $file = $this->request->getFile('csv_file');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filepath = $file->getTempName();
            
            $db = \Config\Database::connect();
            $scriptPath = WRITEPATH . 'scripts/agent.py';
            $count = 0;
            
            if (($handle = fopen($filepath, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($row) >= 2) {
                        
                        $scoreRaw = preg_replace('/[^0-9]/', '', $row[0]);
                        $hoursRaw = preg_replace('/[^0-9]/', '', $row[1]);

                        if (is_numeric($scoreRaw) && is_numeric($hoursRaw)) {
                            $score = (int)$scoreRaw;
                            $hours = (int)$hoursRaw;
                            
                            $command = sprintf('python3 %s %d %d', escapeshellarg($scriptPath), $score, $hours);
                            $output = shell_exec($command);
                            
                            $result = json_decode(trim($output), true);
                            
                            if ($result && isset($result['predicted_tier'])) {
                                $tier = preg_replace('/[^A-Za-z]/', '', $result['predicted_tier']);
                                
                                $db->table('student_predictions')->insert([
                                    'score' => $score,
                                    'study_hours' => $hours,
                                    'predicted_tier' => $tier,
                                    'submitted_by' => session()->get('username') // <-- AUDIT TRAIL ADDED HERE
                                ]);
                                $count++;
                            }
                        }
                    }
                }
                fclose($handle);
            }
            session()->setFlashdata('success', "$count student records successfully imported and predicted!");
        } else {
            session()->setFlashdata('error', "Failed to upload CSV file. Please try again.");
        }
        
        return redirect()->to('/ExamAgent/dashboard');
    }
}

