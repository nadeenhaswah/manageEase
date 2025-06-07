function renderChecklist(checklistId, title, progress = 0) {
    const checklistDiv = document.createElement("div");
    checklistDiv.className = "bg-gray-50 border rounded p-3 mb-3";
    checklistDiv.dataset.checklistId = checklistId;
    
    checklistDiv.innerHTML = `
      <div class="flex justify-between items-center mb-4">
        <h4 class="font-semibold text-lg checklist-title">${title}</h4>
        <div class="flex gap-2 text-gray-500">
          <i class="fas fa-pen cursor-pointer hover:text-blue-600 edit-checklist" title="Edit"></i>
          <i class="fas fa-trash cursor-pointer hover:text-red-600 delete-checklist" title="Delete"></i>
        </div>
      </div>
      <div class="w-full h-2 bg-gray-200 rounded mb-1 relative">
        <div class="h-full bg-green-500 rounded" style="width: ${progress}%"></div>
        <span class="absolute right-1 top-[-18px] text-xs font-semibold text-gray-700 progress-percent">${progress}%</span>
      </div>
      <div class="checklist-items space-y-1 mb-2"></div>
      <div class="add-item-section text-blue-600 text-sm cursor-pointer hover:underline">+ Add an item</div>
      <div class="item-input-form hidden space-y-1">
        <input type="text" class="w-full border rounded px-2 py-1 text-sm" placeholder="Enter item title">
        <div class="flex gap-2">
          <button class="add-item-btn bg-blue-500 text-white px-2 py-1 rounded text-sm">Add</button>
          <button class="cancel-item-btn text-red-500 text-sm">Cancel</button>
        </div>
      </div>
    `;

    checklistsContainer.appendChild(checklistDiv);

    // تحميل العناصر
    fetch(`api/get_checklist_items.php?checklist_id=${checklistId}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                const itemsContainer = checklistDiv.querySelector(".checklist-items");
                itemsContainer.innerHTML = "";
                
                data.items.forEach(item => {
                    renderChecklistItem(
                        itemsContainer,
                        item.content,
                        item.id,
                        checklistId,
                        item.is_checked == 1,
                        updateProgressWrapper
                    );
                });
            }
        });

    function updateProgressWrapper() {
        const allItems = checklistDiv.querySelectorAll(".check-item");
        const checkedItems = checklistDiv.querySelectorAll(".check-item:checked");
      
        const percent = allItems.length === 0 ? 0 : Math.round((checkedItems.length / allItems.length) * 100);
        progressBar.style.width = percent + "%";
        progressPercent.textContent = percent + "%";
    }

    // ربط الأحداث
    const editIcon = checklistDiv.querySelector(".edit-checklist");
    const deleteIcon = checklistDiv.querySelector(".delete-checklist");
    const addItemBtn = checklistDiv.querySelector(".add-item-section");
    const itemForm = checklistDiv.querySelector(".item-input-form");
    const itemInput = itemForm.querySelector("input");
    const confirmBtn = itemForm.querySelector(".add-item-btn");
    const cancelBtn = itemForm.querySelector(".cancel-item-btn");
    const progressBar = checklistDiv.querySelector(".bg-green-500");
    const progressPercent = checklistDiv.querySelector(".progress-percent");

    // تعديل العنوان
    editIcon.addEventListener("click", () => {
        const titleElement = checklistDiv.querySelector(".checklist-title");
        const oldTitle = titleElement.textContent;

        const input = document.createElement("input");
        input.type = "text";
        input.value = oldTitle;
        input.className = "border rounded px-2 py-1 text-sm font-semibold";

        const saveBtn = document.createElement("button");
        saveBtn.textContent = "Save";
        saveBtn.className = "bg-blue-500 text-white px-2 py-1 text-xs rounded ml-2";

        titleElement.replaceWith(input);
        input.after(saveBtn);

        saveBtn.addEventListener("click", () => {
            const newTitle = input.value.trim();
            if (newTitle) {
                fetch("api/update_checklist.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${checklistId}&title=${encodeURIComponent(newTitle)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        titleElement.textContent = newTitle;
                        input.replaceWith(titleElement);
                        saveBtn.remove();
                    }
                });
            }
        });
    });

    // حذف القائمة
    deleteIcon.addEventListener("click", () => {
        if (confirm("Are you sure you want to delete this checklist?")) {
            fetch("api/delete_checklist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${checklistId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    checklistDiv.remove();
                }
            });
        }
    });

    // إضافة عنصر جديد
    addItemBtn.addEventListener("click", () => {
        addItemBtn.classList.add("hidden");
        itemForm.classList.remove("hidden");
        itemInput.focus();
    });

    cancelBtn.addEventListener("click", () => {
        itemForm.classList.add("hidden");
        addItemBtn.classList.remove("hidden");
        itemInput.value = "";
    });

    confirmBtn.addEventListener("click", () => {
        const itemText = itemInput.value.trim();
        if (!itemText) return;

        fetch("api/add_checklist_items.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `checklist_id=${checklistId}&content=${encodeURIComponent(itemText)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                const itemsContainer = checklistDiv.querySelector(".checklist-items");
                renderChecklistItem(
                    itemsContainer,
                    itemText,
                    data.item_id,
                    checklistId,
                    false,
                    updateProgressWrapper
                );
                itemInput.value = "";
                itemForm.classList.add("hidden");
                addItemBtn.classList.remove("hidden");
                updateProgressWrapper();
            }
        });
    });
}
  function renderChecklistItem(container, content, itemId, checklistId, isChecked = false, updateProgressCallback = () => {}) {
    const item = document.createElement("div");
    item.className = "flex items-center justify-between gap-2 text-sm";
    item.dataset.itemId = itemId;
    item.dataset.checklistId = checklistId; // إضافة dataset للربط
    
    // const item = document.createElement("div");
    item.className = "flex items-center justify-between gap-2 text-sm";
    item.innerHTML = `
      <div class="flex items-center gap-2">
        <input type="checkbox" class="check-item w-4 h-4 text-green-600 bg-white border border-gray-300 rounded focus:ring-green-500 focus:ring-2" ${isChecked ? 'checked' : ''}>
        <span class="item-text">${content}</span>
      </div>
      <div class="flex gap-2 text-gray-400 text-sm">
        <i class="fas fa-pen cursor-pointer hover:text-blue-600 edit-item" title="Edit"></i>
        <i class="fas fa-trash cursor-pointer hover:text-red-600 delete-item" title="Delete"></i>
      </div>
    `;
  
    container.appendChild(item);

     // استدعاء تحديث النسبة عند التغيير
  item.querySelector(".check-item").addEventListener("change", (e) => {
    fetch("api/update_checklist_item.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `id=${itemId}&content=${encodeURIComponent(content)}&is_checked=${e.target.checked ? 1 : 0}`
    }).then(() => updateProgressCallback());
  });

  // كمان عند الحذف لازم تحدثي النسبة:
  item.querySelector(".delete-item").addEventListener("click", () => {
    showConfirmModal("Delete this item?", () => {
        fetch("api/delete_checklist_item.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${itemId}`
        }).then(() => {
            item.remove();
            updateProgressCallback(); // تحديث شريط التقدم
        });
    });
});

  
    // تعديل العنصر
    item.querySelector(".edit-item").addEventListener("click", () => {
      const textSpan = item.querySelector(".item-text");
      const oldText = textSpan.textContent;
  
      const wrapper = document.createElement("div");
      wrapper.className = "flex items-center gap-2";
  
      const input = document.createElement("input");
      input.type = "text";
      input.value = oldText;
      input.className = "border rounded px-2 py-1 text-sm flex-1";
  
      const saveBtn = document.createElement("button");
      saveBtn.textContent = "Save";
      saveBtn.className = "bg-blue-500 text-white px-2 py-1 text-xs rounded";
  
      const cancelBtn = document.createElement("button");
      cancelBtn.textContent = "✕";
      cancelBtn.className = "text-red-500 text-xl px-2 py-1";
  
      wrapper.appendChild(input);
      wrapper.appendChild(saveBtn);
      wrapper.appendChild(cancelBtn);
  
      textSpan.replaceWith(wrapper);
  
      cancelBtn.addEventListener("click", () => {
        wrapper.replaceWith(textSpan);
      });
  
      saveBtn.addEventListener("click", () => {
        const newText = input.value.trim() || oldText;
        fetch("api/update_checklist_item.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `id=${itemId}&content=${encodeURIComponent(newText)}&is_checked=${item.querySelector(".check-item").checked ? 1 : 0}`
        });
  
        const newSpan = document.createElement("span");
        newSpan.className = "item-text";
        newSpan.textContent = newText;
        wrapper.replaceWith(newSpan);
      });
    });
  
    // حذف العنصر
    item.querySelector(".delete-item").addEventListener("click", () => {
      showConfirmModal("Delete this item?", () => {
        fetch("api/delete_checklist_item.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `id=${itemId}`
        }).then(() => item.remove());
      });
    });
  
    // تفعيل الشيك بوكس
    item.querySelector(".check-item").addEventListener("change", (e) => {
      fetch("api/update_checklist_item.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `id=${itemId}&content=${encodeURIComponent(content)}&is_checked=${e.target.checked ? 1 : 0}`
      });
    });
  }
  // دالة لتحميل التعليقات
async function loadComments(cardId) {
    try {
        const response = await fetch(`api/get_comments.php?card_id=${cardId}`);
        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.error || 'Failed to load comments');
        }

        const commentsList = document.getElementById('commentsList');
        commentsList.innerHTML = ''; // مسح التعليقات الحالية

        if (data.comments.length === 0) {
            commentsList.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No comments yet</p>';
            return;
        }

        // إنشاء عناصر التعليقات
        data.comments.forEach(comment => {
            const commentElement = createCommentElement(comment);
            commentsList.appendChild(commentElement);
        });

    } catch (error) {
        console.error('Error loading comments:', error);
        // يمكنك إظهار رسالة خطأ للمستخدم إذا لزم الأمر
    }
}

// دالة لإنشاء عنصر تعليق
function createCommentElement(comment) {
    const commentBox = document.createElement("div");
    commentBox.className = "flex items-start gap-3 mb-4";
    commentBox.dataset.commentId = comment.id;
    commentBox.dataset.authorId = comment.user_id;

    const userImg = document.createElement("img");
    userImg.src = comment.user_avatar || "default.jpg";
    userImg.className = "w-8 h-8 rounded-full object-cover";
    userImg.alt = comment.user_name;
    userImg.loading = "lazy";

    const commentBody = document.createElement("div");
    commentBody.className = "bg-gray-100 p-3 rounded-lg w-full";

    const commentHeader = document.createElement("div");
    commentHeader.className = "flex justify-between items-center mb-1";
    commentHeader.innerHTML = `
        <span class="font-medium text-sm">${comment.user_name}</span>
        <span class="text-xs text-gray-500">${formatDate(comment.created_at)}</span>
    `;

    const commentText = document.createElement("p");
    commentText.className = "text-sm text-gray-800";
    commentText.textContent = comment.content;

    // عناصر التعديل (مخفية في البداية)
    const editInput = document.createElement("textarea");
    editInput.className = "w-full border px-2 py-1 text-sm rounded hidden resize-none mt-1";
    editInput.value = comment.content;

    const actions = document.createElement("div");
    actions.className = "text-xs text-gray-500 mt-2 flex gap-3 items-center";

    // التحقق من ملكية التعليق
    const isCommentOwner = parseInt(comment.user_id) === parseInt(window.currentUserId);
    
    if (isCommentOwner) {
        const editBtn = document.createElement("button");
        editBtn.textContent = "Edit";
        editBtn.className = "hover:underline text-blue-600";
        
        const deleteBtn = document.createElement("button");
        deleteBtn.textContent = "Delete";
        deleteBtn.className = "hover:underline text-red-500";
        
        const saveBtn = document.createElement("button");
        saveBtn.innerHTML = '<i class="fas fa-check text-green-600"></i> Save';
        saveBtn.className = "hidden items-center gap-1 text-green-600 hover:text-green-800";
        
        const cancelBtn = document.createElement("button");
        cancelBtn.innerHTML = '<i class="fas fa-times text-red-500"></i> Cancel';
        cancelBtn.className = "hidden items-center gap-1 text-red-500 hover:text-red-700";
        
        actions.append(editBtn, deleteBtn, saveBtn, cancelBtn);
        
        // أحداث التعديل
        editBtn.addEventListener("click", () => {
            commentText.classList.add("hidden");
            editInput.classList.remove("hidden");
            saveBtn.classList.remove("hidden");
            cancelBtn.classList.remove("hidden");
            editBtn.classList.add("hidden");
            deleteBtn.classList.add("hidden");
        });
        
        // حدث الحفظ
        saveBtn.addEventListener("click", async () => {
            const newContent = editInput.value.trim();
            if (!newContent) return;
            
            try {
                const response = await fetch('api/update_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        comment_id: comment.id,
                        content: newContent
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Failed to update comment');
                }
                
                commentText.textContent = newContent;
                commentText.classList.remove("hidden");
                editInput.classList.add("hidden");
                saveBtn.classList.add("hidden");
                cancelBtn.classList.add("hidden");
                editBtn.classList.remove("hidden");
                deleteBtn.classList.remove("hidden");
                
            } catch (error) {
                console.error('Error updating comment:', error);
                alert('Error updating comment: ' + error.message);
            }
        });
        
        // حدث الإلغاء
        cancelBtn.addEventListener("click", () => {
            editInput.value = commentText.textContent;
            commentText.classList.remove("hidden");
            editInput.classList.add("hidden");
            saveBtn.classList.add("hidden");
            cancelBtn.classList.add("hidden");
            editBtn.classList.remove("hidden");
            deleteBtn.classList.remove("hidden");
        });
        
        // حدث الحذف
        deleteBtn.addEventListener("click", async () => {
            if (!confirm('Are you sure you want to delete this comment?')) return;
            
            try {
                const response = await fetch('api/delete_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        comment_id: comment.id
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Failed to delete comment');
                }
                
                commentBox.remove();
                
            } catch (error) {
                console.error('Error deleting comment:', error);
                alert('Error deleting comment: ' + error.message);
            }
        });
    } else {
        const replyBtn = document.createElement("button");
        replyBtn.textContent = "Reply";
        replyBtn.className = "hover:underline text-indigo-500";
        actions.append(replyBtn);
        
        replyBtn.addEventListener("click", () => {
            const commentInput = document.getElementById("commentInput");
            commentInput.focus();
            commentInput.value = `@${comment.user_name} `;
            commentInput.dataset.replyTo = comment.id;
            document.getElementById("commentActions").classList.remove("hidden");
        });
    }

    commentBody.append(commentHeader, commentText, editInput, actions);
    commentBox.append(userImg, commentBody);

    return commentBox;
}

// دالة لتنسيق التاريخ
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString(); // أو استخدام مكتبة مثل moment.js لتنسيق أفضل
}

// // استدعاء دالة تحميل التعليقات عند فتح المودال
// document.getElementById('cardDetailsModal').addEventListener('show', function() {
//     const cardId = this.dataset.cardId;
//     if (cardId) {
//         loadComments(cardId);
//     }
// });
const addListBtn = document.getElementById("addListBtn");
const listInputContainer = document.getElementById("listInputContainer");
const listTitleInput = document.getElementById("listTitleInput");
const confirmAddListBtn = document.getElementById("confirmAddListBtn");
const cancelAddListBtn = document.getElementById("cancelAddListBtn");
const kanbanContainer = document.getElementById("kanbanContainer");
const listControls = document.getElementById("listControls");

const deleteModal = document.getElementById("deleteModal");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

let listToDeleteElement = null;
let listToDeleteId = null;

const cardDeleteModal = document.getElementById("cardDeleteModal");
const confirmDeleteCardBtn = document.getElementById("confirmDeleteCardBtn");
const cancelDeleteCardBtn = document.getElementById("cancelDeleteCardBtn");

let cardToDeleteEl = null;
let cardToDeleteId = null;



// إظهار حقل إدخال العنوان
addListBtn.addEventListener("click", () => {
    addListBtn.classList.add("hidden");
    listInputContainer.classList.remove("hidden");
    listTitleInput.focus();
});

// إلغاء الإضافة
cancelAddListBtn.addEventListener("click", () => {
    listTitleInput.value = "";
    listInputContainer.classList.add("hidden");
    addListBtn.classList.remove("hidden");
});

// تأكيد الإضافة
// confirmAddListBtn.addEventListener("click", () => {
//     const title = listTitleInput.value.trim();
//     if (title === "") return;

//     // إنشاء عنصر القائمة
//     const listElement = document.createElement("div");
//     listElement.className = "bg-gray-100 min-w-[250px] rounded p-3 shadow";
//     listElement.innerHTML = `
//         <h3 class="text-lg font-semibold mb-3">${title}</h3>
//         <button class="text-blue-500 hover:underline">+ Add Card</button>
//     `;

//     // إضافة القائمة في البداية (أقصى اليسار)
//     kanbanContainer.prepend(listElement);

//     // إرجاع الوضع السابق
//     listTitleInput.value = "";
//     listInputContainer.classList.add("hidden");
//     addListBtn.classList.remove("hidden");

//     // وضع الزر Add List بعد آخر قائمة
//     listControls.remove();
//     kanbanContainer.appendChild(listControls);
// });

// add list to database 

confirmAddListBtn.addEventListener("click", () => {
    const title = listTitleInput.value.trim();
    if (title === "") return;

    const projectId = new URLSearchParams(window.location.search).get("id");

    fetch("api/add_list.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `project_id=${encodeURIComponent(projectId)}&name=${encodeURIComponent(title)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const listElement = document.createElement("div");
            listElement.className = "list bg-gray-100 min-w-[250px] rounded p-3 shadow";
            listElement.dataset.listId = data.list.id;


            listElement.innerHTML = `
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-semibold">${data.list.name}</h3>
        <div class="flex gap-1">
            <button class="edit-list text-gray-500 hover:text-blue-600" title="Edit">✏️</button>
            <button class="delete-list text-gray-500 hover:text-red-600" title="Delete">🗑️</button>
        </div>
    </div>
    <div class="card-section space-y-2">
    <!-- البطاقات تُضاف هنا -->
    <div class="cards"></div>

    <!-- زر إضافة بطاقة -->
    <button class="add-card-btn text-blue-500 hover:underline text-sm">+ Add Card</button>
</div>

`;


kanbanContainer.insertBefore(listElement, listControls);
// kanbanContainer.prepend(listElement);


            const titleElement = listElement.querySelector("h3");
attachListActions(listElement, data.list.id, titleElement);

            listTitleInput.value = "";
            listInputContainer.classList.add("hidden");
            addListBtn.classList.remove("hidden");

            // إعادة وضع زر Add List
            listControls.remove();
            kanbanContainer.appendChild(listControls);
        } else {
            alert("Failed to add list: " + data.message);
        }
    })
    // .catch(err => {
    //     console.error("Error:", err);
    //     alert("Something went wrong");
    // });
});


// get list 
document.addEventListener("DOMContentLoaded", () => {
    const projectId = new URLSearchParams(window.location.search).get("id");

    if (!projectId) return;

    fetch(`api/get_lists.php?project_id=${encodeURIComponent(projectId)}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                data.lists.forEach(list => {
                    const listElement = document.createElement("div");
                    listElement.className = "list bg-gray-100 min-w-[250px] rounded p-3 shadow";
                    listElement.dataset.listId = list.id;
                    listElement.innerHTML = `
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-semibold">${list.name}</h3>
        <div class="flex gap-1">
            <button class="edit-list text-gray-500 hover:text-blue-600" title="Edit">✏️</button>
            <button class="delete-list text-gray-500 hover:text-red-600" title="Delete">🗑️</button>
        </div>
    </div>
    <div class="card-section space-y-2">
    <!-- البطاقات تُضاف هنا -->
    <div class="cards"></div>

    <!-- زر إضافة بطاقة -->
    <button class="add-card-btn text-blue-500 hover:underline text-sm">+ Add Card</button>
</div>

`;

                    kanbanContainer.insertBefore(listElement, listControls);
                    const titleElement = listElement.querySelector("h3");
                    attachListActions(listElement, list.id, titleElement);

                     // ✅ الجزء الجديد لإظهار الكروت لكل قائمة:
                    const cardsContainer = listElement.querySelector(".cards");
                    fetch(`api/get_cards.php?list_id=${list.id}`)
                    .then(res => res.json())
                    .then(cardData => {
                        if (cardData.status === "success") {
                            cardData.cards.forEach(card => {
                                const cardEl = document.createElement("div");
                                cardEl.className = "bg-white p-2 rounded shadow-sm flex justify-between items-center text-sm hover:bg-gray-100 cursor-pointer transition";
                                cardEl.dataset.cardId = card.id; 
                                cardEl.innerHTML = `
                                    <span class="font-medium">${card.title}</span>
                                    <div class="flex gap-1">
                                        <button class="edit-card text-gray-400 text-xs hover:text-blue-500"><i class="fas fa-pen"></i></button>
                                        <button class="delete-card text-gray-400 text-xs hover:text-red-500"><i class="fas fa-trash"></i></button>
                                    </div>
                                `;
                                cardsContainer.appendChild(cardEl);
                                const titleSpan = cardEl.querySelector("span");
                                attachCardActions(cardEl, card.id, titleSpan);

                            });
                        }
                    })
                    .catch(err => console.error("Failed to load cards:", err));


                });

                // إعادة وضع زر Add List في النهاية
                listControls.remove();
                kanbanContainer.appendChild(listControls);
            } else {
                alert("Failed to load lists: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error loading lists:", error);
        });
});
function attachListActions(listElement, listId, listNameElement) {
    const editBtn = listElement.querySelector(".edit-list");
    const deleteBtn = listElement.querySelector(".delete-list");

deleteBtn.addEventListener("click", () => {
    listToDeleteElement = listElement;
    listToDeleteId = listId;
    deleteModal.classList.remove("hidden");
});



    editBtn.addEventListener("click", () => {
        const currentTitle = listNameElement.textContent;

        const editForm = document.createElement("div");
        editForm.className = "flex items-center gap-2 w-full";
        editForm.innerHTML = `
            <input type="text" class="form-control form-control-sm form-control border px-2 py-1 rounded" value="${currentTitle}">
            <button class="btn-save text-green-600 hover:text-green-800 text-lg">✔️</button>
            <button class="btn-cancel text-red-600 hover:text-red-800 text-lg">✖️</button>
        `;

        listNameElement.replaceWith(editForm);
        const input = editForm.querySelector("input");
        input.focus();
        input.select();

        editForm.querySelector(".btn-save").addEventListener("click", () => {
            const newTitle = input.value.trim();
            if (newTitle && newTitle !== currentTitle) {
                fetch("api/update_list.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `list_id=${listId}&name=${encodeURIComponent(newTitle)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        const newTitleElement = document.createElement("h3");
                        newTitleElement.className = "text-lg font-semibold";
                        newTitleElement.textContent = newTitle;
                        editForm.replaceWith(newTitleElement);
                        attachListActions(listElement, listId, newTitleElement);
                    } else {
                        alert("Error updating list title");
                    }
                });
            } else {
                editForm.replaceWith(listNameElement);
            }
        });

        editForm.querySelector(".btn-cancel").addEventListener("click", () => {
            editForm.replaceWith(listNameElement);
        });
    });

    
}

// delete list 
cancelDeleteBtn.addEventListener("click", () => {
    deleteModal.classList.add("hidden");
    listToDeleteElement = null;
    listToDeleteId = null;
});

confirmDeleteBtn.addEventListener("click", () => {
    if (!listToDeleteId) return;

    fetch("api/delete_list.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `list_id=${listToDeleteId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            listToDeleteElement.remove();
            deleteModal.classList.add("hidden");
        } else {
            alert("Failed to delete list");
        }
    })
    .catch(err => {
        console.error("Delete error:", err);
        alert("Something went wrong");
    })
    .finally(() => {
        listToDeleteElement = null;
        listToDeleteId = null;
    });
});


// add cards 
// تفعيل منطق إضافة البطاقات داخل كل قائمة
kanbanContainer.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-card-btn")) {
        const addBtn = e.target;
        const section = e.target.closest(".card-section");
        const cardsContainer = section.querySelector(".cards");

        // إخفاء زر الإضافة
        e.target.classList.add("hidden");

        // إنشاء نموذج الإدخال
        const form = document.createElement("div");
        form.className = "space-y-2";
        form.innerHTML = `
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" placeholder="Enter card title" />
            <div class="flex items-center gap-2">
                <button class="btn-confirm-add-card text-white bg-blue-500 px-2 py-1 rounded text-sm hover:bg-blue-600">Add Card</button>
                <button class="btn-cancel-add-card text-red-500 text-lg font-bold hover:text-red-700">×</button>
            </div>
        `;

        section.appendChild(form);

        // إلغاء الإضافة
        form.querySelector(".btn-cancel-add-card").addEventListener("click", () => {
            form.remove();
            e.target.classList.remove("hidden");
        });

        // تأكيد الإضافة
        form.querySelector(".btn-confirm-add-card").addEventListener("click", () => {
            const input = form.querySelector("input");
            const title = input.value.trim();
            if (title === "") return;
        
            const listElement = addBtn.closest(".list");
            const listId = listElement.dataset.listId;
        
            fetch("api/add_card.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `list_id=${encodeURIComponent(listId)}&title=${encodeURIComponent(title)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const card = document.createElement("div");
                    card.className = "bg-white p-2 rounded shadow-sm flex justify-between items-center text-sm hover:bg-gray-100 cursor-pointer transition";
                    card.dataset.cardId = data.card.id;
                    card.innerHTML = `
                        <span class="font-medium">${data.card.title}</span>
                        <div class="flex gap-1">
                            <button class="edit-card text-gray-400 text-xs hover:text-blue-500"><i class="fas fa-pen"></i></button>
                            <button class="delete-card text-gray-400 text-xs hover:text-red-500"><i class="fas fa-trash"></i></button>
                        </div>
                    `;

                    const titleSpan = card.querySelector("span");
                    attachCardActions(card, data.card.id, titleSpan);


                    cardsContainer.appendChild(card);
                    form.remove();
                    addBtn.classList.remove("hidden");
                } else {
                    alert("Failed to add card: " + data.message);
                }
            })
            .catch(err => {
                console.error("Add card error:", err);
                alert("Something went wrong");
            });
        });
        
        
    }
});

function attachCardActions(cardEl, cardId, titleSpan) {
    const editBtn = cardEl.querySelector(".edit-card");


    editBtn.addEventListener("click", () => {
        const currentTitle = titleSpan.textContent;

        const editForm = document.createElement("div");
        editForm.className = "flex items-center gap-2 w-full";
        editForm.innerHTML = `
            <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${currentTitle}">
            <button class="btn-save text-green-600 hover:text-green-800 text-sm">✔️</button>
            <button class="btn-cancel text-red-600 hover:text-red-800 text-sm">✖️</button>
        `;

        cardEl.replaceChild(editForm, titleSpan);
        const input = editForm.querySelector("input");
        input.focus();
        input.select();

        editForm.querySelector(".btn-save").addEventListener("click", () => {
            const newTitle = input.value.trim();
            if (newTitle && newTitle !== currentTitle) {
                fetch("api/update_card.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `card_id=${cardId}&title=${encodeURIComponent(newTitle)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        const newSpan = document.createElement("span");
                        newSpan.className = "font-medium";
                        newSpan.textContent = newTitle;
                        cardEl.replaceChild(newSpan, editForm);
                        attachCardActions(cardEl, cardId, newSpan);
                    } else {
                        alert("Failed to update card");
                    }
                });
            } else {
                cardEl.replaceChild(titleSpan, editForm);
            }
        });

        editForm.querySelector(".btn-cancel").addEventListener("click", () => {
            cardEl.replaceChild(titleSpan, editForm);
        });
    });
    const deleteBtn = cardEl.querySelector(".delete-card");
deleteBtn.addEventListener("click", () => {
    cardToDeleteEl = cardEl;
    cardToDeleteId = cardId;
    cardDeleteModal.classList.remove("hidden");
});

}
cancelDeleteCardBtn.addEventListener("click", () => {
    cardDeleteModal.classList.add("hidden");
    cardToDeleteEl = null;
    cardToDeleteId = null;
});

confirmDeleteCardBtn.addEventListener("click", () => {
    if (!cardToDeleteId) return;

    fetch("api/delete_card.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `card_id=${cardToDeleteId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            cardToDeleteEl.remove();
            cardDeleteModal.classList.add("hidden");
        } else {
            alert("Failed to delete card");
        }
    })
    .catch(err => {
        console.error("Delete card error:", err);
        alert("Something went wrong");
    })
    .finally(() => {
        cardToDeleteEl = null;
        cardToDeleteId = null;
    });
});
const cardDetailsModal = document.getElementById("cardDetailsModal");
const cardModalTitle = document.getElementById("cardModalTitle");
const closeCardModal = document.getElementById("closeCardModal");

// عند النقر على أي كارد، نفتح المودال
kanbanContainer.addEventListener("click", (e) => {
    const card = e.target.closest(".cards > div");

    if (card && !e.target.closest("button")) {
        const cardTitle = card.querySelector("span").textContent;
        cardModalTitle.textContent = cardTitle;
        cardDetailsModal.classList.remove("hidden");

        // جلب المرفقات
        fetch(`api/get_attachments.php?card_id=${cardId}`)
        .then(res => res.json())
        .then(data => {
        if (data.status === "success") {
            const container = document.getElementById("attachmentsDisplay");
            container.innerHTML = "";
            data.attachments.forEach(att => {
            renderAttachment({ type: att.type, title: att.title, url: att.url });
            });
        }
        });

    }
});

// زر الإغلاق
closeCardModal.addEventListener("click", () => {
    cardDetailsModal.classList.add("hidden");
});

const descriptionDisplay = document.getElementById("descriptionDisplay");
const descriptionEditor = document.getElementById("descriptionEditor");
const descriptionInput = document.getElementById("descriptionInput");
const saveDescriptionBtn = document.getElementById("saveDescriptionBtn");
const cancelDescriptionBtn = document.getElementById("cancelDescriptionBtn");

let currentDescription = "";

// فتح المودال عند الضغط على البطاقة
kanbanContainer.addEventListener("click", function(e) {
    const cardEl = e.target.closest(".bg-white");
    if (cardEl && cardEl.classList.contains("bg-white")) {
        const title = cardEl.querySelector("span").textContent;
        const cardId = cardEl.dataset.cardId; // ← مهم جدًا

        currentCardId = cardId;
        cardModalTitle.textContent = title;

        // جلب الوصف من قاعدة البيانات
        fetch(`api/get_description.php?card_id=${cardId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    currentDescription = data.description || "";
                    updateDescriptionDisplay(currentDescription);
                }
            });

        cardDetailsModal.classList.remove("hidden");

        cardDetailsModal.dataset.cardId = cardId;

        fetch(`api/get_date.php?card_id=${cardId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    startText.textContent = data.dates.start_date || "";
                    dueText.textContent = data.dates.due_date || "";
                    reminderText.textContent = data.dates.due_reminder || "";
                    metaDisplay.classList.remove("hidden");
                } else {
                    metaDisplay.classList.add("hidden");
                }
            });

            // جلب أعضاء البطاقة
            fetch(`api/get_card_members.php?card_id=${cardId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    selectedMembersContainer.innerHTML = "";
                    data.members.forEach(member => {
                        const div = document.createElement("div");
                        div.className = "flex items-center gap-1 border border-gray-300 rounded px-2 py-1 bg-gray-50 text-sm";
                        div.innerHTML = `<img src="${member.profile_picture}" class="w-5 h-5 rounded-full"><span>${member.username}</span>`;
                        selectedMembersContainer.appendChild(div);
                    });
                    selectedMembersContainer.classList.remove("hidden");
                } else {
                    selectedMembersContainer.classList.add("hidden");
                }

                
            });

            // جلب حالة البطاقة
            fetch(`api/get_status.php?card_id=${cardId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const status = data.card_status;
                    if (status) {
                        statusText.textContent = status;
                        statusDisplay.classList.remove("hidden");
                    } else {
                        statusDisplay.classList.add("hidden");
                    }
                }

                if (data.completed_at) {
                    document.getElementById("completedAtText").textContent = data.completed_at;
                    document.getElementById("completedAtDisplay").classList.remove("hidden");
                } else {
                    document.getElementById("completedAtDisplay").classList.add("hidden");
                }
                
                
                
            });
            // دالة لتحميل وعرض المرفقات عند فتح الـ modal
            function loadAttachments(cardId) {
            fetch(`api/get_attachments.php?card_id=${cardId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const attachmentsContainer = document.getElementById('attachmentsDisplay');
                    attachmentsContainer.innerHTML = ''; // مسح المحتوى القديم
                    
                    data.attachments.forEach(attachment => {
                        renderAttachment({
                            id: attachment.id,
                            type: attachment.type,
                            title: attachment.title,
                            url: attachment.path
                        });
                });
            }
        })
        .catch(error => {
            console.error('Error loading attachments:', error);
        });
}
loadAttachments(cardId);

     // داخل event listener لفتح الـ modal
     fetch(`api/get_checklist.php?card_id=${cardId}`)
     .then(res => res.json())
     .then(data => {
         if (data.status === "success") {
             const checklistsContainer = document.getElementById("checklistsContainer");
             checklistsContainer.innerHTML = ""; // مسح جميع الـ checklists القديمة
             
             data.checklists.forEach(cl => {
                 renderChecklist(cl.id, cl.title, cl.progress);
             });
         }
     });

        loadComments(cardId);
        
    }

   
});

// إغلاق المودال
closeCardModal.addEventListener("click", () => {
    cardDetailsModal.classList.add("hidden");
    descriptionEditor.classList.add("hidden");
    descriptionDisplay.classList.remove("hidden");
});

// عند النقر على عرض الوصف
descriptionDisplay.addEventListener("click", () => {
    descriptionInput.value = currentDescription;
    descriptionDisplay.classList.add("hidden");
    descriptionEditor.classList.remove("hidden");
    descriptionInput.focus();
    descriptionInput.select();
});

// عند الحفظ
saveDescriptionBtn.addEventListener("click", () => {
    const text = descriptionInput.value.trim();
    currentDescription = text;

    // حفظ في قاعدة البيانات
    fetch("api/add_or_update_description.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `card_id=${encodeURIComponent(currentCardId)}&description=${encodeURIComponent(text)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            updateDescriptionDisplay(text);
            descriptionEditor.classList.add("hidden");
            descriptionDisplay.classList.remove("hidden");
        } else {
            alert("Failed to save description");
        }
    });
});


// عند الإلغاء
cancelDescriptionBtn.addEventListener("click", () => {
    descriptionEditor.classList.add("hidden");
    descriptionDisplay.classList.remove("hidden");
});

// تحديث عرض الوصف
function updateDescriptionDisplay(text) {
    if (text && text.trim() !== "") {
        descriptionDisplay.textContent = text;
        descriptionDisplay.classList.remove("text-gray-400", "italic");
    } else {
        descriptionDisplay.textContent = "Write description here...";
        descriptionDisplay.classList.add("text-gray-400", "italic");
    }
}


const datesToggle = document.getElementById("datesToggle");
const datesForm = document.getElementById("datesForm");
const startInput = document.getElementById("startDateInput");
const dueInput = document.getElementById("dueDateInput");
const reminderSelect = document.getElementById("dueReminderSelect");
const saveDatesBtn = document.getElementById("saveDatesBtn");
const cancelDatesBtn = document.getElementById("cancelDatesBtn");

const metaDisplay = document.getElementById("cardMetaDisplay");
const startText = document.getElementById("startDateText");
const dueText = document.getElementById("dueDateText");
const reminderText = document.getElementById("dueReminderText");

let projectStart = null;
let projectEnd = null;

// جلب تواريخ المشروع
const projectId = new URLSearchParams(window.location.search).get("id");
fetch(`api/get_project_dates.php?id=${projectId}`)
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            projectStart = new Date(data.dates.start_date);
            projectEnd = new Date(data.dates.end_date);
        }
    });

// إظهار نموذج إدخال التاريخ
datesToggle.addEventListener("click", () => {
    datesForm.classList.remove("hidden");
});

// إلغاء
cancelDatesBtn.addEventListener("click", () => {
    datesForm.classList.add("hidden");
});

// حفظ
saveDatesBtn.addEventListener("click", () => {
    
    const start = new Date(startInput.value);
    const due = new Date(dueInput.value);
    const reminder = reminderSelect.value;

    // إخفاء الأخطاء أولاً
document.getElementById("startDateError").classList.add("hidden");
document.getElementById("dueDateError").classList.add("hidden");

let hasError = false;

if (start < projectStart || start > projectEnd) {
    document.getElementById("startDateError").textContent = `Start date must be between ${projectStart.toDateString()} and ${projectEnd.toDateString()}.`;
    document.getElementById("startDateError").classList.remove("hidden");
    hasError = true;
}

if (due < projectStart || due > projectEnd) {
    document.getElementById("dueDateError").textContent = `Due date must be between ${projectStart.toDateString()} and ${projectEnd.toDateString()}.`;
    document.getElementById("dueDateError").classList.remove("hidden");
    hasError = true;
}

// التحقق من أن الحقول غير فارغة
if (!startInput.value) {
    document.getElementById("startDateError").textContent = "Start date is required.";
    document.getElementById("startDateError").classList.remove("hidden");
    hasError = true;
}

if (!dueInput.value) {
    document.getElementById("dueDateError").textContent = "Due date is required.";
    document.getElementById("dueDateError").classList.remove("hidden");
    hasError = true;
}


if (hasError) return; // لا تكمل الحفظ


// إرسال البيانات إلى الباك اند
const cardId = cardDetailsModal.dataset.cardId;

fetch("api/add_or_update_date.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `card_id=${cardId}&start_date=${startInput.value}&due_date=${dueInput.value}&due_reminder=${encodeURIComponent(reminder)}`
})
.then(res => res.json())
.then(data => {
    if (data.status === "success") {
        // تحديث العرض بعد الحفظ
        startText.textContent = startInput.value;
        dueText.textContent = dueInput.value;
        reminderText.textContent = reminder;
        metaDisplay.classList.remove("hidden");
        datesForm.classList.add("hidden");
    } else {
        alert("Failed to save dates");
    }
});


    // عرض التواريخ
    startText.textContent = startInput.value;
    dueText.textContent = dueInput.value;
    reminderText.textContent = reminder;
    metaDisplay.classList.remove("hidden");
    datesForm.classList.add("hidden");


});
const membersToggle = document.getElementById("membersToggle");
const membersForm = document.getElementById("membersForm");
const membersList = document.getElementById("membersList");
const saveMembersBtn = document.getElementById("saveMembersBtn");
const cancelMembersBtn = document.getElementById("cancelMembersBtn");
const selectedMembersContainer = document.getElementById("selectedMembers");

membersToggle.addEventListener("click", () => {
    membersForm.classList.remove("hidden");

    // جلب أعضاء المشروع مرة واحدة فقط
    if (membersList.childElementCount === 0) {
        fetch(`api/get_project_members.php?id=${projectId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    data.members.forEach(member => {
                        const item = document.createElement("label");
                        item.className = "flex items-center gap-2 cursor-pointer";
                        item.innerHTML = `
                            <input type="checkbox" value="${member.username}" class="member-checkbox">
                            <img src="${member.profile_picture}" alt="${member.username}" class="w-6 h-6 rounded-full object-cover">
                            <span>${member.username}</span>
                        `;
                        membersList.appendChild(item);
                    });
                }
            });
    }
});
cancelMembersBtn.addEventListener("click", () => {
    membersForm.classList.add("hidden");
});
saveMembersBtn.addEventListener("click", () => {
    const selected = [...document.querySelectorAll(".member-checkbox:checked")].map(cb => ({
        username: cb.value,
        image: cb.nextElementSibling.src
    }));

    selectedMembersContainer.innerHTML = "";
    selected.forEach(member => {
        const div = document.createElement("div");
        div.className = "flex items-center gap-1 border border-gray-300 rounded px-2 py-1 bg-gray-50 text-sm";
        div.innerHTML = `<img src="${member.image}" class="w-5 h-5 rounded-full"><span>${member.username}</span>`;
        selectedMembersContainer.appendChild(div);
    });

    selectedMembersContainer.classList.remove("hidden");
    membersForm.classList.add("hidden");

    const cardId = cardDetailsModal.dataset.cardId;

    // فقط الأسماء
    const selectedUsernames = selected.map(member => member.username);

    // تجهيز body للإرسال (x-www-form-urlencoded)
    const bodyData = `card_id=${encodeURIComponent(cardId)}&usernames[]=` + selectedUsernames.map(name => encodeURIComponent(name)).join("&usernames[]=");

    fetch("api/add_or_update_members.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: bodyData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            console.log("Members saved!");
        } else {
            console.error("Failed to save members:", data.message);
        }
    })
    .catch(err => {
        console.error("Request failed:", err);
    });

});


const statusToggle = document.getElementById("statusToggle");
const statusForm = document.getElementById("statusForm");
const statusSelect = document.getElementById("statusSelect");
const saveStatusBtn = document.getElementById("saveStatusBtn");
const cancelStatusBtn = document.getElementById("cancelStatusBtn");
const statusDisplay = document.getElementById("statusDisplay");
const statusText = document.getElementById("statusText");

// فتح النموذج
statusToggle.addEventListener("click", () => {
    statusForm.classList.remove("hidden");
});

// إلغاء
cancelStatusBtn.addEventListener("click", () => {
    statusForm.classList.add("hidden");
});

// حفظ
saveStatusBtn.addEventListener("click", () => {
    const selectedStatus = statusSelect.value;
    const cardId = cardDetailsModal.dataset.cardId;

    fetch("api/add_or_update_status.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `card_id=${cardId}&status=${encodeURIComponent(selectedStatus)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            statusText.textContent = selectedStatus;
            statusDisplay.classList.remove("hidden");
            statusForm.classList.add("hidden");
        } else {
            alert("Failed to update status");
        }
    });

    fetch("api/update_status.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `card_id=${cardId}&status=${selectedStatus}`
    })
    // ✅ عرض تاريخ الإكمال إذا الحالة Done
    if (selectedStatus === "Done") {
        const now = new Date();
        document.getElementById("completedAtText").textContent = now.toLocaleString();
        document.getElementById("completedAtDisplay").classList.remove("hidden");
    } else {
        document.getElementById("completedAtDisplay").classList.add("hidden");
    }
});
const checklistToggle = document.getElementById("checklistToggle");
const checklistForm = document.getElementById("checklistForm");
const checklistTitleInput = document.getElementById("checklistTitleInput");
const saveChecklistTitleBtn = document.getElementById("saveChecklistTitleBtn");
const cancelChecklistTitleBtn = document.getElementById("cancelChecklistTitleBtn");
const checklistsContainer = document.getElementById("checklistsContainer");

checklistToggle.addEventListener("click", () => {
  checklistForm.classList.remove("hidden");
  checklistTitleInput.focus();
});

cancelChecklistTitleBtn.addEventListener("click", () => {
  checklistForm.classList.add("hidden");
  checklistTitleInput.value = "";
});

saveChecklistTitleBtn.addEventListener("click", () => {
    const title = checklistTitleInput.value.trim();
    const cardId = cardDetailsModal.dataset.cardId;
    if (!title) return;
  
    fetch("api/add_checklist.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `card_id=${cardId}&title=${encodeURIComponent(title)}`
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          const checklistId = data.checklist_id;
  
          const checklistDiv = document.createElement("div");
          checklistDiv.className = "bg-gray-50 border rounded p-3";
          checklistDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
              <h4 class="font-semibold text-lg checklist-title">${title}</h4>
              <div class="flex gap-2 text-gray-500">
                <i class="fas fa-pen cursor-pointer hover:text-blue-600 edit-checklist" title="Edit"></i>
                <i class="fas fa-trash cursor-pointer hover:text-red-600 delete-checklist" title="Delete"></i>
              </div>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded mb-1 relative">
              <div class="h-full bg-green-500 rounded" style="width: 0%"></div>
              <span class="absolute right-1 top-[-18px] text-xs font-semibold text-gray-700 progress-percent">0%</span>
            </div>
            <div class="checklist-items space-y-1 mb-2"></div>
            <div class="add-item-section text-blue-600 text-sm cursor-pointer hover:underline">+ Add an item</div>
            <div class="item-input-form hidden space-y-1">
              <input type="text" class="w-full border rounded px-2 py-1 text-sm" placeholder="Enter item title">
              <div class="flex gap-2">
                <button class="add-item-btn bg-blue-500 text-white px-2 py-1 rounded text-sm">Add</button>
                <button class="cancel-item-btn text-red-500 text-sm">Cancel</button>
              </div>
            </div>
          `;
  
          checklistsContainer.appendChild(checklistDiv);
          checklistForm.classList.add("hidden");
          checklistTitleInput.value = "";
  
          // --- التفاعل داخل الواجهة ---
          const editIcon = checklistDiv.querySelector(".edit-checklist");
          const deleteIcon = checklistDiv.querySelector(".delete-checklist");
          const checklistTitle = checklistDiv.querySelector(".checklist-title");
          const addItemBtn = checklistDiv.querySelector(".add-item-section");
          const itemForm = checklistDiv.querySelector(".item-input-form");
          const itemInput = itemForm.querySelector("input");
          const confirmBtn = itemForm.querySelector(".add-item-btn");
          const cancelBtn = itemForm.querySelector(".cancel-item-btn");
          const itemsContainer = checklistDiv.querySelector(".checklist-items");
          const progressBar = checklistDiv.querySelector(".bg-green-500");
          const progressPercent = checklistDiv.querySelector(".progress-percent");
  
          let totalItems = 0;
          let completedItems = 0;
  
          // تعديل العنوان
          editIcon.addEventListener("click", () => {
            const oldTitle = checklistTitle.textContent;
  
            const wrapper = document.createElement("div");
            wrapper.className = "flex items-center gap-2";
  
            const input = document.createElement("input");
            input.type = "text";
            input.value = oldTitle;
            input.className = "border rounded px-2 py-1 text-sm font-semibold flex-1";
  
            const saveBtn = document.createElement("button");
            saveBtn.textContent = "Save";
            saveBtn.className = "bg-blue-500 text-white px-2 py-1 text-xs rounded";
  
            const cancelBtn = document.createElement("button");
            cancelBtn.textContent = "✕";
            cancelBtn.className = "text-red-500 text-xl px-2 py-1";
  
            wrapper.appendChild(input);
            wrapper.appendChild(saveBtn);
            wrapper.appendChild(cancelBtn);
  
            checklistTitle.replaceWith(wrapper);
  
            cancelBtn.addEventListener("click", () => {
              wrapper.replaceWith(checklistTitle);
            });
  
            saveBtn.addEventListener("click", () => {
              const newTitle = input.value.trim() || oldTitle;
              checklistTitle.textContent = newTitle;
              wrapper.replaceWith(checklistTitle);
  
              // حفظ في قاعدة البيانات
              fetch("api/update_checklist.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${checklistId}&title=${encodeURIComponent(newTitle)}`
              });
            });
          });
  
          // حذف
          deleteIcon.addEventListener("click", () => {
            showConfirmModal("Are you sure you want to delete this checklist?", () => {
              checklistDiv.remove();
              fetch("api/delete_checklist.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${checklistId}`
              });
            });
          });
  
          // إضافة عنصر
          addItemBtn.addEventListener("click", () => {
            addItemBtn.classList.add("hidden");
            itemForm.classList.remove("hidden");
            itemInput.focus();
          });
  
          cancelBtn.addEventListener("click", () => {
            itemForm.classList.add("hidden");
            addItemBtn.classList.remove("hidden");
            itemInput.value = "";
          });
  
          confirmBtn.addEventListener("click", () => {
            const itemText = itemInput.value.trim();
            if (!itemText) return;
  
            const item = document.createElement("div");
            item.className = "flex items-center justify-between gap-2 text-sm";
            item.innerHTML = `
              <div class="flex items-center gap-2">
                <input type="checkbox" class="check-item w-4 h-4">
                <span class="item-text">${itemText}</span>
              </div>
              <div class="flex gap-2 text-gray-400 text-sm">
                <i class="fas fa-pen cursor-pointer hover:text-blue-600 edit-item" title="Edit"></i>
                <i class="fas fa-trash cursor-pointer hover:text-red-600 delete-item" title="Delete"></i>
              </div>
            `;
  
            itemsContainer.appendChild(item);
            totalItems++;
            updateProgress();
  
            item.querySelector(".check-item").addEventListener("change", (e) => {
              if (e.target.checked) completedItems++;
              else completedItems--;
              updateProgress();
            });
  
            item.querySelector(".delete-item").addEventListener("click", () => {
              showConfirmModal("Delete this item?", () => {
                if (item.querySelector(".check-item").checked) completedItems--;
                totalItems--;
                item.remove();
                updateProgress();
              });
            });
  
            item.querySelector(".edit-item").addEventListener("click", () => {
              const textSpan = item.querySelector(".item-text");
              const oldText = textSpan.textContent;
  
              const wrapper = document.createElement("div");
              wrapper.className = "flex items-center gap-2";
  
              const input = document.createElement("input");
              input.type = "text";
              input.value = oldText;
              input.className = "border rounded px-2 py-1 text-sm flex-1";
  
              const saveBtn = document.createElement("button");
              saveBtn.textContent = "Save";
              saveBtn.className = "bg-blue-500 text-white px-2 py-1 text-xs rounded";
  
              const cancelBtn = document.createElement("button");
              cancelBtn.textContent = "✕";
              cancelBtn.className = "text-red-500 text-xl px-2 py-1";
  
              wrapper.appendChild(input);
              wrapper.appendChild(saveBtn);
              wrapper.appendChild(cancelBtn);
  
              textSpan.replaceWith(wrapper);
  
              cancelBtn.addEventListener("click", () => {
                wrapper.replaceWith(textSpan);
              });
  
              saveBtn.addEventListener("click", () => {
                const newText = input.value.trim() || oldText;
                const newSpan = document.createElement("span");
                newSpan.className = "item-text";
                newSpan.textContent = newText;
                wrapper.replaceWith(newSpan);
              });
            });
  
            itemForm.classList.add("hidden");
            addItemBtn.classList.remove("hidden");
            itemInput.value = "";
          });
  
          function updateProgress() {
            if (totalItems === 0) {
              progressBar.style.width = "0%";
              progressPercent.textContent = "0%";
              return;
            }
            const percent = Math.round((completedItems / totalItems) * 100);
            progressBar.style.width = percent + "%";
            progressPercent.textContent = percent + "%";
          }
        }
      });
  });
  
  
  function showConfirmModal(message, onConfirm) {
    const modal = document.getElementById("confirmModal");
    document.getElementById("confirmMessage").textContent = message;
    modal.classList.remove("hidden", "items-center", "justify-center");
    modal.classList.add("flex");
  
    const yesBtn = document.getElementById("confirmYesBtn");
    const noBtn = document.getElementById("confirmNoBtn");
  
    const cleanup = () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
      yesBtn.removeEventListener("click", confirmHandler);
      noBtn.removeEventListener("click", cleanup);
    };
  
    const confirmHandler = () => {
      onConfirm();
      cleanup();
    };
  
    yesBtn.addEventListener("click", confirmHandler);
    noBtn.addEventListener("click", cleanup);
  }
  


//   Attachment

function renderAttachment({ id, type, title, url }) {
    const container = document.getElementById("attachmentsDisplay");
    const wrapper = document.createElement("div");
    wrapper.className = "border p-3 rounded-lg mb-3 bg-gray-50 flex flex-col";
    wrapper.dataset.attachmentId = id;

    // Preview Section
    const previewSection = document.createElement("div");
    previewSection.className = "mb-3";

    if (type === "url") {
        // For URLs (show as clickable link)
        const linkPreview = document.createElement("div");
        linkPreview.className = "flex items-center gap-2 p-2 bg-blue-50 rounded";
        
        const linkIcon = document.createElement("i");
        linkIcon.className = "fas fa-link text-blue-500";
        
        const linkText = document.createElement("a");
        linkText.href = url;
        linkText.textContent = title;
        linkText.className = "text-blue-600 hover:underline truncate";
        linkText.target = "_blank";
        
        linkPreview.append(linkIcon, linkText);
        previewSection.appendChild(linkPreview);
    } else {
        // For files - detect type and show appropriate preview
        const fileExt = url.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt);
        const isVideo = ['mp4', 'webm', 'ogg'].includes(fileExt);
        const isAudio = ['mp3', 'wav', 'ogg'].includes(fileExt);
        const isPDF = fileExt === 'pdf';

        if (isImage) {
            const imgPreview = document.createElement("div");
            imgPreview.className = "flex justify-center";
            
            const img = document.createElement("img");
            img.src = url;
            img.alt = title;
            img.className = "max-h-48 rounded object-contain border";
            img.style.maxWidth = "100%";
            
            imgPreview.appendChild(img);
            previewSection.appendChild(imgPreview);
        } 
        else if (isVideo) {
            const videoPreview = document.createElement("div");
            videoPreview.className = "flex justify-center";
            
            const video = document.createElement("video");
            video.src = url;
            video.controls = true;
            video.className = "max-h-48 rounded";
            video.style.maxWidth = "100%";
            
            videoPreview.appendChild(video);
            previewSection.appendChild(videoPreview);
        }
        else if (isAudio) {
            const audioPreview = document.createElement("div");
            audioPreview.className = "w-full";
            
            const audio = document.createElement("audio");
            audio.src = url;
            audio.controls = true;
            audio.className = "w-full";
            
            audioPreview.appendChild(audio);
            previewSection.appendChild(audioPreview);
        }
        else if (isPDF) {
            const pdfPreview = document.createElement("div");
            pdfPreview.className = "flex flex-col items-center";
            
            const pdfIcon = document.createElement("i");
            pdfIcon.className = "fas fa-file-pdf text-red-500 text-4xl mb-2";
            
            const pdfLink = document.createElement("a");
            pdfLink.href = url;
            pdfLink.textContent = title;
            pdfLink.className = "text-blue-600 hover:underline";
            pdfLink.target = "_blank";
            
            pdfPreview.append(pdfIcon, pdfLink);
            previewSection.appendChild(pdfPreview);
        }
        else {
            // Generic file preview
            const filePreview = document.createElement("div");
            filePreview.className = "flex flex-col items-center";
            
            const fileIcon = document.createElement("i");
            fileIcon.className = "fas fa-file text-gray-500 text-4xl mb-2";
            
            const fileLink = document.createElement("a");
            fileLink.href = url;
            fileLink.textContent = title;
            fileLink.className = "text-blue-600 hover:underline";
            fileLink.target = "_blank";
            
            filePreview.append(fileIcon, fileLink);
            previewSection.appendChild(filePreview);
        }
    }

    // Actions Section
    const actionsSection = document.createElement("div");
    actionsSection.className = "flex justify-between items-center mt-2 text-sm";

    const titleElement = document.createElement("span");
    titleElement.textContent = title;
    titleElement.className = "text-gray-700 font-medium truncate flex-1";

    const buttonsContainer = document.createElement("div");
    buttonsContainer.className = "flex gap-2";

    const downloadBtn = document.createElement("a");
    downloadBtn.href = url;
    downloadBtn.download = title;
    downloadBtn.innerHTML = '<i class="fas fa-download"></i>';
    downloadBtn.className = "text-green-600 hover:text-green-800";
    downloadBtn.title = "Download";

    const deleteBtn = document.createElement("button");
    deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
    deleteBtn.className = "text-red-500 hover:text-red-700";
    deleteBtn.title = "Delete";
    deleteBtn.onclick = () => {
        if (confirm("Are you sure you want to delete this attachment?")) {
            fetch("api/delete_attachment.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    wrapper.remove();
                }
            });
        }
    };

    buttonsContainer.append(downloadBtn, deleteBtn);
    actionsSection.append(titleElement, buttonsContainer);

    // Append all sections to wrapper
    wrapper.append(previewSection, actionsSection);
    container.appendChild(wrapper);
}
  
  
// Attachment Handling
const attachmentToggle = document.getElementById("attachmentToggle");
const attachmentForm = document.getElementById("attachmentForm");
const fileOptionBtn = document.getElementById("fileOptionBtn");
const urlOptionBtn = document.getElementById("urlOptionBtn");
const fileUploadSection = document.getElementById("fileUploadSection");
const urlSection = document.getElementById("urlSection");
const cancelAttachmentBtn = document.getElementById("cancelAttachmentBtn");
const addAttachmentBtn = document.getElementById("addAttachmentBtn");

let currentAttachmentType = "file"; // Default to file upload

attachmentToggle.addEventListener("click", () => {
    attachmentForm.classList.toggle("hidden");
    fileUploadSection.classList.add("hidden");
    urlSection.classList.add("hidden");
    document.getElementById("attachmentError").classList.add("hidden");
    document.getElementById("attachmentErrorUrl").classList.add("hidden");
});

fileOptionBtn.addEventListener("click", () => {
    currentAttachmentType = "file";
    fileUploadSection.classList.remove("hidden");
    urlSection.classList.add("hidden");
});

urlOptionBtn.addEventListener("click", () => {
    currentAttachmentType = "url";
    urlSection.classList.remove("hidden");
    fileUploadSection.classList.add("hidden");
});

cancelAttachmentBtn.addEventListener("click", () => {
    attachmentForm.classList.add("hidden");
});

document.addEventListener("change", (e) => {
    if (e.target.id === "attachmentFile") {
        const file = e.target.files[0];
        const display = document.getElementById("selectedFileName");
        if (file && display) {
            display.textContent = "Selected: " + file.name;
        }
    }
});

addAttachmentBtn.addEventListener("click", () => {
    const errorDiv = currentAttachmentType === "file" 
        ? document.getElementById("attachmentError")
        : document.getElementById("attachmentErrorUrl");
        
    errorDiv.classList.add("hidden");

    const cardId = cardDetailsModal.dataset.cardId;

    if (currentAttachmentType === "url") {
        const title = document.getElementById("linkTitle").value.trim();
        const url = document.getElementById("linkURL").value.trim();

        if (!title || !url) {
            errorDiv.textContent = "Please enter a valid title and URL.";
            errorDiv.classList.remove("hidden");
            return;
        }

        fetch("api/add_attachment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `card_id=${cardId}&type=url&title=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    renderAttachment({ type: "url", title, url });
                    attachmentForm.classList.add("hidden");
                    document.getElementById("linkTitle").value = "";
                    document.getElementById("linkURL").value = "";
                } else {
                    errorDiv.textContent = data.message || "Failed to save link.";
                    errorDiv.classList.remove("hidden");
                }
            });
        return;
    }

    // File upload handling
    const fileInput = document.getElementById("attachmentFile");
    const file = fileInput?.files[0];

    if (!file) {
        errorDiv.textContent = "Please select a file.";
        errorDiv.classList.remove("hidden");
        return;
    }

    const formData = new FormData();
    formData.append("card_id", cardId);
    formData.append("type", "file"); // We'll use "file" as the type for all uploads
    formData.append("file", fileInput.files[0]);
    formData.append("title", file.name);

    fetch("api/upload_attachment.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                renderAttachment({ type: "file", title: file.name, url: data.path });
                attachmentForm.classList.add("hidden");
                fileInput.value = ""; // Clear file input
                document.getElementById("selectedFileName").textContent = "";
            } else {
                errorDiv.textContent = data.message || "Upload failed.";
                errorDiv.classList.remove("hidden");
            }
        });
});



//-------------  comments --------- //--
const currentUserAvatar = document.querySelector('#cardDetailsModal img[alt="User"]')?.src || "default.jpg";
const currentUserName = window.currentUserName; // الآن سيتم جلب الاسم من المتغير العام
const currentUserId = window.currentUserId; // تم تمريره من PHP

window.currentCardId = parseInt(document.getElementById("cardDetailsModal")?.dataset.cardId || 0);


document.addEventListener("DOMContentLoaded", function () {
    // Comments section logic
    const commentInput = document.getElementById("commentInput");
    const commentActions = document.getElementById("commentActions");
    const sendCommentBtn = document.getElementById("sendCommentBtn");
    const cancelCommentBtn = document.getElementById("cancelCommentBtn");
    const commentsList = document.getElementById("commentsList");
  
    if (commentInput && commentActions && sendCommentBtn && cancelCommentBtn && commentsList) {
      // تحقق من أن المتغيرات العالمية موجودة
      if (!window.currentUserId || !window.currentUserName || !window.currentUserAvatar) {
        console.error("User data is not properly initialized");
        return;
      }
  
      const currentUserAvatar = window.currentUserAvatar || "default.jpg";
      const currentUserName = window.currentUserName || "User";
      const currentUserId = parseInt(window.currentUserId) || 0;
  
      commentInput.addEventListener("focus", () => {
        commentActions.classList.remove("hidden");
      });
  
      cancelCommentBtn.addEventListener("click", () => {
        commentInput.value = "";
        commentActions.classList.add("hidden");
      });
  
      sendCommentBtn.addEventListener("click", () => {
        const content = commentInput.value.trim();
        if (!content) return;

// إرسال البيانات إلى الخادم
fetch('api/add_comment.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        card_id: window.currentCardId,
        content: content,
        parent_id: commentInput.dataset.replyTo || null
    })
})

        
  
        const commentBox = document.createElement("div");
        commentBox.className = "flex items-start gap-3 mb-4";
        commentBox.dataset.authorId = currentUserId;
  
        const userImg = document.createElement("img");
        userImg.src = currentUserAvatar;
        userImg.className = "w-8 h-8 rounded-full object-cover";
        userImg.alt = "User";
  
        const commentBody = document.createElement("div");
        commentBody.className = "bg-gray-100 p-3 rounded-lg w-full";
  
        const commentHeader = document.createElement("div");
        commentHeader.className = "flex justify-between items-center mb-1";
        commentHeader.innerHTML = `
          <span class="font-medium text-sm">${currentUserName}</span>
          <span class="text-xs text-gray-500">Just now</span>
        `;
  
        const commentText = document.createElement("p");
        commentText.className = "text-sm text-gray-800";
        commentText.textContent = content;
  
        const editInput = document.createElement("textarea");
        editInput.className = "w-full border px-2 py-1 text-sm rounded hidden resize-none mt-1";
        editInput.value = content;
  
        const actions = document.createElement("div");
        actions.className = "text-xs text-gray-500 mt-2 flex gap-3 items-center";
  
        // إنشاء الأزرار
        const editBtn = document.createElement("button");
        editBtn.textContent = "Edit";
        editBtn.className = "hover:underline text-blue-600";
  
        const deleteBtn = document.createElement("button");
        deleteBtn.textContent = "Delete";
        deleteBtn.className = "hover:underline text-red-500";
  
        const saveBtn = document.createElement("button");
        saveBtn.innerHTML = `<i class="fas fa-check text-green-600"></i>`;
        saveBtn.className = "hidden";
  
        const cancelBtn = document.createElement("button");
        cancelBtn.innerHTML = `<i class="fas fa-times text-red-500"></i>`;
        cancelBtn.className = "hidden";
  
        const replyBtn = document.createElement("button");
        replyBtn.textContent = "Reply";
        replyBtn.className = "hover:underline text-indigo-500";
  
        // التحقق من ملكية التعليق
        const isCommentOwner = parseInt(commentBox.dataset.authorId) === currentUserId;
        
        if (isCommentOwner) {
          actions.append(editBtn, deleteBtn, saveBtn, cancelBtn);
        } else {
          actions.append(replyBtn);
        }
  
        // إضافة عناصر التعليق
        commentBody.append(commentHeader, commentText, editInput, actions);
        commentBox.append(userImg, commentBody);
        commentsList.prepend(commentBox);
  
        // إعادة تعيين حقل الإدخال
        commentInput.value = "";
        commentActions.classList.add("hidden");
  
        // منطق التعديل
        editBtn.addEventListener("click", () => {
          commentText.classList.add("hidden");
          editInput.classList.remove("hidden");
          saveBtn.classList.remove("hidden");
          cancelBtn.classList.remove("hidden");
          editBtn.classList.add("hidden");
          deleteBtn.classList.add("hidden");
        });
  
        cancelBtn.addEventListener("click", () => {
          editInput.value = commentText.textContent;
          commentText.classList.remove("hidden");
          editInput.classList.add("hidden");
          saveBtn.classList.add("hidden");
          cancelBtn.classList.add("hidden");
          editBtn.classList.remove("hidden");
          deleteBtn.classList.remove("hidden");
        });
  
        saveBtn.addEventListener("click", () => {
          const newValue = editInput.value.trim();
          if (newValue) {
            commentText.textContent = newValue;
            commentText.classList.remove("hidden");
            editInput.classList.add("hidden");
            saveBtn.classList.add("hidden");
            cancelBtn.classList.add("hidden");
            editBtn.classList.remove("hidden");
            deleteBtn.classList.remove("hidden");
          }
        });
  
        deleteBtn.addEventListener("click", () => {
          commentBox.remove();
        });
  
        replyBtn.addEventListener("click", () => {
            commentInput.focus();
            commentInput.value = `@${currentUserName} `;
            commentInput.dataset.replyTo = comment.id; // استخدمي معرف التعليق كـ parent_id
            commentActions.classList.remove("hidden");
          });
          
      });
    }
  });