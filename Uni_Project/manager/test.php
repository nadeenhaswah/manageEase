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
    <!-- main css  -->
    <link rel="stylesheet" href="../asset/css/inner.css">
    <title>manageEase</title>
</head>

<body>
    <header class="main-header switch">
        <div class="container">
            <div class="row">
                <div class="col-10">
                    <div class="user-photo">
                        <div class="log-out">

                        </div>
                    </div>
                    <div class="dark-mode">
                        <i class="fa-solid fa-sun" id="toggleIcon"></i>
                        <!-- <i class="fa-solid fa-moon"></i> -->
                    </div>
                </div>
            </div>
        </div>
    </header>
    <aside class="main-aside switch">
        <div class="logo">LOGO</div>
        <nav>
            <div class="main-item switchTxt"><i class="icon fa-solid fa-chart-line"></i><a class="switchTxt" href="#">Dashboard</a></div>
            <div class="main-item menu switchTxt">
                <div class="branch-list-title">
                    <div class="item-header">
                        <i class="icon fa-regular fa-file"></i>
                        Projects
                    </div>
                    <div><i class="fa-solid fa-caret-down"></i></div>
                </div>
                <ul>
                    <li><a class="switchTxt" href="#">Add Project</a></li>
                    <li><a class="switchTxt" href="#">List Projects</a></li>
                </ul>
            </div>
            <div class="main-item switchTxt"><i class="icon fa-solid fa-grip"></i><a class="switchTxt" href="#">Board</a></div>
            <div class="main-item menu switchTxt">
                <div class="branch-list-title">
                    <div class="item-header">
                        <i class="icon fa-solid fa-list-check"></i>
                        Tasks
                    </div>
                    <div><i class="fa-solid fa-caret-down"></i></div>
                </div>
                <ul>
                    <li><a class="switchTxt" href="#">List Tasks</a></li>
                </ul>
            </div>
            <div class="main-item switchTxt"><i class="icon fa-regular fa-rectangle-list"></i><a class="switchTxt" href="#">Report</a></div>
            <div class="main-item menu switchTxt">
                <div class="branch-list-title">
                    <div class="item-header">
                        <i class="icon fa-solid fa-users"></i>
                        Members
                    </div>
                    <div><i class="fa-solid fa-caret-down"></i></div>
                </div>
                <ul>
                    <li><a class="switchTxt" href="#">Add Member</a></li>
                    <li><a class="switchTxt" href="#">List Members</a></li>
                </ul>
            </div>
            <div class="main-item switchTxt"><i class=" icon fa-regular fa-note-sticky"></i><a class="switchTxt" href="#">Stiky Notes</a></div>
            <div class="main-item switchTxt"><i class="icon fa-solid fa-gear"></i><a class="switchTxt" href="#">Setting</a></div>
        </nav>


    </aside>

    <script src="../asset/js/dark_moode.js"></script>
    <script src="../asset/js/aside_links.js"></script>
</body>

</html>