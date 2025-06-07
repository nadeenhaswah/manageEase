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
        <!-- tailwind  -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
                <div class="main-item active switchTxt"><i class="icon fa-solid fa-gear"></i><a class="switchTxt" href="setting.php">Setting</a></div>
                <div class="main-item switchTxt"><i class="fa-solid fa-right-from-bracket"></i><a class="switchTxt" href="logout.php">Logut</a></div>

            </nav>


        </aside>
        <?php
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $info = mysqli_fetch_assoc($result);
        ?>
        <article class="switch_op ">
            <div class="container mt-5">
                <div class="row  border border-gray-200 rounded-lg p-3">
                    <div class="col-md-12">
                        <p class="text-xl font-bold tracking-widest text-secondary">Settings</p>


                        <!-- switch to enable search or dis-enable  -->
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-medium text-gray-600 dark:text-gray-300">
                                Allow others to find and add you to projects
                            </span>

                            <label class="relative inline-block w-11 h-6">
                                <input type="checkbox" id="searchToggle" class="sr-only peer" value="1" <?= $info['search_enabled'] ? 'checked' : '' ?>>
                                <div class="absolute inset-0 bg-gray-200 rounded-full peer-checked:bg-blue-600 transition"></div>
                                <div class="absolute top-0.5 left-0.5 h-5 w-5 bg-white rounded-full transition peer-checked:translate-x-5"></div>
                            </label>
                        </div>




                    </div>
                </div>
            </div>



        </article>


        <script src="../asset/js/dark_moode.js"></script>
        <script src="../asset/js/aside_links.js"></script>
        <!-- ✅ Toast Box -->
        <div id="toast" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg hidden z-50 text-sm">
            Setting updated successfully
        </div>

        <script>
            function showToast(message = "Setting updated") {
                const toast = document.getElementById("toast");
                toast.textContent = message;
                toast.classList.remove("hidden");

                setTimeout(() => {
                    toast.classList.add("hidden");
                }, 2500);
            }

            document.getElementById("searchToggle").addEventListener("change", function() {
                const isEnabled = this.checked ? 1 : 0;

                fetch("settings_process.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "search_enabled=" + isEnabled
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "Success") {
                            showToast("Search setting updated successfully ✅");
                        } else {
                            showToast("Something went wrong ❌");
                        }
                    })
                    .catch(() => {
                        showToast("Failed to connect to server ❌");
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