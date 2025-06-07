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
        <link rel="stylesheet" href="../asset/css/list_members.css">
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
                <div class="main-item active menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-solid fa-users"></i>
                            <span>Members</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <li><a class="switchTxt" href="add_memberr.php">Add Member</a></li>
                        <li class="active"><a class="switchTxt" href="list_members.php">List Members</a></li>
                    </ul>
                </div>
                <!-- <div class="main-item switchTxt"><i class=" icon fa-regular fa-note-sticky"></i><a class="switchTxt" href="sticky_note.php">Stiky Notes</a></div> -->
                <div class="main-item switchTxt"><i class="icon fa-solid fa-gear"></i><a class="switchTxt" href="setting.php">Setting</a></div>
                <div class="main-item switchTxt"><i class="fa-solid fa-right-from-bracket"></i><a class="switchTxt" href="logout.php">Logut</a></div>

            </nav>


        </aside>
        <article class="switch_op list-article">
            <div class="container">
                <div class="row">
                    <div class="col-12 add_member_link">
                        <a href="add_memberr.php" class="switch"><i class="fa-solid fa-user-plus"></i> Add Member</a>
                    </div>
                </div>
                <div class="row">
                    <?php if (isset($_SESSION['messege'])) : ?>
                        <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                            <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                            <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                            <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                        </div>
                    <?php unset($_SESSION['messege']);
                    endif; ?>
                    <div class="col-12 table_container table-responsive-sm">
                        <table class="table table-sm table-bordered  table-hover mt-3 " id="membersTable">

                            <thead class="switch">
                                <tr>
                                    <th class="switch">ID </th>
                                    <th class="switch">Name</th>
                                    <th class="switch">Email</th>
                                    <th class="switch">Role</th>
                                    <th class="switch">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                $sql = "SELECT * FROM users WHERE added_by ='$id'";
                                $query_run = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $member) {
                                        $profile_picture = $member['profile_picture'];
                                        $memberName = $member['username'];

                                        $project_names = [];
                                        $sql = "SELECT * FROM projects 
                                 WHERE FIND_IN_SET('$memberName', 	members) 
                                 OR FIND_IN_SET('$memberName', project_managers)";
                                        $query_run = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            while ($project = mysqli_fetch_array($query_run)) {
                                                $project_names[] = $project['name'];
                                            }
                                        }
                                        $projects = implode(", ", $project_names);
                                ?>
                                        <tr>
                                            <td class="switch"> <?= $member['id'] ?></td>
                                            <td class="switch picANDname"><img class="profile-pic" src="<?php echo   $profile_picture; ?>" alt="Profile Picture"> <?= $member['username'] ?></td>
                                            <td class="switch"> <?= $member['email'] ?></td>
                                            <td class="switch"> <?= $member['role'] ?></td>
                                            <td class="switch action">
                                                <!-- <a type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Read More</a> -->
                                                <a type="button" class="btn btn-success btn-sm read-more-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop"
                                                    data-username="<?= $member['username'] ?>"
                                                    data-email="<?= $member['email'] ?>"
                                                    data-role="<?= $member['role'] ?>"
                                                    data-job="<?= $member['job'] ?>"
                                                    data-phone="<?= $member['phone'] ?>"
                                                    data-profile-pic="<?= $member['profile_picture'] ?>"
                                                    data-projects="<?= $projects ?>"
                                                    data-created-at="<?= $member['created_at'] ?>">
                                                    Read More
                                                </a>

                                                <a href=" edit_member.php?id=<?= $member['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <!-- <form action="add_member_process.php" method="POST" class="d-inline">
                                                <button type="submit" name="delete_member"
                                                    value="<?= $member['id']; ?>"
                                                    class="btn btn-danger btn-sm">Delete</button>

                                            </form> -->
                                                <button class="btn btn-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-member-id="<?= $member['id']; ?>"> Delete</button>

                                            </td>
                                        </tr>
                                <?php
                                    }
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </article>
        <!-- Modal for Read More-->
        <div class="modal fade modal-dialog-scrollable " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header switch_op">
                        <h1 class="modal-title fs-5" id="modalTitle">Member Details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body switch_op">
                        <img id="modalProfilePic" class="display_pic" src="" alt="Profile Picture">
                        <h2 id="modalUsername"></h2>
                        <div class="details">
                            <div class="content ">
                                <span><strong>Email :</strong> </span>
                                <p class="switch"> <span id="modalEmail"></span></p>
                            </div>
                            <div class="content">
                                <span><strong>Role :</strong></span>
                                <p class="switch"><span id="modalRole"></span></p>
                            </div>
                            <div class="content">
                                <span><strong>job :</strong></span>
                                <p class="switch"><span id="modalJob"></span></p>
                            </div>
                            <div class="content">
                                <span><strong>phone :</strong></span>
                                <p class="switch"><span id="modalPhone"></span></p>
                            </div>
                            <div class="content">
                                <span><strong>Projects :</strong></span>
                                <p class="switch"><span id="modalProjects"></span></p>
                            </div>
                            <div class="content">
                                <span><strong>Joined :</strong></span>
                                <p class="switch"><span id="modaljioned"></span></p>
                            </div>
                        </div>
                        <!-- <div class="btn btn-primary"><i class="fa-solid fa-right-from-bracket"> <span> </span><span> </span></i><a class="switchTxt text-light" href="logout.php">Logut</a></div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Delete-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header switch">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Member</h1>
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body delete-user switch">
                        <i class="fa-regular fa-circle-xmark"></i>
                        <p>Do you really want to delete this member ? You won't be able to recover the data after deletion!</p>
                    </div>
                    <div class="modal-footer switch">
                        <button type="button" aria-label="Close" data-bs-dismiss="modal" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <form action="add_member_process.php" method="POST" class="d-inline">
                            <button type="submit" name="delete_member" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#membersTable').DataTable({
                    "ordering": true, // لتفعيل الفرز
                    "searching": true, // لتفعيل البحث
                    "paging": false, // للتنقل بين الصفحات
                    "info": true // لإظهار معلومات عن عدد النتائج
                });
            });

            $(document).ready(function() {
                $('.delete-btn').click(function() {
                    let memberId = $(this).data('member-id'); // الحصول على معرف العضو

                    // تحديث قيمة زر الحذف في المودال
                    $('#confirmDeleteBtn').val(memberId);
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.read-more-btn').click(function() {
                    let username = $(this).data('username');
                    let email = $(this).data('email');
                    let role = $(this).data('role');
                    let job = $(this).data('job');
                    let phone = $(this).data('phone');
                    let profilePic = $(this).data('profile-pic');
                    let projects = $(this).data('projects');
                    let createdAt = $(this).data('created-at'); // جلب تاريخ الانضمام

                    $('#modalTitle').text(username + "'s Details");
                    $('#modalUsername').text(username);
                    $('#modalEmail').text(email);
                    $('#modalRole').text(role);
                    $('#modalJob').text(job);
                    $('#modalPhone').text(phone);
                    $('#modalProjects').text(projects);
                    $('#modalProfilePic').attr('src', profilePic);

                    // التأكد من أن createdAt ليس فارغًا ثم تنسيقه
                    if (createdAt) {
                        let formattedDate = new Date(createdAt).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        $('#modaljioned').text(formattedDate);
                    } else {
                        $('#modaljioned').text("Unknown");
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