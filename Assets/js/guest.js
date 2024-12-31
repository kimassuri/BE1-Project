
// GSAP Animation cho chuyển động thẻ
gsap.to(".card", {
    y: -10, // Di chuyển lên
    repeat: -1, // Lặp lại vô hạn
    yoyo: true, // Hoán đổi chuyển động sau mỗi lần lặp
    duration: 1.5, // Thời gian mỗi vòng chuyển động
    stagger: 0.2, // Dãn cách chuyển động giữa các thẻ
    ease: "power1.inOut" // Mượt mà khi chuyển động
});
// GSAP hiệu ứng lật thẻ
gsap.fromTo(".card-inner", {
    rotationY: 0, // Bắt đầu với không xoay
}, {
    rotationY: 180, // Xoay 180 độ khi hover
    duration: 0.5,
    ease: "power2.inOut",
    paused: true, // Đảm bảo không có hoạt ảnh khi chưa hover
    repeat: -1, // Lặp lại hiệu ứng khi hover
    yoyo: true // Đảo lại khi rời khỏi thẻ
});
gsap.to(".ellipse", {
    y: -10, // Di chuyển lên
    y:  20, // Di chuyển lên
    repeat: -1, // Lặp lại vô hạn
    yoyo: true, // Hoán đổi chuyển động sau mỗi lần lặp
    duration: 1.5, // Thời gian mỗi vòng chuyển động
    stagger: 0.2, // Dãn cách chuyển động giữa các thẻ
    ease: "power1.inOut" // Mượt mà khi chuyển động
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
