<?php
header('Content-Type: application/json');

// Define the data file path
$data_file = 'data.json';

// --- Utility Functions ---

// Read data from the JSON file
function read_data($file) {
    if (!file_exists($file)) {
        return ['appointments' => [], 'snd_reports' => [], 'tasks' => []];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: ['appointments' => [], 'snd_reports' => [], 'tasks' => []];
}

// Write data back to the JSON file
function write_data($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Generate a unique ID (simple time-based ID for simulation)
function generate_unique_id() {
    return time() . mt_rand(100, 999);
}

// --- Request Handling ---

$data = read_data($data_file);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data_type = $_GET['data_type'] ?? null;
    $id = $_GET['id'] ?? null;
    
    if ($data_type === 'appointments') {
        echo json_encode(['status' => 'success', 'data' => $data['appointments']]);
    } elseif ($data_type === 'snd_reports') {
        if ($id !== null) {
            // Fetch single report detail
            $report = array_filter($data['snd_reports'], function($r) use ($id) {
                return $r['id'] == $id;
            });
            $report = reset($report);
            if ($report) {
                echo json_encode(['status' => 'success', 'data' => $report]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Report not found']);
            }
        } else {
            // Fetch all reports
            echo json_encode(['status' => 'success', 'data' => $data['snd_reports']]);
        }
    } elseif ($data_type === 'tasks') {
        echo json_encode(['status' => 'success', 'data' => $data['tasks']]);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data type specified']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? null;

    if ($action === 'delete_appointment') {
        $appt_id = $input['id'];
        $data['appointments'] = array_filter($data['appointments'], function($a) use ($appt_id) {
            return $a['id'] != $appt_id;
        });
        write_data($data_file, $data);
        echo json_encode(['status' => 'success', 'message' => 'Appointment deleted.']);

    } elseif ($action === 'update_snd_status') {
        $report_id = $input['id'];
        $new_status = $input['status'];
        
        $found = false;
        foreach ($data['snd_reports'] as $key => $report) {
            if ($report['id'] == $report_id) {
                $data['snd_reports'][$key]['status'] = $new_status;
                $found = true;
                break;
            }
        }
        
        if ($found) {
            write_data($data_file, $data);
            echo json_encode(['status' => 'success', 'message' => 'Report status updated to ' . $new_status]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Report not found.']);
        }

    } elseif ($action === 'add_task') {
        $new_task = [
            'id' => (int)generate_unique_id(),
            'description' => $input['description'] ?? '',
            'completed' => false
        ];
        $data['tasks'][] = $new_task;
        write_data($data_file, $data);
        echo json_encode(['status' => 'success', 'message' => 'Task added.', 'task' => $new_task]);

    } elseif ($action === 'toggle_task') {
        $task_id = $input['id'];
        $new_state = null;
        
        $found = false;
        foreach ($data['tasks'] as $key => $task) {
            if ($task['id'] == $task_id) {
                $data['tasks'][$key]['completed'] = !$task['completed'];
                $new_state = $data['tasks'][$key]['completed'];
                $found = true;
                break;
            }
        }
        
        if ($found) {
            write_data($data_file, $data);
            echo json_encode(['status' => 'success', 'message' => 'Task status toggled.', 'completed' => $new_state]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Task not found.']);
        }

    } elseif ($action === 'delete_task') {
        $task_id = $input['id'];
        $data['tasks'] = array_filter($data['tasks'], function($t) use ($task_id) {
            return $t['id'] != $task_id;
        });
        write_data($data_file, $data);
        echo json_encode(['status' => 'success', 'message' => 'Task deleted.']);

    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action or missing payload.']);
    }

} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
