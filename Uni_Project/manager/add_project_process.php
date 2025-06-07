<?php
include('../confing/DB_connection.php');
include('notification_functions.php');
session_start();
$flag = 0;
// add project 
if (isset($_POST['add_project'])) {
    $project_name = htmlspecialchars($_POST['project_name']);
    $start_date = htmlspecialchars($_POST['start_date']);
    $end_date = htmlspecialchars($_POST['end_date']);
    $managers = isset($_POST['manager_ids']) ? implode(",", array_map("htmlspecialchars", $_POST['manager_ids'])) : null;
    $members = isset($_POST['member_ids']) ? implode(",", array_map("htmlspecialchars", $_POST['member_ids'])) : null;
    $visibility = htmlspecialchars($_POST['visibility']); // Public أو Private
    $description = htmlspecialchars($_POST['description']);
    $user = $_SESSION['username'];
    $created_by = $user;

    // validation for project name 
    if (empty($project_name)) {
        $_SESSION['projecterror']  = "Please Enter Project Name <br>";
        $flag = 1;
    } elseif (strlen($project_name) < 4) {
        $_SESSION['projecterror']  = " Must not be less than 4 characters<br>";
        $flag = 1;
    }

    // validation for start date 
    if (empty($start_date)) {
        $_SESSION['startDateError']  = "Please Enter Start Date  <br>";
        $flag = 1;
    }

    // validation for end date 
    if (empty($end_date)) {
        $_SESSION['endDateError']  = "Please Enter End Date  <br>";
        $flag = 1;
    }

    // validation for member 
    if (empty($members)) {
        $_SESSION['memberError']  = "Please  Selecte Members  <br>";
        $flag = 1;
    }
    // validation for manager
    if (empty($managers)) {
        $_SESSION['managerError']  = "Please  Selecte Manager  <br>";
        $flag = 1;
    }

    // intial value fo description
    if (empty($description)) {
        $description = " There is no description  <br>";
    }

    if ($flag == 0) {
        $sql = "INSERT INTO projects (name,start_date,end_date,visibility,description,created_by,project_managers,members) VALUES(?,?,?,?,?,?,?,?) ";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $project_name, $start_date, $end_date, $visibility, $description, $created_by, $managers,  $members);


        if (mysqli_stmt_execute($stmt)) {

            $project_id = $conn->insert_id;
            $triggered_by = $_SESSION['id'];
            // إرسال إشعارات لجميع الأعضاء المضافين
            $members = explode(',', $members);
            foreach ($members as $member) {
                notifyMemberAdded(trim($member), $project_id,  $triggered_by);
            }

            $_SESSION['messege'] = "Project Created Successfully";
            header("Location:add_project.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['oldProjectName'] = $project_name = htmlspecialchars($_POST['project_name']);
        $_SESSION['oldStartDate'] =  $start_date = htmlspecialchars($_POST['start_date']);
        $_SESSION['oldEndDate'] = $end_date = htmlspecialchars($_POST['end_date']);
        $_SESSION['managers'] = $managers = isset($_POST['manager_ids']) ? implode(",", array_map("htmlspecialchars", $_POST['manager_ids'])) : null;
        $_SESSION['members'] = isset($_POST['member_ids']) ? implode(",", array_map("htmlspecialchars", $_POST['member_ids'])) : null;
        $_SESSION['visibility'] = htmlspecialchars($_POST['visibility']);
        $_SESSION['description'] =  htmlspecialchars($_POST['description']);



        header("location:add_project.php");
    }
}


// edit or update project 
if (isset($_POST['edit_project'])) {
    $project_name = htmlspecialchars($_POST['project_name']);
    $start_date = htmlspecialchars($_POST['start_date']);
    $end_date = htmlspecialchars($_POST['end_date']);
    $managers = isset($_POST['manager_ids']) ? implode(",", array_map("htmlspecialchars", $_POST['manager_ids'])) : null;
    $members = isset($_POST['member_ids']) ? implode(",", array_map("htmlspecialchars", $_POST['member_ids'])) : null;
    $visibility = htmlspecialchars($_POST['visibility']); // Public أو Private
    $description = htmlspecialchars($_POST['description']);
    $user = $_SESSION['username'];
    $created_by = $user;
    $id = htmlspecialchars($_POST['id']);


    // validation for project name 
    if (empty($project_name)) {
        $_SESSION['projecterror']  = "Please Enter Project Name <br>";
        $flag = 1;
    } elseif (strlen($project_name) < 4) {
        $_SESSION['projecterror']  = " Must not be less than 4 characters<br>";
        $flag = 1;
    }

    // validation for start date 
    if (empty($start_date)) {
        $_SESSION['startDateError']  = "Please Enter Start Date  <br>";
        $flag = 1;
    }

    // validation for end date 
    if (empty($end_date)) {
        $_SESSION['endDateError']  = "Please Enter End Date  <br>";
        $flag = 1;
    }

    // validation for member 
    if (empty($members)) {
        $_SESSION['memberError']  = "Please  Selecte Members  <br>";
        $flag = 1;
    }
    // validation for manager
    if (empty($managers)) {
        $_SESSION['managerError']  = "Please  Selecte Manager  <br>";
        $flag = 1;
    }

    // intial value fo description
    if (empty($description)) {
        $description = " There is no description  <br>";
    }

    if ($flag == 0) {
        $sql = "UPDATE projects SET name = ?, start_date = ?, end_date = ?, visibility = ? , description = ?
        ,created_by=?,project_managers=? ,members=? WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $project_name, $start_date, $end_date, $visibility, $description, $created_by, $managers,  $members, $id);


        if (mysqli_stmt_execute($stmt)) {

            $project_id = $conn->insert_id;
            $triggered_by = $_SESSION['id'];
            // إرسال إشعارات لجميع الأعضاء المضافين
            $members = explode(',', $members);
            foreach ($members as $member) {
                notifyMemberAdded(trim($member), $project_id,  $triggered_by);
            }
            $_SESSION['messege'] = "Project Edited Successfully";
            header("Location:list_project.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        header("location:edit_project.php?id=$id");
    }
}


//delete project
if (isset($_POST['delete_project'])) {
    $project_id = htmlspecialchars($_POST['delete_project']);

    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    if ($stmt->execute()) {
        $_SESSION['messege'] = "Project deleted successfully.";
    } else {
        $_SESSION['messege'] = "Failed to delete the project.";
    }
    header("Location: list_project.php");
    exit();
}
