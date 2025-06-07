<?php
$sql = "SELECT * FROM users WHERE id='$id' and username='$user'";
$query_run = mysqli_query($conn, $sql);
if (mysqli_num_rows($query_run) > 0) {
    $info = mysqli_fetch_array($query_run);
?>
    <header class="main-header switch">
        <!-- <div class="container">
        <div class="row">
            <div class="col-12"> -->
        <div class="logo switch">ManageEase</div>
        <div class="branch-container">
            <div class="dark-mode">
                <i class="fa-solid fa-sun" id="toggleIcon"></i>
                <!-- <i class="fa-solid fa-moon"></i> -->
            </div>
            <div class="notifications position-relative">
                <i class="fa-solid fa-bell" id="notifications-icon"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle badge">
                    <?php
                    $sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $_SESSION['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $count = $result->fetch_assoc()['count'];
                    echo $count > 0 ? $count : '';
                    ?>
                </span>
                <div class="notifications-container z-3 " id="notifications-container"></div>
            </div>

            <div class="user-photo">
                <?php
                $sql = "SELECT * FROM users WHERE id='$id' and username='$user'";
                $query_run = mysqli_query($conn, $sql);
                if (mysqli_num_rows($query_run) > 0) {
                    $info = mysqli_fetch_array($query_run);
                }
                ?>
                <?php
                // $profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'assets/images/profilePic.png'; 
                ?>
                <!-- <img class="profile-pic-page mb-4" src="<?= $info['profile_picture']; ?>" alt="Profile Picture"> -->

                <img id="dropdownInformationButton" data-dropdown-toggle="dropdownInformation" class=" profile-pic" src="<?= $info['profile_picture']; ?>" alt="Profile Picture">
                <div id="dropdownInformation" class="switch z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-70 dark:bg-gray-700 dark:divide-gray-600">
                    <div class="px-4 py-3 text-sm text-gray-900 ">
                        <div class="switch col-lg-12 d-flex justify-content-center align-items-center flex-nowrap flex-column pb-2">
                            <img class="info-profile-pic mb-1" src="<?= $info['profile_picture']; ?>" alt="Profile Picture">
                            <p class=" text-gray-600 text-capitalize fs-5 mb-0"> <?php echo $info['username']; ?> </p>
                            <p class=" text-gray-600 text-capitalize fs-6"> <?php echo $info['role']; ?> </p>
                        </div>

                        <div class="switch d-flex justify-content-start align-items-center flex-nowrap border-bottom mb-1">
                            <a href="profile.php" class="btn text-secondary text-capitalize"> <i class="fa-solid fa-user"></i> My Profile</a>
                        </div>
                        <div class="switch d-flex justify-content-start align-items-center flex-nowrap border-bottom mb-1">
                            <a href="update_info.php" class="btn text-secondary text-capitalize"> <i class="fa-solid fa-pen-to-square"></i> Update Information</a>
                        </div>
                        <div class="switch d-flex justify-content-start align-items-center flex-nowrap border-bottom mb-1">
                            <a href="reset_pass.php" class="btn text-secondary text-capitalize"> <i class="fa-solid fa-lock-open"></i> Change Password</a>
                        </div>
                        <div class="switch d-flex justify-content-start align-items-center flex-nowrap border-bottom mb-1">
                            <a href="setting.php" class="btn text-secondary text-capitalize"> <i class="fa-solid fa-gear"></i> Setting</a>
                        </div>
                        <div class="switch d-flex justify-content-center align-items-center flex-nowrap mt-5 border border-secondary p-1 border-opacity-10">
                            <a href="logout.php" class="btn text-secondary text-capitalize"><i class="fa-solid fa-right-from-bracket"></i> Log Out </a>
                        </div>

                    </div>
                </div>
                <div class="log-out">

                </div>
            </div>
        </div>
    <?php
}

    ?>

    <!-- </div>
        </div>
    </div> -->
    </header>