<?php
include('../confing/DB_connection.php');
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    $id = $_SESSION['id'];
    $user = $_SESSION['username'];

    $email = isset($_SESSION['old_email']) ? $_SESSION['old_email'] : '';
    $password = isset($_SESSION['old_password']) ? $_SESSION['old_password'] : '';
    $confirm_password = isset($_SESSION['old_confirm_password']) ? $_SESSION['old_confirm_password'] : '';

    unset($_SESSION['old_email'], $_SESSION['old_password'], $_SESSION['old_confirm_password']);
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
        // $sql = "SELECT * FROM users WHERE id='$id' and username='$user'";
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $info = mysqli_fetch_assoc($result);

        $project_names = [];
        $sql = "SELECT * FROM projects 
        WHERE FIND_IN_SET('$user', 	members) 
        OR FIND_IN_SET('$user', project_managers)";
        $query_run = mysqli_query($conn, $sql);

        if (mysqli_num_rows($query_run) > 0) {
            while ($project = mysqli_fetch_array($query_run)) {
                $project_names[] = $project['name'];
            }
        }
        $projects = implode(", ", $project_names);


        ?>
        <article class="switch_op ">
            <div class="container">
                <!-- <div class="row callout callout-info">
                    <div class="col-12 switch_op mt-3">
                        <h1 class="mb-4 text-sm  text-secondary  md:text-5xl lg:text-6xl">Profile </h1>
                    </div>
                </div> -->
                <?php if (isset($_SESSION['messege'])) : ?>
                    <div class="alert alert-primary alert-dismissible fade show session-msg" role="alert">
                        <strong>Done <span> </span> <span></span></strong> <?= $_SESSION['messege'] ?>
                        <span> </span> <span> </span> <span> </span> <span> </span> <span> </span> <span> </span>
                        <i class="fa-solid fa-circle-check" style="color: #65e67b;"></i>
                    </div>
                <?php unset($_SESSION['messege']);
                endif; ?>
                <div class="row  mt-4">
                    <div class="switch col-md-3 mt-1  p-3 border border-gray-200 rounded-lg  d-flex  flex-column  justify-content-center align-items-center flex-nowrap ">
                        <?php

                        // $profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'asset/images/profilePic.png';
                        ?>
                        <img class="profile-pic-page mb-4" src="<?= $info['profile_picture']; ?>" alt="Profile Picture">

                        <p class=" text-gray-600 text-capitalize fs-5 mb-0"> <?php echo $info['username']; ?> </p>
                        <p class=" text-gray-600 text-capitalize fs-6"> <?php echo $info['role']; ?> </p>
                    </div>
                    <div class="switch col-md-9 mt-1  border border-gray-200 rounded-lg  ">


                        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-purple-500 border-blue-600 dark:border-purple-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                                <li class="me-2" role="presentation">
                                    <button class="inline-block px-5 py-3 border-b-2 rounded-t-lg" id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                                </li>
                                <li class="me-2" role="presentation">
                                    <button class="inline-block px-5 py-3 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">Update Information</button>
                                </li>
                                <li class="me-2" role="presentation">
                                    <button class="inline-block px-5 py-3 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Change Password</button>
                                </li>

                            </ul>
                        </div>
                        <div id="default-styled-tab-content switch">
                            <div class="w-full switch hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">
                                <section class=" switch py-8 antialiased dark:bg-gray-900 md:py-16">
                                    <div class="w-full   px-4 2xl:px-0">
                                        <div class="space-y-4 switch sm:space-y-2 rounded-lg bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 mb-6 md:mb-8">
                                            <dl class="sm:flex items-center justify-between gap-4">
                                                <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Name</dt>
                                                <dd class="font-medium text-gray-400 dark:text-white sm:text-end text-capitalize"> <?php echo $info['username']; ?></dd>
                                            </dl>
                                            <dl class="sm:flex items-center justify-between gap-4">
                                                <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Email</dt>
                                                <dd class="font-medium text-gray-400 dark:text-white sm:text-end"><?php echo $info['email']; ?></dd>
                                            </dl>
                                            <dl class="sm:flex items-center justify-between gap-4">
                                                <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Role</dt>
                                                <dd class="font-medium text-gray-400 dark:text-white sm:text-end"><?php echo $info['role']; ?></dd>
                                            </dl>
                                            <dl class="sm:flex items-center justify-between gap-4">
                                                <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Job</dt>
                                                <dd class="font-medium text-gray-400 dark:text-white sm:text-end"><?php echo $info['job']; ?></dd>
                                            </dl>
                                            <dl class="sm:flex items-center justify-between gap-4">
                                                <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Phone</dt>
                                                <dd class="font-medium text-gray-400 dark:text-white sm:text-end"><?php echo $info['phone']; ?></dd>
                                            </dl>
                                            <dl class="sm:flex items-center justify-between gap-4">
                                                <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Projects</dt>
                                                <dd class="font-medium text-gray-400 dark:text-white sm:text-end"> <?php echo $projects; ?></dd>
                                            </dl>

                                        </div>

                                    </div>
                                </section>
                            </div>
                            <div class="hidden p-4 switch rounded-lg bg-gray-50 " id="styled-settings" role="tabpanel" aria-labelledby="settings-tab">


                                <form class="max-w-sm mx-auto switch" action="reset_password_process.php" method="POST">
                                    <div class="mb-5">
                                        <label for="email" class="block mb-2 text-sm  font-medium text-gray-600 dark:text-white">Your email</label>
                                        <input type="email" name="email" id="email"
                                            value="<?= htmlspecialchars(isset($email) ? $email  : '', ENT_QUOTES, 'UTF-8') ?>"
                                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">New password</label>
                                        <input type="password" name="password" placeholder="••••••••" id="password"
                                            value="<?= htmlspecialchars(isset($password) ? $password : '', ENT_QUOTES, 'UTF-8') ?>"
                                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="repeat-password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-white">Confirm New password</label>
                                        <input type="password" name="confirm_password" placeholder="••••••••" id="repeat-password"
                                            value="<?= htmlspecialchars(isset($confirm_password) ? $confirm_password : '', ENT_QUOTES, 'UTF-8') ?>"
                                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                                    </div>

                                    <button type="submit" name="reset" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Reset Password</button>
                                </form>

                            </div>
                            <div class="switch  p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel" aria-labelledby="dashboard-styled-tab">


                                <form action="update_information.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $info['id']; ?>">
                                    <div class="relative z-0 w-full mb-5 group">
                                        <!-- صورة البروفايل الحالية -->
                                        <img id="profile-preview" class="profile-pic-page-edit " src="<?= $info['profile_picture']; ?>" alt="Profile Picture">

                                        <div class="flex items-center gap-4 mt-2">
                                            <!-- أيقونة لتغيير الصورة -->
                                            <label class="cursor-pointer text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i> Change
                                                <input type="file" name="new_profile_picture" class="hidden" accept="image/*" id="new-profile-input">
                                            </label>

                                            <!-- أيقونة لحذف الصورة -->
                                            <label class="cursor-pointer text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash-alt"></i> Remove
                                                <input type="checkbox" name="remove_picture" value="1" class="hidden" id="remove-profile-checkbox">
                                            </label>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 mt-5 group">
                                            <input type="text" value="<?= $info['username']; ?>" name="username" class="block py-2.5 px-0 w-full text-sm text-gray-600 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                                            <label class="peer-focus:font-medium absolute  text-lg text-gray-500  dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
                                            <?php
                                            if (isset($_SESSION['usererror'])): ?>
                                                <p class="error"><?= $_SESSION['usererror'] ?> </p>
                                            <?php unset($_SESSION['usererror']);
                                            endif; ?>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="email" value="<?= $info['email']; ?>" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-600 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                                            <label for="floating_email" class="peer-focus:font-medium absolute text-lg text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
                                            <?php
                                            if (isset($_SESSION['email_err'])): ?>
                                                <p class="error"><?= $_SESSION['email_err'] ?> </p>
                                            <?php unset($_SESSION['email_err']);
                                            endif; ?>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="text" value="<?= $info['job']; ?>" name="job" class="block py-2.5 px-0 w-full text-sm text-gray-600 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                                            <label class="peer-focus:font-medium absolute text-lg text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Job</label>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="number" value="<?= $info['phone']; ?>" name="phone" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-600 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                                            <label for="floating_email" class="peer-focus:font-medium absolute text-lg text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone</label>
                                        </div>
                                        <button type="submit" name="update" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                                </form>

                            </div>


                        </div>

                    </div>
                </div>


            </div>
            </div>

        </article>

        <script src="../asset/js/dark_moode.js"></script>
        <script src="../asset/js/aside_links.js"></script>
        <script>
            const profileInput = document.getElementById('new-profile-input');
            const profilePreview = document.getElementById('profile-preview');
            const removeCheckbox = document.getElementById('remove-profile-checkbox');

            profileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                        removeCheckbox.checked = false; // إذا رفع صورة جديدة، نلغي حذف الصورة
                    };
                    reader.readAsDataURL(file);
                }
            });

            // خيار حذف الصورة يعرض الصورة الافتراضية فوراً
            removeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    profilePreview.src = '../asset/images/profilePic.png';
                    profileInput.value = ''; // نفرغ اختيار الملف إذا تم التراجع
                }
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