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
        <!-- tailwind  -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <!-- webSite icon  -->
        <link rel="shortcut icon" href="../asset/images/icon2.jpg">
        <!-- flowbite -->
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <!-- main css  -->
        <link rel="stylesheet" href="../asset/css/inner.css">
        <link rel="stylesheet" href="../asset/css/list_members.css">
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
                        <li><a class="switchTxt" href="add_project.php">Add Project</a></li>
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
        <article class="switch_op list-project">
            <div class="container">
                <div class="row">
                    <div class="col-12 adding-project d-flex justify-content-end  align-items-center mt-5">
                        <a href="add_project.php" class=" switch bg-blue-800 hover:bg-blue-700 text-white  from-cyan-400 via-cyan-500 to-cyan-600 focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 shadow-lg shadow-cyan-500/50 dark:shadow-lg dark:shadow-cyan-800/80 font-medium rounded-lg text-sm px-4 py-3 text-center me-2 mb-2 "><i class="fa-solid fa-plus"></i> Add Project</a>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?php if (isset($_SESSION['messege'])) : ?>
                        <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                            <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                            <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                            <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                        </div>
                    <?php unset($_SESSION['messege']);
                    endif; ?>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4 switch">
                        <table class=" w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                            <thead class="border-bottom switch text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 ">
                                        <div class="flex items-center ">
                                            Project name
                                        </div>

                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        <div class="flex items-center ">
                                            Due Data
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        <div class="flex items-center ">
                                            Members
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        <div class="flex items-center ">
                                            Status
                                        </div>
                                    </th>

                                    <th scope="col" class="px-4 py-3">
                                        <div class="flex items-center justify-content-center ">
                                            Action
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM projects WHERE created_by ='$user'";
                                $query_run = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $project) {
                                        // تحويل أسماء الأعضاء إلى مصفوفة
                                        $member_names = explode(",", $project['members']);
                                        $members_pictures = [];
                                        if (!empty($member_names)) {
                                            // إنشاء placeholders بعدد الأسماء
                                            $names_placeholder = implode(',', array_fill(0, count($member_names), '?'));

                                            // تجهيز الاستعلام لجلب الصور بناءً على أسماء الأعضاء
                                            $stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE username IN ($names_placeholder)");

                                            // تمرير القيم إلى الاستعلام
                                            $stmt->bind_param(str_repeat('s', count($member_names)), ...$member_names);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($row = $result->fetch_assoc()) {
                                                // تخزين اسم العضو وصورته
                                                // $members_pictures[$row['username']] = $row['profile_picture'];
                                                $members_pictures[] = $row['profile_picture'];
                                            }
                                        }
                                ?>
                                        <tr class="switch bg-white border-b bg-gray-800  border-gray-200 ">
                                            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-white">
                                                <?= $project['name'] ?>
                                            </td>
                                            <td class="px-6 py-4 ">
                                                <?= $project['end_date'] ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <!-- <?= $project['members'] ?> -->
                                                <?php // عرض صور الأعضاء
                                                foreach ($members_pictures as $picture) {
                                                    echo '<img class="inline-block size-10 rounded-full ring-2 ring-white" src="' . htmlspecialchars($picture) . '" alt="Member Image">';
                                                } ?>

                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap flex items-center ">
                                                <?= $project['status'] ?>
                                            </td>
                                            <td class=" action px-6 py-4 ">
                                                <!-- <a type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Read More</a> -->

                                                <a href="readMoreAboutProject.php?id=<?= $project['id']; ?>" class="btn btn-success btn-sm read-more-btn">Read More</a>


                                                <a href=" edit_project.php?id=<?= $project['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                                                <!-- <form action="add_member_process.php" method="POST" class="d-inline">
                                                <button type="submit" name="delete_member"
                                                    value="<?= $member['id']; ?>"
                                                    class="btn btn-danger btn-sm">Delete</button>

                                            </form> -->
                                                <button class="btn btn-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-project-id="<?= $project['id']; ?>"> Delete</button>


                                            </td>
                                        </tr>
                                <?php }
                                } ?>



                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </article>
        <!-- Modal for Delete-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header switch">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Project</h1>
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body delete-user switch">
                        <i class="fa-regular fa-circle-xmark"></i>
                        <p>Do you really want to delete this Project ? You won't be able to recover the data after deletion!</p>
                    </div>
                    <div class="modal-footer switch">
                        <button type="button" aria-label="Close" data-bs-dismiss="modal" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <form action="add_project_process.php" method="POST" class="d-inline">
                            <button type="submit" name="delete_project" id="confirmDeleteBtn" class=" btn btn-danger">Delete</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="../asset/js/dark_moode.js"></script>
        <script src="../asset/js/aside_links.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.delete-btn').click(function() {
                    let projectId = $(this).data('project-id');
                    $('#confirmDeleteBtn').val(projectId);
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