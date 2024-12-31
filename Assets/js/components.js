function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}
// alert
const addFolderButton = document.getElementById("addFolderButton");
const alertContainer = document.querySelector(".alert-container");
const closeButton = document.querySelector(".close-btn");

const createFolder = document.getElementById("createFolderButton");
// Lấy tất cả các phần tử có class "add-folder-btn"
const addFolders = document.querySelectorAll(".add-folder-btn");

// Hiển thị alert khi nhấn nút
addFolderButton.addEventListener("click", () => {
    document.getElementById('alertOverlay').style.display = 'flex';
    alertContainer.classList.remove("hidden");
    alertContainer.classList.add("show");
});

// Hiển thị khi ấn nút add - folder 
addFolders.forEach(function(button) {
    button.addEventListener("click", ()=> {
        document.getElementById('alertOverlay').style.display = 'flex';
        alertContainer.classList.remove("hidden");
        alertContainer.classList.add("show");
    } )
})


// Hiển thị form tạo folder khi ấn nút tạo folder
createFolder.addEventListener("click", ()=> {
    document.querySelector(".create-folder").style.display = 'none';
    document.querySelector(".formCreate").style.display = 'flex';

})

// Ẩn alert khi nhấn vào biểu tượng đóng
closeButton.addEventListener("click", () => {
    document.getElementById('alertOverlay').style.display = 'none';
    document.querySelector(".create-folder").style.display = 'flex';
    document.querySelector(".formCreate").style.display = 'none';
    alertContainer.classList.remove("show");
    alertContainer.classList.add("hidden");
});

// toast notification
function showToast(success, text) {
    let toast = document.getElementById("toast");
    let icon = toast.querySelector(".icon-toast"); 
    let message = toast.querySelector(".message span"); 
    
    // Set the initial position off-screen to the right
    gsap.set(toast, {
        x: 200, // Off-screen to the right
        opacity: 0,
    });

    // Set the background color based on success or failure
    if (success) {
        icon.className = "bi bi-check-lg icon-toast";

        toast.style.borderLeft = "5px solid rgb(18, 227, 11)";
        toast.style.boxShadow = "0 2px 2px rgb(18, 227, 11)"; 
        icon.style.backgroundColor = "rgb(18, 227, 11)"
    }
    message.innerText = text; 
    
    
    // GSAP animation to show the toast sliding in from the right
    gsap.to(toast, {
        duration: 0.5,
        opacity: 1,  // Fade in
        x: 0,  // Move to the normal position (from right to left)
        ease: "ease.inOut",  // Smooth easing
        display: "block",  // Ensure it's visible
    });

    // After 1 seconds, hide the toast with GSAP animation
    setTimeout(function() {
        gsap.to(toast, {
            duration: 0.5,
            opacity: 0,  // Fade out
            x: 200,  // Slide back to the right
            ease: "ease.inOut",  // Smooth easing
        });
    }, 2000); // Hide after 3 seconds
}
