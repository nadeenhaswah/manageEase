let color = document.getElementById('color');
let createBtn = document.getElementById('createBtn');
let list = document.getElementById('list');
let msg_type = document.getElementById('msg_type');

createBtn.onclick = () => {
    let newNote = document.createElement('div');
    let get_msg = msg_type.value;
    newNote.classList.add('note');
    newNote.innerHTML = `
    <span class="close">x</span>
     <span>${get_msg}</span>
    <textarea
    placeholder="Write Content..."
    rows="10" cols="30"></textarea>`;
    newNote.style.borderColor = color.value;
    list.appendChild(newNote);


     // تحديد موقع النوت على الصفحة
     const rect = newNote.getBoundingClientRect();

     // تجهيز البيانات لإرسالها إلى السيرفر
     const formData = new FormData();
     formData.append('action', 'create');
     formData.append('title', get_msg);
     const noteContent = newNote.querySelector('textarea').value;
    formData.append('content', noteContent);
     formData.append('color', color.value);
     formData.append('position_x', rect.left);
     formData.append('position_y', rect.top);
     formData.append('width', newNote.offsetWidth);
     formData.append('height', newNote.offsetHeight);
 
     // إرسال البيانات إلى notes.php
     fetch('../../manager/notes_process.php', {
         method: 'POST',
         body: formData
     })
     .then(res => res.json())
     .then(data => {
         if (data.success) {
             console.log("Note saved with ID:", data.note_id);
             newNote.setAttribute('data-id', data.note_id); // لحفظ ID الملاحظة
         } else {
             console.error("Saving note failed:", data.error);
         }
     })
     .catch(err => console.error("Error:", err));
}
document.addEventListener('click', (event) => {
    if(event.target.classList.contains('close')){
        event.target.parentNode.remove();
    }
})

let cursor = {
    x: null,
    y: null
}
let note = {
    dom: null,
    x: null,
    y: null
}
// document.addEventListener('mousedown', (event) => {
//     if(event.target.classList.contains('note')){
//         cursor = {
//             x: event.clientX,
//             y: event.clientY
//         }
//         note = {
//             dom: event.target,
//             x: event.target.getBoundingClientRect().left,
//             y: event.target.getBoundingClientRect().top
//         }
//     }
// })
document.addEventListener('mousedown', (event) => {
    let target = event.target;
    if (target.closest('.note')) {
        let noteElem = target.closest('.note');
        // ما نسحب إذا ضغط داخل textarea
        if (target.tagName.toLowerCase() === 'textarea') return;

        cursor = {
            x: event.clientX,
            y: event.clientY
        };
        note = {
            dom: noteElem,
            x: noteElem.getBoundingClientRect().left,
            y: noteElem.getBoundingClientRect().top
        };
    }
});


document.addEventListener('mousemove', (event) => {
    if(note.dom == null) return;
    let currentCursor = {
        x: event.clientX,
        y: event.clientY
    }
    let distance = {
        x: currentCursor.x - cursor.x,
        y: currentCursor.y - cursor.y
    }
    note.dom.style.left = (note.x + distance.x) + 'px';
    note.dom.style.top = (note.y + distance.y) + 'px';
    note.dom.style.cursor = 'grab';
})
document.addEventListener('mouseup', () => {
    if( note.dom == null) return;
    note.dom.style.cursor = 'auto';
    note.dom = null;  
})




