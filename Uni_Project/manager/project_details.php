<?php
include('../confing/DB_connection.php');
session_start();
$stmt = $conn->prepare("SELECT id, username, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

// تخزين البيانات في متغيرات للاستخدام لاحقاً
$currentUserId = $current_user['id'];
$currentUsername = htmlspecialchars($current_user['username'], ENT_QUOTES, 'UTF-8');
$currentAvatar = htmlspecialchars($current_user['profile_picture'], ENT_QUOTES, 'UTF-8');
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
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <!-- fontawesome  -->
        <script src="https://kit.fontawesome.com/3e1d046fbe.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- webSite icon  -->
        <!-- tailwind  -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="shortcut icon" href="../asset/images/icon2.jpg">
        <!-- flowbite -->
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <!-- main css  -->
        <link rel="stylesheet" href="../asset/css/inner.css">
        <link rel="stylesheet" href="../asset/css/kanban.css">
        <title>manageEase</title>
    </head>

    <body>

        <?php include('includes/header.php') ?>

        <?php
        if (isset($_GET['id'])) {
            $project_id = htmlspecialchars(intval($_GET['id'])); // تأمين من الـ injection

            $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
            $stmt->bind_param("i", $project_id);
            $stmt->execute();
            $query_run = $stmt->get_result();

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
                            $members_pictures[] = [
                                'username' => $row['username'],
                                'profile_picture' => $row['profile_picture']
                            ];
                        }
                    }
                }
        ?>
                <article class="kanban-board switch_op ">
                    <div class="container-fluid  mt-1 rounded bg-primary">
                        <div class="row switch">
                            <div class="col-md-2 py-1 border-bottom text-white border-end flex justify-center items-center">
                                <a href="board.php" class="font-[Open_Sans] text-lg text-decoration-none text-white fs-6"><i class="fa-solid fa-arrow-left"></i> All Projects </a>
                            </div>
                            <div class="col-md-3 py-1 border-bottom text-white border-end flex justify-center items-center">
                                <span class="capitalize font-[Open_Sans] text-lg"> <?php echo $project['name']; ?></span>
                            </div>
                            <div class="col-md-4 py-1 text-white border-end border-bottom flex justify-center items-center">

                                <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers" data-dropdown-placement="bottom" class="text-white   focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button"><?php // عرض صور الأعضاء
                                                                                                                                                                                                                                                                                                                                                                    foreach ($members_pictures as $member) {
                                                                                                                                                                                                                                                                                                                                                                        echo '<img class="inline-block size-7 rounded-full ring-2 ring-white" src="' .  htmlspecialchars($member['profile_picture']) . '" alt="Member Image">';
                                                                                                                                                                                                                                                                                                                                                                    } ?>
                                </button>

                                <!-- Dropdown menu -->
                                <div id="dropdownUsers" class="z-10 hidden bg-white rounded-lg shadow-sm w-60 dark:bg-gray-700">
                                    <ul class="h-48 py-2 overflow-y-auto text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUsersButton">
                                        <?php foreach ($members_pictures as $member): ?>
                                            <li>
                                                <a href="#" class="flex items-center px-4  py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <img class="w-6 h-6 me-2 rounded-full" src="<?= htmlspecialchars($member['profile_picture']) ?>" alt="<?= htmlspecialchars($member['username']) ?> image">
                                                    <?= htmlspecialchars($member['username']) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>


                            </div>
                            <div class="col-md-3 py-1 border-end border-bottom flex justify-center items-center">
                                <ul class="flex w-full justify-center   flex-wrap text-md font-medium text-center mb-0" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                                    <li class="me-3" role="presentation">
                                        <button class="inline-block border-b-2 rounded-t-lg text-gray-500 hover:text-gray-500 border-transparent aria-selected:border-blue-600 aria-selected:text-white" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                            Board
                                        </button>
                                    </li>
                                    <li class="me-3" role="presentation">
                                        <button class="inline-block border-b-2 rounded-t-lg text-gray-500 border-transparent  hover:border-gray-300 aria-selected:border-blue-600 aria-selected:text-white" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">
                                            Table
                                        </button>
                                    </li>

                                </ul>
                            </div>


                        </div>
                    </div>
                    <div class="container-fluid mt-3">
                        <div class="row mb-4">
                            <div id="default-tab-content">
                                <div class="overflow-x-auto overflow-y-hidden p-4 rounded-lg bg-gray-50" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <!-- زر Add List -->
                                    <div class="flex justify-start items-center p-4">
                                        <button id="addListBtn" class="border-2 border-dashed border-blue-500 text-blue-500 px-4 py-2 rounded hover:bg-blue-50 transition">
                                            + Add List
                                        </button>
                                        <!-- Input Form (مخفي بالبداية) -->
                                        <div id="listInputContainer" class="hidden flex flex-col gap-2">
                                            <input type="text" id="listTitleInput" placeholder="Enter list title"
                                                class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                                            <div class="flex items-center gap-2">
                                                <button id="confirmAddListBtn"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Add List</button>
                                                <button id="cancelAddListBtn" class="text-red-500 text-xl font-bold hover:text-red-700">×</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- الحاوية الرئيسية للقوائم -->
                                    <div id="kanbanContainer" class="flex overflow-x-auto gap-4 p-4 min-h-[500px]">
                                        <!-- القوائم تُضاف هنا -->
                                    </div>

                                    <!-- Backdrop for drawer -->
                                    <div id="drawerBackdrop" class="drawer-backdrop"></div>
                                    <!-- Card Details Drawer -->
                                    <div id="cardDetailsDrawer" class="card-details-drawer">
                                        <h5 class="drawer-header">
                                            <i class="bi bi-card-text me-2"></i>Card Details
                                        </h5>
                                        <button type="button" onclick="hideDrawer()" class="drawer-close-btn">
                                            <i class="bi bi-x-lg"></i>
                                            <span class="sr-only">Close menu</span>
                                        </button>
                                        <div id="cardDetailsContent" class="drawer-content">
                                            <!-- سيتم إضافة محتوى البطاقة هنا عبر JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden p-4 rounded-lg bg-gray-50" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">List</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Card</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checklists</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php
                                                // استعلام لجلب جميع القوائم والكروت الخاصة بالمشروع مع تفاصيلها
                                                $sql = "SELECT 
                            l.id as list_id, l.name as list_name,
                            c.id as card_id, c.title as card_title,
                            cd.status, cd.start_date, cd.due_date,
                            GROUP_CONCAT(DISTINCT cm.username SEPARATOR '||') as members,
                            GROUP_CONCAT(DISTINCT cl.title SEPARATOR '||') as checklists
                        FROM lists l
                        LEFT JOIN cards c ON l.id = c.list_id
                        LEFT JOIN card_details cd ON c.id = cd.card_id
                        LEFT JOIN card_members cm ON c.id = cm.card_id
                        LEFT JOIN card_checklists cl ON c.id = cl.card_id
                        WHERE l.project_id = '$project_id'
                        GROUP BY c.id, l.id
                        ORDER BY l.created_at, c.created_at";

                                                $result = mysqli_query($conn, $sql);

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $members = !empty($row['members']) ? explode('||', $row['members']) : [];
                                                        $checklists = !empty($row['checklists']) ? explode('||', $row['checklists']) : [];
                                                ?>
                                                        <tr class="hover:bg-gray-50">
                                                            <!-- List Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                <?= htmlspecialchars($row['list_name']) ?>
                                                            </td>

                                                            <!-- Card Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                <?= htmlspecialchars($row['card_title']) ?>
                                                            </td>

                                                            <!-- Status Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                                <?php if ($row['status']): ?>
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php
                                                                    switch ($row['status']) {
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
                                                                        <?= $row['status'] ?>
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span class="bg-gray-100 text-gray-800">To Do</span>
                                                                <?php endif; ?>
                                                            </td>

                                                            <!-- Start Date Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <?= $row['start_date'] ? date('M d, Y', strtotime($row['start_date'])) : '<span class="text-gray-400">-</span>' ?>
                                                            </td>

                                                            <!-- Due Date Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <?= $row['due_date'] ? date('M d, Y', strtotime($row['due_date'])) : '<span class="text-gray-400">-</span>' ?>
                                                            </td>

                                                            <!-- Members Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <div class="flex flex-wrap gap-1">
                                                                    <?php
                                                                    if (!empty($members)) {
                                                                        foreach ($members as $member) {
                                                                            // جلب صورة العضو
                                                                            $user_sql = "SELECT profile_picture FROM users WHERE username='" . mysqli_real_escape_string($conn, $member) . "'";
                                                                            $user_result = mysqli_query($conn, $user_sql);
                                                                            $user = $user_result ? mysqli_fetch_assoc($user_result) : null;
                                                                            if ($user) {
                                                                    ?>
                                                                                <div class="flex items-center" title="<?= htmlspecialchars($member) ?>">
                                                                                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                                                                                        class="w-6 h-6 rounded-full object-cover border border-gray-200"
                                                                                        alt="<?= htmlspecialchars($member) ?>">
                                                                                </div>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo '<span class="text-gray-400">-</span>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </td>

                                                            <!-- Checklists Column -->
                                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                                <?php if (!empty($checklists)): ?>
                                                                    <div class="flex flex-col gap-1">
                                                                        <?php foreach ($checklists as $checklist): ?>
                                                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($checklist) ?></span>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-gray-400">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                                            No tasks found for this project
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

                    </div>
                    <!-- Delete Confirmation Modal -->
                    <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-20 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                            <h3 class="text-xl font-semibold mb-4 text-gray-800">Delete List</h3>
                            <p class="text-gray-600 mb-4">Are you sure you want to delete this list and all its cards?</p>
                            <div class="flex justify-end gap-2">
                                <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for confirming card deletion -->
                    <div id="cardDeleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-20 flex items-center justify-center z-50 hidden">
                        <div class="bg-white p-6 rounded shadow-lg w-[300px] text-center">
                            <p class="text-gray-800 mb-4">Are you sure you want to delete this card and all its contents?</p>
                            <div class="flex justify-center gap-4">
                                <button id="confirmDeleteCardBtn" class="px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                <button id="cancelDeleteCardBtn" class="px-4 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal للتأكيد -->
                    <div id="confirmModal" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-60 bg-white border rounded shadow-lg w-[300px] hidden">
                        <div class="p-4">
                            <p id="confirmMessage" class="text-gray-800 text-sm mb-4">Are you sure?</p>
                            <div class="flex justify-end gap-2">
                                <button id="confirmNoBtn" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                                <button id="confirmYesBtn" class="px-3 py-1 bg-red-500 text-white rounded text-sm">Delete</button>
                            </div>
                        </div>
                    </div>


                    <!-- Card Details Modal -->
                    <div id="cardDetailsModal" data-card-id="2" class="fixed inset-0 bg-black/30 flex items-start justify-center z-50 hidden">

                        <div class="bg-white w-full max-w-5xl mt-20 p-6 rounded shadow-xl relative flex gap-6 max-h-[90vh] overflow-y-auto pb-5">
                            <!-- Left/Main Section -->
                            <div class="w-3/4 space-y-6">
                                <button id="closeCardModal" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-2xl">&times;</button>

                                <h2 id="cardModalTitle" class="text-xl font-semibold mb-4">Card Title</h2>
                                <!-- 📋 معلومات مدخلة تظهر فوق -->
                                <div id="cardMetaDisplay" class="text-sm flex flex-wrap items-center gap-4 hidden">
                                    <div class="flex items-center gap-1 text-green-700">
                                        <i class="fas fa-play-circle text-green-600"></i>
                                        <span><strong>Start:</strong> <span id="startDateText" class="font-medium"></span></span>
                                    </div>
                                    <div class="flex items-center gap-1 text-red-600">
                                        <i class="fas fa-flag text-red-500"></i>
                                        <span><strong>Due:</strong> <span id="dueDateText" class="font-medium"></span></span>
                                    </div>
                                    <div class="flex items-center gap-1 text-blue-600">
                                        <i class="fas fa-bell text-blue-500"></i>
                                        <span><strong>Reminder:</strong> <span id="dueReminderText" class="font-medium"></span></span>
                                    </div>
                                </div>
                                <!-- ✅ Selected Members -->
                                <div id="selectedMembers" class="flex flex-wrap items-center gap-2 mt-2 hidden">
                                    <!-- صور وأسماء الأعضاء المختارين ستظهر هنا -->
                                </div>

                                <!-- Status Display -->
                                <div id="statusDisplay" class="flex items-center gap-1 text-purple-700 text-sm hidden">
                                    <i class="fas fa-tag text-purple-600"></i>
                                    <span><strong>Status:</strong></span>
                                    <span id="statusText" class="font-medium"></span>
                                </div>

                                <div id="completedAtDisplay" class="hidden flex items-center gap-1 text-gray-600 text-sm">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span><strong>Completed:</strong> <span id="completedAtText" class="font-medium"></span></span>
                                </div>



                                <!-- مسافة تنظيمية -->
                                <div class="mb-4"></div>

                                <!-- 📄 Description Section -->
                                <div id="descriptionSection" class="space-y-2">
                                    <h3 class="text-md font-semibold">Description</h3>

                                    <!-- العرض الحالي للنص -->
                                    <div id="descriptionDisplay"
                                        class="border border-gray-300 rounded p-2 text-sm text-gray-400 italic cursor-pointer hover:bg-gray-50"
                                        data-placeholder="Write description here...">Write description here...</div>

                                    <!-- textarea + أزرار -->
                                    <div id="descriptionEditor" class="hidden space-y-2">
                                        <textarea id="descriptionInput"
                                            class="w-full border rounded p-2 text-sm resize-none"
                                            rows="5"
                                            placeholder="Write description here..."></textarea>
                                        <div class="flex gap-2">
                                            <button id="saveDescriptionBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Save</button>
                                            <button id="cancelDescriptionBtn" class="text-red-500 px-3 py-1 rounded text-sm hover:text-red-700">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- عرض المرفقات -->
                                <div id="attachmentsDisplay" class="space-y-2"></div>


                                <div id="checklistsContainer" class="space-y-4">
                                    <!-- 🧩 ستضاف عناصر checklist هنا -->
                                </div>
                                <!-- 💬 Activity Section -->
                                <div id="activitySection" class="space-y-4 mt-6 pb-5">
                                    <h3 class="text-md font-semibold flex items-center gap-2">
                                        <i class="fas fa-comment-dots text-gray-600"></i>
                                        Activity
                                    </h3>

                                    <!-- Input Field -->
                                    <div class="flex items-start gap-3">
                                        <img src="<?= $info['profile_picture']; ?>" alt="User" class="w-8 h-8 rounded-full object-cover">
                                        <div class="flex-1">
                                            <textarea id="commentInput" rows="2" placeholder="Write a comment..." class="w-full border rounded px-3 py-2 text-sm resize-none focus:outline-none focus:ring"></textarea>
                                            <div id="commentActions" class="flex gap-2 mt-2 hidden">
                                                <button id="sendCommentBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Send</button>
                                                <button id="cancelCommentBtn" class="text-red-500 text-sm hover:underline">Cancel</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Comments List -->
                                    <div id="commentsList" class="space-y-4 mt-4">
                                        <!-- 📝 الكومنتات ستُضاف هنا لاحقًا -->
                                    </div>
                                </div>


                            </div>
                            <!-- Right/Sidebar -->
                            <div class="w-1/4 space-y-4 text-sm text-gray-700">
                                <!-- Trigger -->
                                <div id="datesToggle" class="cursor-pointer border border-gray-300 px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm font-medium flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-calendar-alt text-gray-600"></i>
                                    Dates
                                </div>

                                <!-- Form (مخفي مبدئيًا) -->
                                <div id="datesForm" class="hidden space-y-2">
                                    <div>
                                        <label for="startDateInput" class="block text-xs font-semibold mb-1">Start Date</label>
                                        <input type="date" id="startDateInput" class="w-full border px-2 py-1 rounded">
                                        <div id="startDateError" class="text-xs text-red-500 mt-1 hidden">Start date must be within the project date range.</div>
                                    </div><small id="startDateError" class="text-red-500 text-xs hidden"></small>

                                    <div>
                                        <label for="dueDateInput" class="block text-xs font-semibold mb-1">Due Date</label>
                                        <input type="date" id="dueDateInput" class="w-full border px-2 py-1 rounded">
                                        <div id="dueDateError" class="text-xs text-red-500 mt-1 hidden">Due date must be within the project date range.</div>
                                        <small id="dueDateError" class="text-red-500 text-xs hidden"></small>


                                    </div>
                                    <div>
                                        <label for="dueReminderSelect" class="block text-xs font-semibold mb-1">Due Reminder</label>
                                        <select id="dueReminderSelect" class="w-full border px-2 py-1 rounded">
                                            <option value="None">None</option>
                                            <option value="10 minutes before">10 minutes before</option>
                                            <option value="1 hour before">1 hour before</option>
                                            <option value="1 day before">1 day before</option>
                                            <option value="2 days before">2 days before</option>
                                        </select>
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <button id="saveDatesBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Save</button>
                                        <button id="cancelDatesBtn" class="text-red-500 px-3 py-1 rounded text-sm hover:text-red-700">Cancel</button>
                                    </div>
                                </div>
                                <!-- 👥 Members Toggle -->
                                <div id="membersToggle" class="cursor-pointer border border-gray-300 px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm font-medium flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-user-friends text-gray-600"></i>
                                    Members
                                </div>

                                <!-- Members Selection (مخفي مبدئيًا) -->
                                <div id="membersForm" class="hidden space-y-2">
                                    <div id="membersList" class="space-y-2 max-h-40 overflow-y-auto">
                                        <!-- أسماء الأعضاء وصورهم ستُضاف هنا ديناميكيًا -->
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <button id="saveMembersBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Add</button>
                                        <button id="cancelMembersBtn" class="text-red-500 px-3 py-1 rounded text-sm hover:text-red-700">Cancel</button>
                                    </div>
                                </div>

                                <!-- 🔄 Status Section -->
                                <div id="statusToggle" class="cursor-pointer border border-gray-300 px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm font-medium flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-tasks text-gray-600"></i>
                                    Status
                                </div>

                                <!-- Form (مخفي مبدئيًا) -->
                                <div id="statusForm" class="hidden mt-2">
                                    <label for="statusSelect" class="block text-xs font-semibold mb-1">Select Status</label>
                                    <select id="statusSelect" class="w-full border px-2 py-1 rounded">
                                        <option value="To Do">To Do</option>
                                        <option value="In Progress">In Progress</option>
                                        <!-- <option value="Review">Review</option> -->
                                        <option value="Done">Done</option>
                                    </select>
                                    <div class="flex gap-2 mt-2">
                                        <button id="saveStatusBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Save</button>
                                        <button id="cancelStatusBtn" class="text-red-500 px-3 py-1 rounded text-sm hover:text-red-700">Cancel</button>
                                    </div>
                                </div>
                                <div id="checklistToggle" class="cursor-pointer border border-gray-300 px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm font-medium flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-tasks text-gray-600"></i>
                                    Checklist
                                </div>

                                <!-- ✅ نموذج إدخال عنوان Checklist (مخفي مبدئياً) -->
                                <div id="checklistForm" class="hidden mt-2 space-y-2">
                                    <input type="text" id="checklistTitleInput" placeholder="Enter checklist title..." class="w-full border rounded px-2 py-1 text-sm">
                                    <div class="flex gap-2">
                                        <button id="saveChecklistTitleBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Add</button>
                                        <button id="cancelChecklistTitleBtn" class="text-red-500 px-3 py-1 rounded text-sm hover:text-red-700">Cancel</button>
                                    </div>
                                </div>
                                <!-- 📎 Attachment Toggle -->
                                <div id="attachmentToggle" class="cursor-pointer border border-gray-300 px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 text-sm font-medium flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-paperclip text-gray-600"></i>
                                    Attachment
                                </div>

                                <div id="attachmentForm" class="hidden space-y-2 mt-2">
                                    <div id="attachmentOptions" class="flex gap-2">
                                        <button id="fileOptionBtn" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm flex-1">Upload File</button>
                                        <button id="urlOptionBtn" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm flex-1">Insert URL</button>
                                    </div>

                                    <!-- File Upload Section -->
                                    <div id="fileUploadSection" class="hidden space-y-2">
                                        <label class="block border px-4 py-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 text-sm text-center">
                                            Click to choose file
                                            <input type="file" id="attachmentFile" class="hidden">
                                        </label>
                                        <div id="selectedFileName" class="text-xs mt-1 text-gray-700 font-medium"></div>
                                        <div id="attachmentError" class="text-xs text-red-500 mt-1 hidden"></div>
                                    </div>

                                    <!-- URL Section -->
                                    <div id="urlSection" class="hidden space-y-2">
                                        <input type="text" id="linkTitle" placeholder="Link Title" class="w-full border rounded px-2 py-1 text-sm mb-2">
                                        <input type="url" id="linkURL" placeholder="https://example.com" class="w-full border rounded px-2 py-1 text-sm">
                                        <div id="attachmentErrorUrl" class="text-xs text-red-500 mt-1 hidden"></div>
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="button" id="addAttachmentBtn" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Insert</button>
                                        <button id="cancelAttachmentBtn" class="text-red-500 px-3 py-1 rounded text-sm hover:text-red-700">Cancel</button>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

            <?php
            }
        }
            ?>
            <script>
                // دالة لتحميل القوائم عند فتح الصفحة
                function loadLists(projectId) {
                    fetch(`api/lists.php?project_id=${projectId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.lists) {
                                data.lists.forEach(list => {
                                    createKanbanList(list.name, list.id);
                                });
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

                // استدعاء الدالة عند تحميل الصفحة
                document.addEventListener('DOMContentLoaded', function() {
                    const projectId = <?php echo json_encode($project_id); ?>;
                    if (projectId) {
                        loadLists(projectId);
                    }
                });
            </script>
                </article>


                <script src="../asset/js/dark_moode.js"></script>
                <script src="../asset/js/aside_links.js"></script>
                <!-- Bootstrap JS Bundle with Popper -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <!-- Custom JS -->
                <script>
                    window.currentUserId = <?= json_encode($currentUserId) ?>;
                    window.currentUserAvatar = <?= json_encode($currentAvatar) ?>;
                    window.currentUserName = <?= json_encode($currentUsername) ?>;
                </script>
                <script src="../asset/js/kanban.js"></script>
                <script>
                    // جعل معرف المشروع متاحاً عالمياً
                    window.currentProjectId = <?php echo json_encode($project_id); ?>;

                    document.addEventListener('DOMContentLoaded', function() {
                        // تأخير التهيئة قليلاً لضمان تحميل جميع الأكواد
                        setTimeout(() => {
                            if (typeof initKanban === 'function' && window.currentProjectId) {
                                initKanban(window.currentProjectId);
                            } else {
                                console.error('Failed to initialize Kanban board');
                            }
                        }, 100);
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