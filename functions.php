<?php
session_start();

function initializeTasks() {
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [];
    }
}

function validateTask($taskName, $priority) {
    $errors = [];
    if (empty($taskName)) {
        $errors[] = "Task name is required.";
    }
    if (!in_array($priority, ['High', 'Medium', 'Low'])) {
        $errors[] = "Please select a valid priority.";
    }
    return $errors;
}

function addTask($taskName, $taskDescription, $priority) {
    $_SESSION['tasks'][] = [
        'id' => uniqid(),
        'name' => $taskName,
        'description' => $taskDescription,
        'priority' => $priority
    ];
}

function editTask($taskId, $taskName, $taskDescription, $priority) {
    foreach ($_SESSION['tasks'] as &$task) {
        if ($task['id'] === $taskId) {
            $task['name'] = $taskName;
            $task['description'] = $taskDescription;
            $task['priority'] = $priority;
            break;
        }
    }
}

function deleteTask($taskId) {
    foreach ($_SESSION['tasks'] as $key => $task) {
        if ($task['id'] === $taskId) {
            unset($_SESSION['tasks'][$key]);
            $_SESSION['tasks'] = array_values($_SESSION['tasks']);
            break;
        }
    }
}

function getTheme() {
    return isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
}

function setTheme($theme) {
    setcookie('theme', $theme, time() + 86400, "/");
}

function getLastTaskName() {
    return isset($_COOKIE['last_task_name']) ? $_COOKIE['last_task_name'] : '';
}

?>
