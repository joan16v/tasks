<?php

session_start();
require('./configure.php');
require('./connect_db.php');
$mysqli = new mysqli(HOST, MYSQL_USER, MYSQL_PASSWORD, MYDB);
mysqli_query($link, "SET NAMES 'UTF8'");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
set_exception_handler(function($e) {
    error_log($e->getMessage());
    exit('Error connecting to database');
});
$users = mysqli_query($link, "select * from tareas_users");
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$user = (isset($_GET['user'])) ? $_GET['user'] : '';
$tarea = (isset($_GET['tarea'])) ? $_GET['tarea'] : '';
$currentWeek = getCurrentWeek();
$currentWeekValue = getCurrentWeekValue();
$currentYear = date('Y');
$year = (isset($_GET['year'])) ? $_GET['year'] : $currentYear;
$week = (isset($_GET['week'])) ? $_GET['week'] : $currentWeekValue;

function getCurrentWeekValue() {
    $date = new DateTime();
    return $date->format("W");
}

function getCurrentWeek() {
    $dtmin = new DateTime("last sunday");
    $dtmin->modify('+1 day');
    $dtmax = clone($dtmin);
    $dtmax->modify('+6 days');
    return $dtmin->format('d/m/Y') . ' - ' . $dtmax->format('d/m/Y');
}

function getWeekValues($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('d/m/Y');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('d/m/Y');
    return $ret['week_start'] . ' - ' . $ret['week_end'];
}

function getTareas($year, $week, $user, $mysqli) {
    $days = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'];
    $arrayTareas = [];

    foreach ($days as $day) {
        $stmt = $mysqli->prepare("SELECT * FROM tareas_weeks WHERE id_user = ? AND week = ? AND year = ? AND day = ?");
        $stmt->bind_param("iiis", $user, $week, $year, $day);
        $stmt->execute();
        $resultTareas = $stmt->get_result();
        while ($row = $resultTareas->fetch_assoc()) {
            $arrayTareas[] = $row;
        }
    }

    return $arrayTareas;
}

function getHoursWeek($arrayTareas) {
    $hours = 0;
    foreach ($arrayTareas as $tarea) {
        $hours += $tarea['hours'];
    }

    return $hours;
}

if ('insert_user' == $action) {
    $name = (isset($_GET['name'])) ? $_GET['name'] : '';

    if (!empty($name)) {
        $stmt = $mysqli->prepare("INSERT INTO tareas_users (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ('insert_tarea' == $action) {
    $idUser = (isset($_GET['id_user'])) ? $_GET['id_user'] : '';
    $day = (isset($_GET['day'])) ? $_GET['day'] : '';
    $gestic = (isset($_GET['gestic'])) ? $_GET['gestic'] : '';
    $descriptionGestic = (isset($_GET['gestic_description'])) ? $_GET['gestic_description'] : '';
    $description = (isset($_GET['description'])) ? $_GET['description'] : '';
    $tarea = (isset($_GET['tarea'])) ? $_GET['tarea'] : '';
    $hourType = (isset($_GET['hour_type'])) ? $_GET['hour_type'] : '';
    $hours = (isset($_GET['hours'])) ? $_GET['hours'] : '';
    $status = (isset($_GET['status'])) ? $_GET['status'] : '';
    $percent = (isset($_GET['percent'])) ? $_GET['percent'] : '';
    $week = (isset($_GET['week'])) ? $_GET['week'] : '';
    $year = (isset($_GET['year'])) ? $_GET['year'] : '';

    if (!empty($idUser) && !empty($day)) {
        try {
            $stmt = $mysqli->prepare("INSERT INTO tareas_weeks (id_user, week, year, day, gestic, description_gestic, description, tarea, hour_type, hours, status, percent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siissssssdsi", $idUser, $week, $year, $day, $gestic, $descriptionGestic, $description, $tarea, $hourType, $hours, $status, $percent);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ('edit_tarea' == $action) {
    $tareaId = (isset($_GET['tarea_id'])) ? $_GET['tarea_id'] : '';

    if (!empty($tareaId)) {
        $day = (isset($_GET['day'])) ? $_GET['day'] : '';
        $gestic = (isset($_GET['gestic'])) ? $_GET['gestic'] : '';
        $descriptionGestic = (isset($_GET['gestic_description'])) ? $_GET['gestic_description'] : '';
        $description = (isset($_GET['description'])) ? $_GET['description'] : '';
        $tarea = (isset($_GET['tarea'])) ? $_GET['tarea'] : '';
        $hourType = (isset($_GET['hour_type'])) ? $_GET['hour_type'] : '';
        $hours = (isset($_GET['hours'])) ? $_GET['hours'] : '';
        $status = (isset($_GET['status'])) ? $_GET['status'] : '';
        $percent = (isset($_GET['percent'])) ? $_GET['percent'] : '';
        $week = (isset($_GET['week'])) ? $_GET['week'] : '';
        $year = (isset($_GET['year'])) ? $_GET['year'] : '';

        $stmt = $mysqli->prepare("UPDATE tareas_weeks SET day = ?, gestic = ?, description_gestic = ?, description = ?, tarea = ?, hour_type = ?, hours = ?, status = ?, percent = ? WHERE id = ?");
        $stmt->bind_param("ssssssdsii", $day, $gestic, $descriptionGestic, $description, $tarea, $hourType, $hours, $status, $percent, $tareaId);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ('delete_tarea' == $action) {
    if (!empty($tarea)) {
        $stmt = $mysqli->prepare("DELETE FROM tareas_weeks WHERE id = ?");
        $stmt->bind_param("i", $tarea);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ('delete_user' == $action) {
    if (!empty($user)) {
        $stmt = $mysqli->prepare("SELECT * FROM tareas_weeks WHERE id_user = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $stmt2 = $mysqli->prepare("DELETE FROM tareas_users WHERE id = ?");
            $stmt2->bind_param("i", $user);
            $stmt2->execute();
            $stmt2->close();
        }
        $stmt->close();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if ('get_tarea' == $action) {
    $tarea = (isset($_POST['id']) ? $_POST['id'] : '');

    if (!empty($tarea)) {
        $stmt = $mysqli->prepare("SELECT * FROM tareas_weeks WHERE id = ?");
        $stmt->bind_param("s", $tarea);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        echo json_encode($row, true);
        exit;
    }
}

if (!empty($user)) {
    $user = intval($user);
    $stmt = $mysqli->prepare("SELECT * FROM tareas_users WHERE id = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($result->num_rows === 0) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $userName = $row['name'];
    $userId = $row['id'];
    $stmt->close();
    $week = (isset($_GET['week'])) ? $_GET['week'] : getCurrentWeekValue();
    $year = (isset($_GET['year'])) ? $_GET['year'] : $currentYear;
    $arrayTareas = getTareas($year, $week, $userId, $mysqli);
    $totalHours = getHoursWeek($arrayTareas);
}
