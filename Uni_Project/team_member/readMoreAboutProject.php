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
        <!-- tailwind  -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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

    <body style="width:100vw !important ; overflow-x: hidden;">

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
                        <li class="active"><a class="switchTxt" href="list_project.php">List Projects</a></li>
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
                <!-- <div class="main-item  switchTxt"><i class="icon fa-regular fa-rectangle-list"></i><a class="switchTxt" href="report.php">Report</a></div> -->
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
        <?php
        if (isset($_GET['id'])) {
            $Project_id = mysqli_real_escape_string($conn, $_GET['id']);
            $sql = "SELECT * FROM projects WHERE id='$Project_id'";
            $query_run = mysqli_query($conn, $sql);
            if (mysqli_num_rows($query_run) > 0) {
                $project = mysqli_fetch_array($query_run);
                $selectedManagers = explode(',', $project['project_managers']);
                $selectedMembers = explode(',', $project['members']);

        ?>
                <article class="switch_op ">
                    <div class="container">
                        <div class="row callout callout-info">
                            <div class="col-12 switch_op mt-3">

                                <h1 class="mb-4 text-3xl font-extrabold text-gray-200 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-gray-600 from-sky-600">More About Porject</span> </h1>

                            </div>
                        </div>
                        <div class="row  border border-gray-200 rounded-lg">
                            <div class="switch col-md-6 mt-1  p-3">
                                <div class="mb-3">
                                    <h5 class="text-secondary mb-2">Project Name : </h5>
                                    <p><span class="underline underline-offset-8 decoration-2 decoration-blue-800"><?= $project['name']; ?></span></p>
                                </div>
                                <div class="mb-3">
                                    <h5 class="text-secondary mb-2">Visibility: </h5>
                                    <p><span class="underline underline-offset-8 decoration-2 decoration-blue-800"><?= $project['visibility']; ?></span></p>
                                </div>

                                <div>
                                    <h5 class="text-secondary">Description :</h5>
                                    <p class="overflow-auto "><span class="underline underline-offset-8 decoration-2 decoration-blue-800"><?= $project['description']; ?></span></p>
                                </div>
                            </div>
                            <div class="switch col-md-6 mt-1 p-3">
                                <div class="mb-3">
                                    <h5 class="text-secondary">Start Date : </h5>
                                    <p><span class="underline underline-offset-8 decoration-2 decoration-blue-800"><?= $project['start_date']; ?></span></p>

                                </div>
                                <div class="mb-3">
                                    <h5 class="text-secondary">End Date : </h5>
                                    <p><span class="underline underline-offset-8 decoration-2 decoration-blue-800"><?= $project['end_date']; ?></span></p>

                                </div>
                                <div class="mb-3">
                                    <!-- <h5 class="text-secondary">Status : </h5> -->
                                    <?php
                                    // استعلام لفحص حالة الكروت في المشروع
                                    $cards_status_sql = "SELECT cd.status 
                    FROM cards c
                    JOIN lists l ON c.list_id = l.id
                    LEFT JOIN card_details cd ON c.id = cd.card_id
                    WHERE l.project_id = '$Project_id'";
                                    $cards_status_result = mysqli_query($conn, $cards_status_sql);

                                    $total_cards = 0;
                                    $in_progress_cards = 0;
                                    $done_cards = 0;
                                    $has_cards = false;

                                    if (mysqli_num_rows($cards_status_result) > 0) {
                                        $has_cards = true;
                                        while ($card_status = mysqli_fetch_assoc($cards_status_result)) {
                                            $total_cards++;
                                            if ($card_status['status'] == 'In Progress') {
                                                $in_progress_cards++;
                                            } elseif ($card_status['status'] == 'Done') {
                                                $done_cards++;
                                            }
                                        }
                                    }

                                    // تحديد حالة المشروع بناءً على الكروت
                                    $project_status = $project['status']; // الحالة الأساسية من قاعدة البيانات

                                    if ($has_cards) {
                                        if ($done_cards == $total_cards && $total_cards > 0) {
                                            // إذا كانت جميع الكروت مكتملة
                                            $project_status = 'Completed';
                                        } elseif ($in_progress_cards > 0) {
                                            // إذا كان هناك كروت قيد التنفيذ
                                            $project_status = 'In Progress';
                                        } elseif ($project_status == 'Not Started' && $total_cards > 0) {
                                            // إذا كان هناك كروت ولكن لم يبدأ العمل عليها بعد
                                            $project_status = 'Started';
                                        }

                                        // تحديث حالة المشروع في قاعدة البيانات إذا تغيرت
                                        if ($project_status != $project['status']) {
                                            $update_sql = "UPDATE projects SET status = '$project_status' WHERE id = '$Project_id'";
                                            mysqli_query($conn, $update_sql);
                                            $project['status'] = $project_status; // تحديث المتغير المحلي
                                        }
                                    }
                                    ?>

                                    <!-- عرض حالة المشروع مع الألوان المناسبة -->
                                    <div class="mb-3">
                                        <h5 class="text-secondary">Status : </h5>
                                        <?php
                                        switch ($project_status) {
                                            case 'Not Started':
                                                echo "<p><span class='badge bg-warning'>{$project_status}</span></p>";
                                                break;
                                            case 'Started':
                                                echo "<p><span class='badge bg-info'>{$project_status}</span></p>";
                                                break;
                                            case 'In Progress':
                                                echo "<p><span class='badge bg-primary'>{$project_status}</span></p>";
                                                break;
                                            case 'Over Due':
                                                echo "<p><span class='badge bg-danger'>{$project_status}</span></p>";
                                                break;
                                            case 'Completed':
                                                echo "<p><span class='badge bg-success'>{$project_status}</span></p>";
                                                break;
                                            default:
                                                echo "<p><span class='badge bg-secondary'>{$project_status}</span></p>";
                                        }
                                        ?>

                                        <?php if ($has_cards): ?>
                                            <small class="text-muted">
                                                Tasks: <?= $done_cards ?> done of <?= $total_cards ?> total
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-secondary">Project Manager : </h5>
                                    <?php foreach ($selectedManagers as $manager):
                                        $manager = trim($manager); // إزالة الفراغات
                                        $sql = "SELECT profile_picture FROM users WHERE username = '$manager'";
                                        $query_run = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            $pic = mysqli_fetch_array($query_run);
                                            $profilePic = $pic['profile_picture'];
                                        }
                                    ?>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img src="<?= $profilePic ?>" alt="<?= $manager ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            <span><?= ucwords($manager) ?></span>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- قسم الأعضاء -->
                            <div class="switch col-md-4 border border-gray-200 rounded-lg mt-1 p-3">
                                <h5 class="text-secondary mb-3">Project Members:</h5>
                                <div class="d-flex flex-row flex-wrap gap-3">
                                    <?php
                                    // جلب أعضاء المشروع من قائمة الأعضاء في جدول المشاريع
                                    $project_members = array_unique($selectedMembers); // إزالة التكرارات
                                    foreach ($project_members as $member):
                                        $member = trim($member);
                                        $sql = "SELECT profile_picture, role FROM users WHERE username = '$member'";
                                        $query_run = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($query_run)) {
                                            $user_data = mysqli_fetch_assoc($query_run);
                                            $profilePic = $user_data['profile_picture'];
                                            $role = $user_data['role'];
                                    ?>
                                            <div class="d-flex flex-column align-items-center text-center" style="width: 80px;">
                                                <img src="<?= $profilePic ?>" alt="<?= $member ?>" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                                <small class="switch text-muted mt-1"><?= ucwords($member) ?></small>

                                            </div>
                                    <?php }
                                    endforeach; ?>
                                </div>
                            </div>


                            <div class="switch col-md-8 border border-gray-200 rounded-lg mt-1 p-3">
                                <h5 class="text-secondary mb-3">Project Tasks:</h5>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task Title</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">List</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <?php
                                            // استعلام لجلب جميع القوائم الخاصة بالمشروع
                                            $lists_sql = "SELECT * FROM lists WHERE project_id='$Project_id'";
                                            $lists_result = mysqli_query($conn, $lists_sql);

                                            if (mysqli_num_rows($lists_result) > 0) {
                                                while ($list = mysqli_fetch_assoc($lists_result)) {
                                                    $list_id = $list['id'];

                                                    // استعلام لجلب جميع الكروت الخاصة بالقائمة الحالية
                                                    $cards_sql = "SELECT * FROM cards WHERE list_id='$list_id'";
                                                    $cards_result = mysqli_query($conn, $cards_sql);

                                                    if (mysqli_num_rows($cards_result) > 0) {
                                                        while ($card = mysqli_fetch_assoc($cards_result)) {
                                                            $card_id = $card['id'];

                                                            // جلب تفاصيل الكارت
                                                            $details_sql = "SELECT * FROM card_details WHERE card_id='$card_id'";
                                                            $details_result = mysqli_query($conn, $details_sql);
                                                            $details = mysqli_num_rows($details_result) > 0 ? mysqli_fetch_assoc($details_result) : null;

                                                            // جلب أعضاء الكارت
                                                            $members_sql = "SELECT username FROM card_members WHERE card_id='$card_id'";
                                                            $members_result = mysqli_query($conn, $members_sql);
                                            ?>
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                    <?= htmlspecialchars($card['title']) ?>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                                    <?= htmlspecialchars($list['name']) ?>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                                    <?php if ($details && $details['status']): ?>
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php
                                                                        switch ($details['status']) {
                                                                            case 'To Do':
                                                                                echo 'bg-gray-100 text-gray-800';
                                                                                break;
                                                                            case 'In Progress':
                                                                                echo 'bg-blue-100 text-blue-800';
                                                                                break;
                                                                            case 'Review':
                                                                                echo 'bg-yellow-100 text-yellow-800';
                                                                                break;
                                                                            case 'Done':
                                                                                echo 'bg-green-100 text-green-800';
                                                                                break;
                                                                            default:
                                                                                echo 'bg-gray-100 text-gray-800';
                                                                        }
                                                ?>">
                                                                            <?= $details['status'] ?>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                            To Do
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                                    <div class="flex flex-wrap gap-1">
                                                                        <?php
                                                                        if (mysqli_num_rows($members_result) > 0) {
                                                                            while ($member = mysqli_fetch_assoc($members_result)) {
                                                                                // جلب صورة العضو
                                                                                $user_sql = "SELECT profile_picture FROM users WHERE username='" . $member['username'] . "'";
                                                                                $user_result = mysqli_query($conn, $user_sql);
                                                                                $user = mysqli_fetch_assoc($user_result);
                                                                        ?>
                                                                                <div class="flex items-center" title="<?= htmlspecialchars($member['username']) ?>">
                                                                                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                                                                                        class="w-6 h-6 rounded-full object-cover border border-gray-200"
                                                                                        alt="<?= htmlspecialchars($member['username']) ?>">
                                                                                </div>
                                                                        <?php
                                                                            }
                                                                        } else {
                                                                            echo '<span class="text-gray-400">No members</span>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">
                                                                No tasks found in list "<?= htmlspecialchars($list['name']) ?>"
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">
                                                        No lists found for this project
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    </div>
            <?php }
        } ?>
                </article>

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