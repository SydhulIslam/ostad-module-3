<?php 

// Define a constant for the tasks file (tasks.json)
define("TASKS_FILE", "tasks.json");


//  Create a function to load tasks from the tasks.json file
// This function should read the JSON file and return the decoded array
function loadTasks():array{
    if(!file_exists(TASKS_FILE)){
        return [];
    }

    $data = file_get_contents(TASKS_FILE);

    return $data ? json_decode($data, true) : [];
}

// Load tasks from the tasks.json file
$tasks = loadTasks();


// Create a function to save tasks to the tasks.json file
// This function should take an array of tasks and save it back to the JSON file
function saveTasks(array $tasks):void{
    file_put_contents(TASKS_FILE, json_encode($tasks,JSON_PRETTY_PRINT));
}

// Check if the form has been submitted using POST request

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['task']) && !empty(trim($_POST['task']))){
        $tasks[] = [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ];

        saveTasks($tasks);
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;

    }elseif(isset($_POST['toggle'])){
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];

        saveTasks($tasks);
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;

    }elseif(isset($_POST['delete'])){
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks);

        saveTasks($tasks);
        header('Location:' . $_SERVER['PHP_SELF']);
        exit;
    }
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">

    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="container">
        <div class="task-card">
            <h1>To-Do Applecation </h1>

            <!-- Add Task Form -->
             <form action="" method="POST" >
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new task" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add Task</button>
                    </div>
                </div>
             </form>


            <!-- Task List -->
             <h2>Task List</h2>
             <ul class="ulclass">
                <?php if(empty($tasks)): ?>
                    <li>No tasks yet. Add one above!</li>
                <?php else: ?>
                    <?php foreach($tasks as $index => $task): ?>
                        <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index?>">
                                
                                <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                    <span class="task <?= $task['done'] ? 'task-done' : '' ?>">
                                        <?= htmlspecialchars($task['task']) ?>
                                    </span>
                                </button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="delete" value="<?= $index ?>">
                                <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                            </form>

                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>






             </ul>


        </div>
    </div>
</body>
</html>