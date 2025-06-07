// let icon = document.getElementById("toggleIcon");

// icon.addEventListener("click", function () {
//     if (icon.classList.contains("fa-sun")) {
//         icon.classList.remove("fa-sun");
//         icon.classList.add("fa-moon");
//     } else {
//         icon.classList.remove("fa-moon");
//         icon.classList.add("fa-sun");
//     }


//     document.querySelectorAll(".switch").forEach(element => {
//         element.classList.toggle("dark-mode-active");
//     });

//     document.querySelectorAll(".switchTxt").forEach(element => {
//         // إذا كان اللون الحالي أبيض، قم بإرجاع اللون الأصلي
//         if (!element.dataset.originalColor) {
//             // احفظ اللون الأصلي عند أول ضغطة
//             element.dataset.originalColor = window.getComputedStyle(element).color;
//         }

//         // تبديل اللون بين الأبيض والأصلي
//         if (element.style.color === "gray") {
//             element.style.color = element.dataset.originalColor;
//         } else {
//             element.style.color = "gray";
//         }
//     });
// });












let icon = document.getElementById("toggleIcon");

// ✅ استرجاع الحالة المحفوظة عند تحميل الصفحة
if (localStorage.getItem("darkMode") === "enabled") {
    icon.classList.remove("fa-sun");
    icon.classList.add("fa-moon");
    document.querySelectorAll(".switch").forEach(element => {
        element.classList.add("dark-mode-active");
    });
    document.querySelectorAll(".switch_op").forEach(element => {
        element.classList.add("dark-mode-active_op");
    });

    document.querySelectorAll(".switchTxt").forEach(element => {
        element.style.color = "gray";
    });
    document.querySelectorAll(".switchTxt_op").forEach(element => {
        element.style.color = "white";
    });
}

// ✅ حدث عند الضغط على الأيقونة
icon.addEventListener("click", function () {
    if (icon.classList.contains("fa-sun")) {
        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");

        // تفعيل الوضع الداكن فقط للعناصر المطلوبة
        document.querySelectorAll(".switch").forEach(element => {
            element.classList.add("dark-mode-active");
        });
        document.querySelectorAll(".switch_op").forEach(element => {
            element.classList.add("dark-mode-active_op");
        });

        document.querySelectorAll(".switchTxt").forEach(element => {
            if (!element.dataset.originalColor) {
                element.dataset.originalColor = window.getComputedStyle(element).color;
            }
            element.style.color = "gray";
        });
        document.querySelectorAll(".switchTxt_op").forEach(element => {
            if (!element.dataset.originalColor) {
                element.dataset.originalColor = window.getComputedStyle(element).color;
            }
            element.style.color = "white";
        });

        localStorage.setItem("darkMode", "enabled"); // 🔹 حفظ الحالة
    } else {
        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");

        // تعطيل الوضع الداكن فقط للعناصر المطلوبة
        document.querySelectorAll(".switch").forEach(element => {
            element.classList.remove("dark-mode-active");
        });
        document.querySelectorAll(".switch_op").forEach(element => {
            element.classList.remove("dark-mode-active_op");
        });

        document.querySelectorAll(".switchTxt").forEach(element => {
            if (element.dataset.originalColor) {
                element.style.color = element.dataset.originalColor;
            }
        });
        document.querySelectorAll(".switchTxt_op").forEach(element => {
            if (element.dataset.originalColor) {
                element.style.color = element.dataset.originalColor;
            }
        });

        localStorage.setItem("darkMode", "disabled"); // 🔹 حفظ الحالة
    }
});





