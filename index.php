<?php
$tasksFile = 'tasks.json';


$tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task"])) {
    $newTask = trim($_POST["task"]);
    if (!empty($newTask)) {
        $tasks[] = ["task" => htmlspecialchars($newTask), "done" => false];
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit();
}


if (isset($_GET["toggle"])) {
    $index = (int) $_GET["toggle"];
    if (isset($tasks[$index])) {
        $tasks[$index]["done"] = !$tasks[$index]["done"];
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit();
}


if (isset($_GET["delete"])) {
    $index = (int) $_GET["delete"];
    if (isset($tasks[$index])) {
        array_splice($tasks, $index, 1);
        file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do App</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: auto; padding: 20px; }
        ul { list-style: none; padding: 0; }
        li { padding: 10px; margin: 5px 0; background: #f4f4f4; display: flex; justify-content: space-between; align-items: center; }
        .done { text-decoration: line-through; color: gray; }
        button { background: red; color: white; border: none; padding: 5px 8px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Simple To-Do App</h2>
    <form method="post">
        <input type="text" name="task" placeholder="Enter new task" required>
        <button type="submit">Add Task</button>
    </form>
    <ul>
        <?php foreach ($tasks as $index => $task): ?>
            <li>
                <a href="?toggle=<?= $index ?>" class="<?= $task['done'] ? 'done' : '' ?>">
                    <?= $task['task'] ?>
                </a>
                <a href="?delete=<?= $index ?>"><button>Delete</button></a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
