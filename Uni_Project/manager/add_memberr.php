<?php
include('../confing/DB_connection.php');
session_start();
session_regenerate_id(true); // تم إضافة هذا السطر لتحديث معرف الجلسة بشكل دوري لتحسين الأمان ضد هجمات

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$username = isset($_SESSION['old_username']) ? $_SESSION['old_username'] : '';
$email = isset($_SESSION['old_email']) ? $_SESSION['old_email'] : '';
$job = isset($_SESSION['old_job']) ? $_SESSION['old_job'] : '';
$phone = isset($_SESSION['old_phone']) ? $_SESSION['old_phone'] : '';
$password = isset($_SESSION['old_password']) ? $_SESSION['old_password'] : '';
$confirm_password = isset($_SESSION['old_confirm_password']) ? $_SESSION['old_confirm_password'] : '';

unset($_SESSION['old_username'], $_SESSION['old_email'], $_SESSION['old_job'], $_SESSION['old_phone'], $_SESSION['old_password'], $_SESSION['old_confirm_password']);

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
        <link rel="shortcut icon" type="x-icon" href="../asset/images/favicon.ico">
        <!-- flowbite -->
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <!-- main css  -->
        <link rel="stylesheet" href="../asset/css/inner.css">
        <link rel="stylesheet" href="../asset/css/add_member.css">
        <title>manageEase</title>
    </head>

    <body style="overflow-x: hidden;">

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
                <div class="main-item active menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-solid fa-users"></i>
                            <span>Members</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <li class="active"><a class="switchTxt" href="add_memberr.php">Add Member</a></li>
                        <li><a class="switchTxt" href="list_members.php">List Members</a></li>
                    </ul>
                </div>
                <!-- <div class="main-item switchTxt"><i class=" icon fa-regular fa-note-sticky"></i><a class="switchTxt" href="sticky_note.php">Stiky Notes</a></div> -->
                <div class="main-item switchTxt"><i class="icon fa-solid fa-gear"></i><a class="switchTxt" href="setting.php">Setting</a></div>
                <div class="main-item switchTxt"><i class="fa-solid fa-right-from-bracket"></i><a class="switchTxt" href="logout.php">Logut</a></div>
            </nav>


        </aside>
        <article class="switch_op">
            <div class="branch-header">
                <h3>New Member</h3>
            </div>
            <div class="container switch">
                <div class="row">
                    <div class="col-6">
                        <i class="fa-solid fa-user-plus shape"></i>
                        <div class="form-container">
                            <form action="add_member_process.php" method="POST">
                                <?php if (isset($_SESSION['messege'])) : ?>
                                    <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                                        <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                                        <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                                        <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                                    </div>
                                <?php unset($_SESSION['messege']);
                                endif; ?>
                                <!-- CSRF Token Hidden Field -->
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="line">
                                    <div class="input-fulid">
                                        <label><i class="fa-solid fa-user"></i> Username</label>
                                        <br>
                                        <input type="text" name="username"
                                            value="<?= htmlspecialchars(isset($username) ? $username : '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php
                                        if (isset($_SESSION['usererror'])): ?>
                                            <p class="error"><?= $_SESSION['usererror'] ?> </p>
                                        <?php unset($_SESSION['usererror']);
                                        endif; ?>
                                    </div>
                                    <div class="input-fulid">
                                        <label><i class="fa-solid fa-envelope"></i> Email</label>
                                        <br>
                                        <input type="text" name="email" value="<?= htmlspecialchars(isset($email) ? $email  : '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?php
                                        if (isset($_SESSION['email_err'])): ?>
                                            <p class="error"><?= $_SESSION['email_err'] ?> </p>
                                        <?php unset($_SESSION['email_err']);
                                        endif; ?>
                                    </div>
                                </div>
                                <div class="line switch">
                                    <div class="input-fulid">
                                        <label><i class="fa-solid fa-briefcase"></i> Job</label> <!-- NEW NEW NEW-->
                                        <br>
                                        <input type="text" placeholder="Optional" name="job" value="<?= htmlspecialchars(isset($job) ? $job : '', ENT_QUOTES, 'UTF-8') ?>">
                                    </div>
                                    <div class="input-fulid">
                                        <label><i class="fa-solid fa-phone"></i>Phone </label>
                                        <br>
                                        <input type="number" placeholder="Optional" name="phone" value="<?= htmlspecialchars(isset($phone) ? $phone : '', ENT_QUOTES, 'UTF-8') ?>">

                                    </div>
                                </div>
                                <div class="line switch">
                                    <div class="input-fulid">
                                        <label><i class="fa-solid fa-lock"></i> Password</label>
                                        <br>
                                        <input type="password" name="password" value="<?= htmlspecialchars(isset($password) ? $password : '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?php
                                        if (isset($_SESSION['password_err'])): ?>
                                            <p class="error"><?= $_SESSION['password_err'] ?> </p>
                                        <?php unset($_SESSION['password_err']);
                                        endif; ?>
                                    </div>
                                    <div class="input-fulid">
                                        <label><i class="fa-solid fa-lock"></i> Confirm Password</label>
                                        <br>
                                        <input type="password" name="confirm_password" value="<?= htmlspecialchars(isset($confirm_password) ? $confirm_password : '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?php
                                        if (isset($_SESSION['confirm_password_err'])): ?>
                                            <p class="error"><?= $_SESSION['confirm_password_err'] ?> </p>
                                        <?php unset($_SESSION['confirm_password_err']);
                                        endif; ?>
                                    </div>
                                </div>

                                <div class="line">
                                    <div class="input-fulid">
                                        <label for="role"><i class="fa-solid fa-users-gear"></i> Role </label>
                                        <br>
                                        <select class="form-select form-select-sm bg-secondary-subtle" name="role" id="role" aria-label="Small select example">
                                            <option value="Select Role" selected>Select Role</option>
                                            <!-- <option value="project_manager">Project manager</option> -->
                                            <option value="team_member">Team Member</option>
                                            <option value="special_member">Special Member</option>
                                        </select>
                                        <?php
                                        if (isset($_SESSION['role_err'])): ?>
                                            <p class="error"><?= $_SESSION['role_err'] ?> </p>
                                        <?php unset($_SESSION['role_err']);
                                        endif; ?>
                                    </div>
                                    <div class="input-fulid">
                                        <div id="specialMemberOptions">

                                            <label><i class="fa-solid fa-clipboard-check"></i> Permissions </label>
                                            <br>

                                            <div class="flex items-center mb-1">
                                                <input id="Permission1" type="checkbox" name="Permissions[]" value="Add Projects" class="checkbox-input w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="Permission1" class=" ms-2">Add Projects</label>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <input id="Permission2" type="checkbox" name="Permissions[]" value="Add Tasks" class=" checkbox-input w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="Permission2" class="ms-2 ">Add Tasks</label>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <input id="Permission3" type="checkbox" name="Permissions[]" value="Watch Reports" class=" checkbox-input w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="Permission3" class="ms-2 ">Check Reports</label>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="line last-line">
                                    <div class="input-fulid">
                                        <button class="btn" name="add_member">Add Member</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <script src="../asset/js/dark_moode.js"></script>
        <script src="../asset/js/aside_links.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#role').change(function() {
                    if ($(this).val() === 'special_member') {
                        $('#specialMemberOptions').slideDown();
                    } else {
                        $('#specialMemberOptions').slideUp();
                    }
                });
            });
        </script>

    </body>

    </html>
<?php

} else {
    header('Location:../auth/logIn.php');
    exit();
}
?>