<?php

include('functions.php');


initializeTasks();


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_name'], $_POST['priority'])) {
    
    $taskName = htmlspecialchars(trim($_POST['task_name']));
    $taskDescription = isset($_POST['task_description']) ? htmlspecialchars(trim($_POST['task_description'])) : '';
    $priority = htmlspecialchars(trim($_POST['priority']));

    
    $errors = validateTask($taskName, $priority);

    if (empty($errors)) {
        if (isset($_POST['task_id'])) {
            
            $taskId = $_POST['task_id'];
            editTask($taskId, $taskName, $taskDescription, $priority);
        } else {
           
            addTask($taskName, $taskDescription, $priority);
        }

        header("Location: index.php");
        exit();
    } else {
        $error = implode("<br>", $errors);
    }
}

if (isset($_GET['delete'])) {
    $taskIdToDelete = $_GET['delete'];
    deleteTask($taskIdToDelete);
    $_SESSION['message'] = "Task deleted successfully!";
    header("Location: index.php");
    exit();
}

if (isset($_GET['edit'])) {
    $taskId = $_GET['edit'];
    $taskToEdit = null;
    foreach ($_SESSION['tasks'] as $task) {
        if ($task['id'] === $taskId) {
            $taskToEdit = $task;
            break;
        }
    }
}

if (isset($_GET['theme'])) {
    $theme = $_GET['theme'];
    setTheme($theme);
    header("Location: index.php");
    exit();
}

$theme = getTheme();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body.light {
            background-color: #f0f0f0;
            color: #333;
        }
        body.dark {
            background-color: #333;
            color: #fff;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body class="<?php echo $theme; ?>">
    <div class="container">
        <h1>Task Management System</h1>

        <div class="theme-switcher">
            <a href="?theme=light" class="theme-icon" id="theme-toggle" title="Switch to Dark Mode">
            <i class="fas fa-sun"></i> 
            </a>
        </div>



        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <h2 class="task-heading">
            <?php echo isset($taskToEdit) ? 'Update Task' : 'Create Task'; ?>
        </h2>

        <form method="post">
            <?php if (isset($taskToEdit)): ?>
                <input type="hidden" name="task_id" value="<?php echo $taskToEdit['id']; ?>">
            <?php endif; ?>

            <label for="task_name">Task Name:</label>
            <input type="text" id="task_name" name="task_name" value="<?php echo isset($taskToEdit) ? htmlspecialchars($taskToEdit['name']) : ''; ?>" required><br>
            
            <label for="task_description">Task Description:</label>
            <textarea id="task_description" name="task_description" ><?php echo isset($taskToEdit) ? htmlspecialchars($taskToEdit['description']) : ''; ?></textarea><br>
            
            <label for="priority">Priority:</label>
            <select id="priority" name="priority" required>
                <option value="High" <?php echo isset($taskToEdit) && $taskToEdit['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                <option value="Medium" <?php echo isset($taskToEdit) && $taskToEdit['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="Low" <?php echo isset($taskToEdit) && $taskToEdit['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
            </select><br>
            
            <button type="submit"><?php echo isset($taskToEdit) ? 'Update Task' : 'Add Task'; ?></button>
        </form>

        <h2>Current Tasks</h2>
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($_SESSION['tasks'])) {
                    echo '<tr><td colspan="4">No tasks yet!</td></tr>';
                } else {
                    foreach ($_SESSION['tasks'] as $task) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($task['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($task['description']) . '</td>';
                        echo '<td>' . htmlspecialchars($task['priority']) . '</td>';
                        echo '<td>
                                <a href="?edit=' . $task['id'] . '">Edit</a>
                                <a href="?delete=' . $task['id'] . '" onclick="return confirm(\'Are you sure?\')">Delete</a>
                              </td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="themeSwitch.js"></script>

</body>
</html>
