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
        <link rel="stylesheet" href="../asset/css/dashboard.css">
        <link rel="stylesheet" href="../asset/css/notification.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>manageEase</title>
    </head>

    <body>

        <?php include('includes/header.php') ?>
        <aside class="main-aside switch">

            <nav>
                <div class="main-item active switchTxt"><i class="icon fa-solid fa-chart-line"></i><a class="switchTxt" href="dashboard.php">Dashboard</a></div>
                <div class="main-item menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-regular fa-file"></i>
                            <span>Projects</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <li><a class="switchTxt" href="add_project.php">Add Project</a></li>
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
                <div class="main-item switchTxt"><i class="icon fa-regular fa-rectangle-list"></i><a class="switchTxt" href="report.php">Report</a></div>
                <div class="main-item menu switchTxt">
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
                </div>
                <!-- <div class="main-item switchTxt"><i class=" icon fa-regular fa-note-sticky"></i><a class="switchTxt" href="sticky_note.php">Stiky Notes</a></div> -->
                <div class="main-item switchTxt"><i class="icon fa-solid fa-gear"></i><a class="switchTxt" href="setting.php">Setting</a></div>
                <div class="main-item switchTxt"><i class="fa-solid fa-right-from-bracket"></i><a class="switchTxt" href="logout.php">Logut</a></div>

            </nav>


        </aside>
        <article class="switch_op">
            <div class="dashboard-container">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h1>Welcome, <?php echo $user; ?>!</h1>
                    <p>Here's what's happening with your projects today</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <!-- Projects Card -->
                    <div class="stat-card border-bottom border-primary border-start ">
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram text-primary"></i>
                        </div>
                        <div class="stat-info text-primary">
                            <h3>My Projects</h3>
                            <?php
                            $project_count = mysqli_query($conn, "SELECT COUNT(*) FROM projects WHERE created_by = '$user'");
                            $project_count = mysqli_fetch_array($project_count)[0];
                            ?>
                            <span class="stat-number"><?php echo $project_count; ?></span>
                        </div>
                    </div>

                    <!-- Team Members Card -->
                    <div class="stat-card border-bottom border-success border-start">
                        <div class="stat-icon">
                            <i class="fas fa-users  text-success"></i>
                        </div>
                        <div class="stat-info  text-success">
                            <h3>Team Members</h3>
                            <?php
                            $member_count = mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE added_by = $id");
                            $member_count = mysqli_fetch_array($member_count)[0];
                            ?>
                            <span class="stat-number"><?php echo $member_count; ?></span>
                        </div>
                    </div>

                    <!-- Tasks Card -->
                    <div class="stat-card border-bottom border-warning border-start">
                        <div class="stat-icon">
                            <i class="fas fa-tasks text-warning"></i>
                        </div>
                        <div class="stat-info text-warning">
                            <h3>Total Tasks</h3>
                            <?php
                            $task_count = mysqli_query(
                                $conn,
                                "SELECT COUNT(cards.id) 
                         FROM cards 
                         JOIN lists ON cards.list_id = lists.id 
                         JOIN projects ON lists.project_id = projects.id 
                         WHERE projects.created_by = '$user'"
                            );
                            $task_count = mysqli_fetch_array($task_count)[0];
                            ?>
                            <span class="stat-number"><?php echo $task_count; ?></span>
                        </div>
                    </div>

                    <!-- Completed Tasks Card -->
                    <div class="stat-card border-bottom border-info border-start">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle text-info"></i>
                        </div>
                        <div class="stat-info text-info">
                            <h3>Completed Tasks</h3>
                            <?php
                            $completed_count = mysqli_query(
                                $conn,
                                "SELECT COUNT(cards.id) 
                         FROM cards 
                         JOIN card_details ON cards.id = card_details.card_id 
                         JOIN lists ON cards.list_id = lists.id 
                         JOIN projects ON lists.project_id = projects.id 
                         WHERE projects.created_by = '$user' AND card_details.status = 'Done'"
                            );
                            $completed_count = mysqli_fetch_array($completed_count)[0];
                            ?>
                            <span class="stat-number"><?php echo $completed_count; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Recent Projects Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-project-diagram"></i> My Recent Projects</h2>
                        <a href="list_project.php" class="view-all">View All</a>
                    </div>

                    <div class="projects-grid">
                        <?php
                        $projects = mysqli_query(
                            $conn,
                            "SELECT * FROM projects 
                     WHERE created_by = '$user' 
                     ORDER BY created_at DESC 
                     LIMIT 4"
                        );

                        while ($project = mysqli_fetch_assoc($projects)):
                            // Calculate progress
                            $total_tasks = mysqli_query(
                                $conn,
                                "SELECT COUNT(cards.id) 
                         FROM cards 
                         JOIN lists ON cards.list_id = lists.id 
                         WHERE lists.project_id = {$project['id']}"
                            );
                            $total_tasks = mysqli_fetch_array($total_tasks)[0];

                            $completed_tasks = mysqli_query(
                                $conn,
                                "SELECT COUNT(cards.id) 
                         FROM cards 
                         JOIN card_details ON cards.id = card_details.card_id 
                         JOIN lists ON cards.list_id = lists.id 
                         WHERE lists.project_id = {$project['id']} AND card_details.status = 'Done'"
                            );
                            $completed_tasks = mysqli_fetch_array($completed_tasks)[0];

                            $progress = ($total_tasks > 0) ? round(($completed_tasks / $total_tasks) * 100) : 0;
                        ?>
                            <div class="project-card">
                                <div class="project-header">
                                    <h3><?php echo $project['name']; ?></h3>
                                    <span class="badge <?php echo strtolower(str_replace(' ', '-', $project['status'])); ?>">
                                        <?php echo $project['status']; ?>
                                    </span>
                                </div>
                                <p class="project-dates">
                                    <?php echo date('M d, Y', strtotime($project['start_date'])); ?> -
                                    <?php echo date('M d, Y', strtotime($project['end_date'])); ?>
                                </p>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                    <span><?php echo $progress; ?>%</span>
                                </div>
                                <div class="project-meta">
                                    <span><i class="fas fa-tasks"></i> <?php echo $total_tasks; ?> Tasks</span>
                                    <a href="board.php?project=<?php echo $project['id']; ?>" class="view-project">View Project</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Recent Team Members Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-users"></i> Recently Added Team Members</h2>
                        <a href="list_members.php" class="view-all">View All</a>
                    </div>

                    <div class="members-grid">
                        <?php
                        $members = mysqli_query(
                            $conn,
                            "SELECT * FROM users 
                     WHERE added_by = $id 
                     ORDER BY created_at DESC 
                     LIMIT 6"
                        );

                        while ($member = mysqli_fetch_assoc($members)):
                        ?>
                            <div class="member-card">
                                <div class="member-avatar">
                                    <?php if ($member['profile_picture']): ?>
                                        <img src="<?php echo $member['profile_picture']; ?>" alt="<?php echo $member['username']; ?>">
                                    <?php else: ?>
                                        <div class="avatar-initials">
                                            <?php
                                            $names = explode(' ', $member['username']);
                                            $initials = substr($names[0], 0, 1);
                                            if (count($names) > 1) {
                                                $initials .= substr(end($names), 0, 1);
                                            }
                                            echo strtoupper($initials);
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="member-info">
                                    <h4><?php echo $member['username']; ?></h4>
                                    <p><?php echo $member['job'] ? $member['job'] : 'No job specified'; ?></p>
                                    <span class="member-role <?php echo strtolower(str_replace(' ', '-', $member['role'])); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $member['role'])); ?>
                                    </span>
                                </div>
                                <div class="member-contact">
                                    <a href="mailto:<?php echo $member['email']; ?>"><i class="fas fa-envelope"></i></a>
                                    <a href="tel:<?php echo $member['phone']; ?>"><i class="fas fa-phone"></i></a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Upcoming Tasks Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-calendar-alt"></i> Upcoming Tasks</h2>
                        <a href="list_tasks.php" class="view-all">View All</a>
                    </div>

                    <div class="tasks-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Project</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tasks = mysqli_query(
                                    $conn,
                                    "SELECT cards.title as task_title, projects.name as project_name, 
                                    card_details.due_date, card_details.status, 
                                    lists.name as list_name
                             FROM cards 
                             JOIN card_details ON cards.id = card_details.card_id 
                             JOIN lists ON cards.list_id = lists.id 
                             JOIN projects ON lists.project_id = projects.id 
                             WHERE projects.created_by = '$user' 
                             AND card_details.due_date >= CURDATE() 
                             ORDER BY card_details.due_date ASC 
                             LIMIT 5"
                                );

                                while ($task = mysqli_fetch_assoc($tasks)):
                                ?>
                                    <tr>
                                        <td><?php echo $task['task_title']; ?></td>
                                        <td><?php echo $task['project_name']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($task['due_date'])); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $task['status'])); ?>">
                                                <?php echo $task['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            // Determine priority based on due date proximity
                                            $due_date = new DateTime($task['due_date']);
                                            $today = new DateTime();
                                            $interval = $today->diff($due_date);
                                            $days_left = $interval->days;

                                            if ($days_left <= 2) {
                                                echo '<span class="priority-badge high">High</span>';
                                            } elseif ($days_left <= 5) {
                                                echo '<span class="priority-badge medium">Medium</span>';
                                            } else {
                                                echo '<span class="priority-badge low">Low</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>
        <script>
            $(document).ready(function() {
                // عرض/إخفاء الإشعارات
                $(document).on('click', '#notifications-icon', function(e) {
                    e.stopPropagation();
                    var container = $('#notifications-container');
                    container.toggleClass('active');

                    if (container.hasClass('active')) {
                        container.load('notifications.php', function(response, status, xhr) {
                            if (status == "error") {
                                console.log("Error loading notifications: " + xhr.status + " " + xhr.statusText);
                            }
                        });
                    }
                });

                // إغلاق الإشعارات عند النقر خارجها
                $(document).click(function(e) {
                    if (!$(e.target).closest('#notifications-container, #notifications-icon').length) {
                        $('#notifications-container').removeClass('active');
                    }
                });
            });
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