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
        <link rel="stylesheet" href="../asset/css/list_tasks.css">
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
                        <!-- <li><a class="switchTxt" href="add_project.php">Add Project</a></li> -->
                        <li><a class="switchTxt" href="list_project.php">List Projects</a></li>
                    </ul>
                </div>
                <div class="main-item switchTxt"><i class="icon fa-solid fa-grip"></i><a class="switchTxt" href="board.php">Board</a></div>
                <div class="main-item active  menu switchTxt">
                    <div class="branch-list-title">
                        <div class="item-header">
                            <i class="icon fa-solid fa-list-check"></i>
                            <span>Tasks</span>
                        </div>
                        <div><i class="fa-solid fa-caret-down"></i></div>
                    </div>
                    <ul>
                        <li class="active"><a class="switchTxt" href="list_tasks.php">List Tasks</a></li>
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
        <article class="switch_op">
            <div class="tasks-container">
                <div class="tasks-header">
                    <h1>List Tasks</h1>
                    <div class="tasks-actions">
                        <div class="search-box">
                            <input type="text" id="taskSearch" placeholder="Search tasks...">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="filter-dropdown">
                            <select id="projectFilter">
                                <option value="">All Projects</option>
                                <?php
                                // استعلام أكثر دقة لاسترجاع المشاريع التي يكون المستخدم عضوًا فيها
                                $projects = mysqli_query(
                                    $conn,
                                    "SELECT DISTINCT p.id, p.name 
                                    FROM projects p
                                    WHERE p.members LIKE CONCAT('%', '$user', '%')
                                    OR p.project_managers LIKE CONCAT('%', '$user', '%')
                                    ORDER BY p.name"
                                );

                                while ($project = mysqli_fetch_assoc($projects)) {
                                    echo "<option value='{$project['id']}'>{$project['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="filter-dropdown">
                            <select id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="To Do">To Do</option>
                                <option value="In Progress">In Progress</option>
                                <!-- <option value="Review">Review</option> -->
                                <option value="Done">Done</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tasks-table-container">
                    <table class="tasks-table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Project</th>
                                <th>List</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tasks_query = "
                       SELECT 
                           cards.id as card_id,
                           projects.id as project_id,
                           cards.title as card_title,
                           projects.name as project_name,
                           lists.name as list_name,
                           IFNULL(card_details.status, 'To Do') as status,
                           card_details.due_date,
                           card_details.description,
                           card_details.start_date,
                           GROUP_CONCAT(DISTINCT card_members.username SEPARATOR ', ') as members,
                           COUNT(DISTINCT card_checklists.id) as total_checklists,
                           COUNT(DISTINCT case when card_checklist_items.is_checked = 1 then 1 end) as completed_checklist_items,
                           COUNT(DISTINCT card_attachments.id) as attachments_count,
                           COUNT(DISTINCT card_comments.id) as comments_count
                       FROM cards
                       JOIN lists ON cards.list_id = lists.id
                       JOIN projects ON lists.project_id = projects.id
                       LEFT JOIN card_details ON cards.id = card_details.card_id
                       LEFT JOIN card_members ON cards.id = card_members.card_id
                       LEFT JOIN card_checklists ON cards.id = card_checklists.card_id
                       LEFT JOIN card_checklist_items ON card_checklists.id = card_checklist_items.checklist_id
                       LEFT JOIN card_attachments ON cards.id = card_attachments.card_id
                       LEFT JOIN card_comments ON cards.id = card_comments.card_id
                       WHERE (projects.created_by = '$user' 
                             OR projects.members LIKE '%, $user,%' 
                             OR projects.members LIKE '$user,%' 
                             OR projects.members LIKE '%, $user' 
                             OR projects.members = '$user'
                             OR projects.project_managers LIKE '%, $user,%' 
                             OR projects.project_managers LIKE '$user,%' 
                             OR projects.project_managers LIKE '%, $user' 
                             OR projects.project_managers = '$user'
                             OR card_members.username = '$user')
                       GROUP BY cards.id
                       ORDER BY card_details.due_date ASC, projects.name, lists.name
                   ";
                            $tasks = mysqli_query($conn, $tasks_query);

                            while ($task = mysqli_fetch_assoc($tasks)) {
                                $due_date = $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'No due date';
                                $progress = ($task['total_checklists'] > 0)
                                    ? round(($task['completed_checklist_items'] / $task['total_checklists']) * 100)
                                    : 0;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($task['card_title']); ?></td>
                                    <td><?php echo htmlspecialchars($task['project_name']); ?></td>
                                    <td><?php echo htmlspecialchars($task['list_name']); ?></td>
                                    <td>
                                        <?php $task['status'] = $task['status'] ?: "To Do"; ?>
                                        <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $task['status'])); ?>">
                                            <?php echo $task['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $due_date; ?></td>
                                    <td>
                                        <button class="view-details-btn"
                                            data-card-id="<?php echo $task['card_id']; ?>"
                                            data-card-title="<?php echo htmlspecialchars($task['card_title']); ?>"
                                            data-project-name="<?php echo htmlspecialchars($task['project_name']); ?>"
                                            data-project-id="<?php echo $task['project_id']; ?>"
                                            data-list-name="<?php echo htmlspecialchars($task['list_name']); ?>"
                                            data-status="<?php echo $task['status']; ?>"
                                            data-start-date="<?php echo $task['start_date'] ? date('M d, Y', strtotime($task['start_date'])) : ''; ?>"
                                            data-due-date="<?php echo $due_date; ?>"
                                            data-description="<?php echo htmlspecialchars($task['description']); ?>"
                                            data-members="<?php echo htmlspecialchars($task['members']); ?>"
                                            data-progress="<?php echo $progress; ?>"
                                            data-attachments="<?php echo $task['attachments_count']; ?>"
                                            data-comments="<?php echo $task['comments_count']; ?>">
                                            Read More
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Task Details Modal -->
            <div id="taskDetailsModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="modalTaskTitle"></h2>
                        <span class="close-modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="modal-row">
                            <div class="modal-col">
                                <h3>Project</h3>
                                <p id="modalProjectName"></p>
                            </div>
                            <div class="modal-col">
                                <h3>List</h3>
                                <p id="modalListName"></p>
                            </div>
                        </div>

                        <div class="modal-row">
                            <div class="modal-col">
                                <h3>Status</h3>
                                <p id="modalStatus"></p>
                            </div>
                            <div class="modal-col">
                                <h3>Progress</h3>
                                <div class="progress-container">
                                    <div class="progress-bar" id="modalProgressBar"></div>
                                    <span id="modalProgressText"></span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-row">
                            <div class="modal-col">
                                <h3>Start Date</h3>
                                <p id="modalStartDate"></p>
                            </div>
                            <div class="modal-col">
                                <h3>Due Date</h3>
                                <p id="modalDueDate"></p>
                            </div>
                        </div>

                        <div class="modal-row">
                            <div class="modal-col full-width">
                                <h3>Description</h3>
                                <p id="modalDescription"></p>
                            </div>
                        </div>

                        <div class="modal-row">
                            <div class="modal-col">
                                <h3>Assigned Members</h3>
                                <p id="modalMembers"></p>
                            </div>
                            <div class="modal-col">
                                <h3>Attachments</h3>
                                <p id="modalAttachments"></p>
                            </div>
                        </div>

                        <div class="modal-row">
                            <div class="modal-col">
                                <h3>Comments</h3>
                                <p id="modalComments"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="close-btn">Close</button>
                    </div>
                </div>
            </div>

        </article>
        <script>
            // Task Details Modal functionality
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('taskDetailsModal');
                const viewButtons = document.querySelectorAll('.view-details-btn');
                const closeModal = document.querySelector('.close-modal');
                const closeBtn = document.querySelector('.close-btn');

                viewButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        document.getElementById('modalTaskTitle').textContent = this.dataset.cardTitle;
                        document.getElementById('modalProjectName').textContent = this.dataset.projectName;
                        document.getElementById('modalListName').textContent = this.dataset.listName;

                        const statusBadge = document.getElementById('modalStatus');
                        statusBadge.textContent = this.dataset.status;
                        statusBadge.className = 'status-badge ' + this.dataset.status.toLowerCase().replace(' ', '-');

                        document.getElementById('modalStartDate').textContent = this.dataset.startDate || 'Not specified';
                        document.getElementById('modalDueDate').textContent = this.dataset.dueDate;
                        document.getElementById('modalDescription').textContent = this.dataset.description || 'No description provided';
                        document.getElementById('modalMembers').textContent = this.dataset.members || 'No members assigned';
                        document.getElementById('modalAttachments').textContent = this.dataset.attachments + ' attachment(s)';
                        document.getElementById('modalComments').textContent = this.dataset.comments + ' comment(s)';

                        const progressBar = document.getElementById('modalProgressBar');
                        const progressText = document.getElementById('modalProgressText');
                        progressBar.style.width = this.dataset.progress + '%';
                        progressText.textContent = this.dataset.progress + '%';

                        modal.style.display = 'block';
                    });
                });

                closeModal.addEventListener('click', function() {
                    modal.style.display = 'none';
                });

                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });

                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });

                // Search functionality
                const searchInput = document.getElementById('taskSearch');
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.tasks-table tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(filter) ? '' : 'none';
                    });
                });

                // Filter by project
                const projectFilter = document.getElementById('projectFilter');
                projectFilter.addEventListener('change', function() {
                    const filterValue = this.value;
                    const rows = document.querySelectorAll('.tasks-table tbody tr');

                    rows.forEach(row => {
                        if (!filterValue) {
                            row.style.display = '';
                            return;
                        }

                        const projectId = row.querySelector('.view-details-btn').dataset.projectId;
                        row.style.display = (projectId === filterValue) ? '' : 'none';
                    });
                });

                // Filter by status
                const statusFilter = document.getElementById('statusFilter');
                statusFilter.addEventListener('change', function() {
                    const filterValue = this.value;
                    const rows = document.querySelectorAll('.tasks-table tbody tr');

                    rows.forEach(row => {
                        if (!filterValue) {
                            row.style.display = '';
                            return;
                        }

                        const status = row.querySelector('.view-details-btn').dataset.status;
                        row.style.display = (status === filterValue) ? '' : 'none';
                    });
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