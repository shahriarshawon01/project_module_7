<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config.php';
include_once '../classes/Database.php';
include_once '../classes/Task.php';

$database = new Database();
$db = $database->getConnection();

$task = new Task($db);

$request_method = $_SERVER["REQUEST_METHOD"];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $task->readOne();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            } else {
                echo json_encode(["message" => "Task not found."]);
            }
        } else {
            $stmt = $task->read();
            $tasks_arr = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $task_item = [
                    "id" => $id,
                    "title" => $title,
                    "description" => $description,
                    "priority" => $priority,
                    "is_completed" => $is_completed,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                ];
                array_push($tasks_arr, $task_item);
            }
            echo json_encode($tasks_arr);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->title)) {
            $task->title = $data->title;
            $task->description = $data->description ?? '';
            $task->priority = $data->priority ?? 'low';
            $task->is_completed = $data->is_completed ?? 0;

            if ($task->create()) {
                echo json_encode(["message" => "Task was created."]);
            } else {
                echo json_encode(["message" => "Unable to create task."]);
            }
        } else {
            echo json_encode(["message" => "Title is required."]);
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"));
            $task->id = $id;
            $task->title = $data->title;
            $task->description = $data->description;
            $task->priority = $data->priority;
            $task->is_completed = $data->is_completed;

            if ($task->update()) {
                echo json_encode(["message" => "Task was updated."]);
            } else {
                echo json_encode(["message" => "Unable to update task."]);
            }
        } else {
            echo json_encode(["message" => "Task ID is required."]);
        }
        break;

    case 'DELETE':
        if ($id) {
            $task->id = $id;
            if ($task->delete()) {
                echo json_encode(["message" => "Task was deleted."]);
            } else {
                echo json_encode(["message" => "Unable to delete task."]);
            }
        } else {
            echo json_encode(["message" => "Task ID is required."]);
        }
        break;

    default:
        echo json_encode(["message" => "Request method not supported."]);
        break;
}
