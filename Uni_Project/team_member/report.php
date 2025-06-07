<?php
include('../confing/DB_connection.php');
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    $id = $_SESSION['id'];
    $user = $_SESSION['username'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <!-- fontawesome  -->
        <script src="https://kit.fontawesome.com/3e1d046fbe.js" crossorigin="anonymous"></script>
        <!-- webSite icon  -->
        <link rel="shortcut icon" href="../asset/images/icon2.jpg">
        <!-- flowbite -->
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <!-- main css  -->
        <link rel="stylesheet" href="../asset/css/inner.css">
        <title>manageEase</title>
    </head>

    <body>

        <?php include('includes/header.php') ?>
        <aside class="main-aside switch">

            <nav>
                <div class="main-item switchTxt"><i class="icon fa-solid fa-chart-line"></i><a class="switchTxt" href="dashboard.php">Dashboard</a></div>
                <div class="main-item menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-regular fa-file"></i>
                            <span>Projects</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <!-- <li><a class="switchTxt" href="add_project.php">Add Project</a></li> -->
                        <li><a class="switchTxt" href="list_project.php">List Projects</a></li>
                    </ul>
                </div>
                <div class="main-item switchTxt"><i class="icon fa-solid fa-grip"></i><a class="switchTxt" href="board.php">Board</a></div>
                <div class="main-item menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-solid fa-list-check"></i>
                            <span>Tasks</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <li><a class="switchTxt" href="list_tasks.php">List Tasks</a></li>
                    </ul>
                </div>
                <div class="main-item active switchTxt"><i class="icon fa-regular fa-rectangle-list"></i><a class="switchTxt" href="report.php">Report</a></div>
                <!-- <div class="main-item menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-solid fa-users"></i>
                            <span>Members</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <li><a class="switchTxt" href="add_memberr.php">Add Member</a></li>
                        <li><a class="switchTxt" href="list_members.php">List Members</a></li>
                    </ul>
                </div> -->
                <!-- <div class="main-item switchTxt"><i class=" icon fa-regular fa-note-sticky"></i><a class="switchTxt" href="sticky_note.php">Stiky Notes</a></div> -->
                <div class="main-item switchTxt"><i class="icon fa-solid fa-gear"></i><a class="switchTxt" href="setting.php">Setting</a></div>
                <div class="main-item switchTxt"><i class="fa-solid fa-right-from-bracket"></i><a class="switchTxt" href="logout.php">Logut</a></div>

            </nav>


        </aside>
        <article class="switch_op">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h1 class="mb-0">Project Reports</h1>
                        <div class="dropdown no-print">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="printDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-print"></i> Print Options
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="printDropdown">
                                <li><a class="dropdown-item" href="#" onclick="printAllProjects()">Print All Projects</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <?php
                                $projects_sql = "SELECT id, name FROM projects WHERE created_by = ?";
                                $stmt = $conn->prepare($projects_sql);
                                $stmt->bind_param("s", $user);
                                $stmt->execute();
                                $projects_result = $stmt->get_result();

                                while ($project = mysqli_fetch_assoc($projects_result)) {
                                    echo '<li><a class="dropdown-item" href="#" onclick="printSingleProject(' . $project['id'] . ')">Print "' . htmlspecialchars($project['name']) . '"</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php
                // استعلام لجلب جميع المشاريع الخاصة بالمستخدم فقط
                $projects_sql = "SELECT * FROM projects WHERE created_by = ? ORDER BY id DESC";
                $stmt = $conn->prepare($projects_sql);
                $stmt->bind_param("s", $user);
                $stmt->execute();
                $projects_result = $stmt->get_result();

                if (mysqli_num_rows($projects_result) > 0) {
                    while ($project = mysqli_fetch_assoc($projects_result)) {
                        $project_id = $project['id'];
                        $selectedManagers = explode(',', $project['project_managers']);
                        $selectedMembers = explode(',', $project['members']);
                ?>
                        <div class="card mb-4 printable-section" id="project-<?= $project_id ?>">
                            <div class="card-header bg-white">
                                <h2 class="mb-0"><?= htmlspecialchars($project['name']) ?></h2>
                            </div>
                            <div class="card-body">
                                <!-- Project Summary -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5 class="text-secondary mb-3">Project Details</h5>
                                        <p><strong>Description:</strong> <?= $project['description'] ? nl2br(htmlspecialchars($project['description'])) : 'No description' ?></p>
                                        <p><strong>Visibility:</strong> <?= htmlspecialchars($project['visibility']) ?></p>
                                        <p><strong>Status:</strong>
                                            <span class="badge 
                                    <?php
                                    switch ($project['status']) {
                                        case 'Not Started':
                                            echo 'bg-warning';
                                            break;
                                        case 'In Progress':
                                            echo 'bg-info';
                                            break;
                                        case 'Completed':
                                            echo 'bg-success';
                                            break;
                                        default:
                                            echo 'bg-secondary';
                                    }
                                    ?>">
                                                <?= htmlspecialchars($project['status']) ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-secondary mb-3">Timeline</h5>
                                        <p><strong>Start Date:</strong> <?= date('M d, Y', strtotime($project['start_date'])) ?></p>
                                        <p><strong>End Date:</strong> <?= date('M d, Y', strtotime($project['end_date'])) ?></p>
                                        <p><strong>Duration:</strong>
                                            <?php
                                            $start = new DateTime($project['start_date']);
                                            $end = new DateTime($project['end_date']);
                                            $diff = $start->diff($end);
                                            echo $diff->format('%a days');
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Team Members -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5 class="text-secondary mb-3">Project Managers</h5>
                                        <div class="d-flex flex-wrap gap-3">
                                            <?php foreach ($selectedManagers as $manager):
                                                $manager = trim($manager);
                                                $user_sql = "SELECT profile_picture FROM users WHERE username = ?";
                                                $stmt = $conn->prepare($user_sql);
                                                $stmt->bind_param("s", $manager);
                                                $stmt->execute();
                                                $user_result = $stmt->get_result();
                                                $user = $user_result->fetch_assoc();
                                            ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                                                        class="rounded-circle"
                                                        style="width: 40px; height: 40px; object-fit: cover;"
                                                        alt="<?= htmlspecialchars($manager) ?>">
                                                    <span><?= htmlspecialchars($manager) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-secondary mb-3">Team Members</h5>
                                        <div class="d-flex flex-wrap gap-3">
                                            <?php
                                            // جلب الأعضاء الفريدين فقط
                                            $unique_members = array_unique(array_map('trim', $selectedMembers));
                                            foreach ($unique_members as $member):
                                                $member = trim($member);
                                                if (empty($member)) continue;

                                                $user_sql = "SELECT profile_picture FROM users WHERE username = ?";
                                                $stmt = $conn->prepare($user_sql);
                                                $stmt->bind_param("s", $member);
                                                $stmt->execute();
                                                $user_result = $stmt->get_result();
                                                $user = $user_result->fetch_assoc();
                                            ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                                                        class="rounded-circle"
                                                        style="width: 40px; height: 40px; object-fit: cover;"
                                                        alt="<?= htmlspecialchars($member) ?>">
                                                    <span><?= htmlspecialchars($member) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tasks Report -->
                                <h5 class="text-secondary mb-3">Tasks Summary</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>List</th>
                                                <th>Task</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Assigned To</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // استعلام لجلب جميع المهام الخاصة بالمشروع
                                            $tasks_sql = "SELECT 
                                            l.name as list_name,
                                            c.title as task_title,
                                            cd.status,
                                            cd.start_date,
                                            cd.due_date,
                                            GROUP_CONCAT(DISTINCT cm.username SEPARATOR ', ') as assigned_to
                                        FROM lists l
                                        JOIN cards c ON l.id = c.list_id
                                        LEFT JOIN card_details cd ON c.id = cd.card_id
                                        LEFT JOIN card_members cm ON c.id = cm.card_id
                                        WHERE l.project_id = ?
                                        GROUP BY c.id
                                        ORDER BY l.name, c.title";
                                            $stmt = $conn->prepare($tasks_sql);
                                            $stmt->bind_param("i", $project_id);
                                            $stmt->execute();
                                            $tasks_result = $stmt->get_result();

                                            if (mysqli_num_rows($tasks_result) > 0) {
                                                while ($task = mysqli_fetch_assoc($tasks_result)) {
                                            ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($task['list_name']) ?></td>
                                                        <td><?= htmlspecialchars($task['task_title']) ?></td>
                                                        <td>
                                                            <span class="badge 
                                                <?php
                                                    switch ($task['status']) {
                                                        case 'To Do':
                                                            echo 'bg-secondary';
                                                            break;
                                                        case 'In Progress':
                                                            echo 'bg-primary';
                                                            break;
                                                        case 'Review':
                                                            echo 'bg-warning';
                                                            break;
                                                        case 'Done':
                                                            echo 'bg-success';
                                                            break;
                                                        default:
                                                            echo 'bg-secondary ';
                                                    }
                                                ?>">
                                                                <?= $task['status'] ? htmlspecialchars($task['status']) : 'To Do' ?>
                                                            </span>
                                                        </td>
                                                        <td><?= $task['start_date'] ? date('M d, Y', strtotime($task['start_date'])) : '-' ?></td>
                                                        <td><?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : '-' ?></td>
                                                        <td><?= $task['assigned_to'] ? htmlspecialchars($task['assigned_to']) : 'Unassigned' ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No tasks found for this project</td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Progress Summary -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <h5 class="text-secondary mb-3">Progress Overview</h5>
                                        <?php
                                        // حساب تقدم المشروع
                                        $progress_sql = "SELECT 
                                    COUNT(*) as total_tasks,
                                    SUM(CASE WHEN cd.status = 'Done' THEN 1 ELSE 0 END) as completed_tasks
                                FROM cards c
                                JOIN lists l ON c.list_id = l.id
                                LEFT JOIN card_details cd ON c.id = cd.card_id
                                WHERE l.project_id = ?";
                                        $stmt = $conn->prepare($progress_sql);
                                        $stmt->bind_param("i", $project_id);
                                        $stmt->execute();
                                        $progress_result = $stmt->get_result();
                                        $progress = $progress_result->fetch_assoc();

                                        $total_tasks = $progress['total_tasks'];
                                        $completed_tasks = $progress['completed_tasks'];
                                        $progress_percent = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0;
                                        ?>
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar bg-success"
                                                role="progressbar"
                                                style="width: <?= $progress_percent ?>%"
                                                aria-valuenow="<?= $progress_percent ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                <?= $progress_percent ?>%
                                            </div>
                                        </div>
                                        <p><?= $completed_tasks ?> of <?= $total_tasks ?> tasks completed</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-secondary mb-3">Status Distribution</h5>
                                        <?php
                                        // توزيع الحالات
                                        $status_sql = "SELECT 
                                    cd.status,
                                    COUNT(*) as count
                                FROM cards c
                                JOIN lists l ON c.list_id = l.id
                                LEFT JOIN card_details cd ON c.id = cd.card_id
                                WHERE l.project_id = ?
                                GROUP BY cd.status";
                                        $stmt = $conn->prepare($status_sql);
                                        $stmt->bind_param("i", $project_id);
                                        $stmt->execute();
                                        $status_result = $stmt->get_result();
                                        ?>
                                        <ul class="list-group">
                                            <?php
                                            while ($status = mysqli_fetch_assoc($status_result)) {
                                                $status_name = $status['status'] ?: 'Not Set';
                                            ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= htmlspecialchars($status_name) ?>
                                                    <span class="badge bg-primary rounded-pill"><?= $status['count'] ?></span>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-muted text-end">
                                Report generated on <?= date('M d, Y H:i') ?>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-info">
                        No projects found for this user.
                    </div>
                <?php
                }
                ?>
            </div>
        </article>

        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .printable-section,
                .printable-section * {
                    visibility: visible;
                }

                .printable-section {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: 0;
                    padding: 0;
                }

                .no-print {
                    display: none !important;
                }
            }
        </style>

        <script>
            function printAllProjects() {
                window.print();
            }

            function printSingleProject(projectId) {
                // Hide all printable sections first
                document.querySelectorAll('.printable-section').forEach(section => {
                    section.style.display = 'none';
                });

                // Show only the selected project
                const projectToPrint = document.getElementById('project-' + projectId);
                if (projectToPrint) {
                    projectToPrint.style.display = 'block';
                    window.print();

                    // After printing, show all projects again
                    setTimeout(() => {
                        document.querySelectorAll('.printable-section').forEach(section => {
                            section.style.display = 'block';
                        });
                    }, 500);
                }
            }
        </script>
        <script src="../asset/js/dark_moode.js"></script>
        <script src="../asset/js/aside_links.js"></script>
    </body>

    </html>
<?php

} else {
    header('Location:../auth/logIn.php');
    exit();
}
?>