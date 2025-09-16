<?php

$TXT = strtolower(readline("Which Txt do you wish to use? (CSV or JSON): "));

if ($TXT === "csv") {
    $path = 'Tasks.csv';
} elseif ($TXT === "json") {
    $path = 'Tasks.json';
} else {
    echo "Invalid format. Exiting...\n";
    exit;
}

$Tasks = [];

if (file_exists($path)) {
    if ($TXT === "csv") {
        $Tasks = array_map('str_getcsv', file($path));
        $Tasks = array_column($Tasks, 0);
    } else {
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        $Tasks = $data["Tasks"] ?? [];
    }
} else {
    $Tasks = ["Undefined", "Recover", "Port"];
}

$Help = [
    "help - Shows a list of commands",
    "n - End interface session",
    "view - Displays the Tasks",
    "add - Add a new Task",
    "delete - Delete a Task by index"
];

function view($Tasks) {
    $UserInp = readline("D:\\> Which task do you want to view? all/[id]: ");
    if ($UserInp == "all") {
        foreach ($Tasks as $n => $z) {
            $num = $n;
            echo "$num. $z\n";
        }
    } elseif (is_numeric($UserInp) && isset($Tasks[$UserInp])) {
        echo $Tasks[$UserInp] . "\n";
    } else {
        echo "Invalid index.\n";
    }
}

function add(&$Tasks) {
    $newTask = readline("D:\\> Enter the new task: ");
    if (trim($newTask) !== "") {
        array_push($Tasks, $newTask);
        echo "Task added.\n";
    } else {
        echo "Empty task not added.\n";
    }
}

function delete(&$Tasks) {
    $delTask = readline("D:\\> Enter the index of the task to delete: ");
    if (is_numeric($delTask) && isset($Tasks[$delTask])) {
        unset($Tasks[$delTask]);
        $Tasks = array_values($Tasks);
        echo "Task deleted.\n";
    } else {
        echo "Invalid index.\n";
    }
}

function saveTasks($Tasks, $TXT, $path) {
    if ($TXT === "csv") {
        $lines = array_map(function($task) {
            return "$task\n";
        }, $Tasks);
        file_put_contents($path, $lines);
    } else {
        $data = ["Tasks" => $Tasks];
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }
}

while (true) {
    $UserInput = strtolower(readline("D:\\> "));

    switch ($UserInput) {
        case "help":
            foreach ($Help as $line) {
                echo "$line\n";
            }
            break;
        case "view":
            view($Tasks);
            break;
        case "add":
            add($Tasks);
            saveTasks($Tasks, $TXT, $path);
            break;
        case "delete":
            delete($Tasks);
            saveTasks($Tasks, $TXT, $path);
            break;
        case "n":
            echo "Session ended.\n";
            exit;
        default:
            echo "Unknown command. Type 'help' for options.\n";
            break;
    }
}
?>
