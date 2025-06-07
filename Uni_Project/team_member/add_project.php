<?php
include('../confing/DB_connection.php');
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    $id = $_SESSION['id'];
    $user = $_SESSION['username'];


    $projectName = isset($_SESSION['oldProjectName']) ? $_SESSION['oldProjectName'] : '';
    $startDate = isset($_SESSION['oldStartDate']) ? $_SESSION['oldStartDate'] : '';
    $endDate = isset($_SESSION['oldEndDate']) ? $_SESSION['oldEndDate'] : '';
    $managers = isset($_SESSION['managers']) ? $_SESSION['managers'] : '';
    $members = isset($_SESSION['members']) ? $_SESSION['members'] : '';
    $visibility = isset($_SESSION['visibility']) ? $_SESSION['visibility'] : '';
    $description = isset($_SESSION['description']) ? $_SESSION['description'] : '';

    unset(
        $_SESSION['oldProjectName'],
        $_SESSION['oldStartDate'],
        $_SESSION['oldEndDate'],
        $_SESSION['managers'],
        $_SESSION['members'],
        $_SESSION['visibility'],
        $_SESSION['description']
    );

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
        <!-- تحميل CSS الخاص بـ select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <!-- main css  -->
        <link rel="stylesheet" href="../asset/css/inner.css">
        <link rel="stylesheet" href="../asset/css/add_project.css">
        <title>manageEase</title>
    </head>

    <body>

        <?php include('includes/header.php') ?>
        <aside class="main-aside switch">

            <nav>
                <div class="main-item switchTxt"><i class="icon fa-solid fa-chart-line"></i><a class="switchTxt" href="dashboard.php">Dashboard</a></div>
                <div class="main-item active menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-regular fa-file"></i>
                            <span>Projects</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <!-- <li class="active"><a class="switchTxt" href="add_project.php">Add Project</a></li> -->
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
                <!-- <div class="main-item switchTxt"><i class="icon fa-regular fa-rectangle-list"></i><a class="switchTxt" href="report.php">Report</a></div> -->
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
        <article class="switch_op new-project">
            <div class="container">
                <div class="add-project-container switch_op">
                    <div class="row">
                        <div class="col-12">
                            <div class="top-section switch">
                                <h3>Project Information</h3>
                                <p>Fill in the details to create a new project</p>
                            </div>
                        </div>
                    </div>



                    <form class="mt-3 p-4" action="add_project_process.php" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (isset($_SESSION['messege'])) : ?>
                                    <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                                        <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                                        <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                                        <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                                    </div>
                                <?php unset($_SESSION['messege']);
                                endif; ?>
                                <div>
                                    <label class=" block mb-2 text-sm font-medium text-gray-400">Project Name</label>
                                    <input type="text" name="project_name"
                                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                        value="<?= htmlspecialchars(isset($projectName) ? $projectName : '', ENT_QUOTES, 'UTF-8'); ?>" />
                                    <?php
                                    if (isset($_SESSION['projecterror'])): ?>
                                        <p class="error"><?= $_SESSION['projecterror'] ?> </p>
                                    <?php unset($_SESSION['projecterror']);
                                    endif; ?>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-400 dark:text-white">Start Date</label>
                                    <input type="date" name="start_date"
                                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        value="<?= htmlspecialchars(isset($startDate) ? $startDate : '', ENT_QUOTES, 'UTF-8'); ?>" />
                                    <?php
                                    if (isset($_SESSION['startDateError'])): ?>
                                        <p class="error"><?= $_SESSION['startDateError'] ?> </p>
                                    <?php unset($_SESSION['startDateError']);
                                    endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-400 dark:text-white">End Date</label>
                                    <input type="date" name="end_date"
                                        class="  bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        value="<?= htmlspecialchars(isset($endDate) ? $endDate : '', ENT_QUOTES, 'UTF-8'); ?>" />
                                    <?php
                                    if (isset($_SESSION['endDateError'])): ?>
                                        <p class="error"><?= $_SESSION['endDateError'] ?> </p>
                                    <?php unset($_SESSION['endDateError']);
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-4">
                            <div class="col-md-6">
                                <label class="block  text-sm font-medium text-gray-400 dark:text-white">Project Manager</label>
                                <select class=" form-control form-control-sm select2 p-2 " multiple="multiple" name="manager_ids[]">
                                    <!-- <option> </option> -->
                                    <?php
                                    $query = "SELECT id, username 
                                    FROM users 
                                    WHERE role = ? AND (search_enabled = 1 OR added_by = ?)";
                                    $stmt = $conn->prepare($query);
                                    $manager = "project_manager";
                                    $stmt->bind_param("si", $manager, $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $row['username'] ?>" <?php echo isset($user_ids) && in_array($row['id'], explode(',', $user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['username']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <?php
                                if (isset($_SESSION['managerError'])): ?>
                                    <p class="error"><?= $_SESSION['managerError'] ?> </p>
                                <?php unset($_SESSION['managerError']);
                                endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="block  text-sm font-medium text-gray-400">Project Team Members</label>
                                <select class=" form-control form-control-sm select2 p-2" multiple="multiple" name="member_ids[]">
                                    <!-- <option> </option> -->
                                    <?php
                                    $query = "SELECT id, username FROM users WHERE added_by = ? OR search_enabled=1";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $row['username'] ?>" <?php echo isset($user_ids) && in_array($row['id'], explode(',', $user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['username']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <?php
                                if (isset($_SESSION['memberError'])): ?>
                                    <p class="error"><?= $_SESSION['memberError'] ?> </p>
                                <?php unset($_SESSION['memberError']);
                                endif; ?>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div x-data="{ selected: 'Private', options: [
    { value: 'Private', icon: 'fa-lock', text: 'Private', description: 'Members can only see their assigned tasks' },
    { value: 'Public', icon: 'fa-earth-americas', text: 'Public', description: 'Members can see everything related to the project' }
] }" class="relative w-full">

                                    <!-- الزر الظاهر عند اختيار القيمة -->
                                    <button type="button" @click="$refs.menu.classList.toggle('hidden')" class=" w-full flex items-center justify-between p-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <span x-text="options.find(o => o.value === selected).text"></span>
                                        <i :class="'fa-solid ' + options.find(o => o.value === selected).icon"></i>
                                    </button>

                                    <!-- القائمة المنسدلة -->
                                    <div x-ref="menu" class="switch_op absolute w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg hidden text-gray-500">
                                        <template x-for="option in options" :key="option.value">
                                            <div @click="selected = option.value; $refs.menu.classList.add('hidden')" class="flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-gray-900">
                                                <i :class="'fa-solid ' + option.icon"></i>
                                                <div>
                                                    <strong x-text="option.text"></strong>
                                                    <p class="text-xs text-gray-500" x-text="option.description"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <input type="hidden" name="visibility" x-model="selected">
                                </div>



                            </div>
                        </div>
                        <div class="row mt-3">

                            <label class="block mb-2 text-sm font-medium text-gray-400 dark:text-white">Description</label>
                            <textarea name="description" rows="4" class=" block p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your Description here..."><?= htmlspecialchars(isset($description) ? $description : '', ENT_QUOTES, 'UTF-8'); ?></textarea>

                        </div>


                        <button name="add_project" type="submit" class="mt-4 p-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create a project</button>
                    </form>



                </div>

            </div>
        </article>

        <script src="../asset/js/dark_moode.js"></script>
        <script src="../asset/js/aside_links.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <!-- تحميل jQuery (مطلوب لـ select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- تحميل JavaScript الخاص بـ select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


        <script>
            $(document).ready(function() {
                $('.select2').select2();
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