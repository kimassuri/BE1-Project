
let stack = document.querySelector(".stack");
let nextButton = document.getElementById("next");
let prevButton = document.getElementById("prev");

// Đảo ngược thứ tự các thẻ trong stack
[...stack.children].reverse().forEach(i => stack.append(i));

// Sự kiện cho nút Next
nextButton.addEventListener("click", () => swapNext());

// Sự kiện cho nút Prev
prevButton.addEventListener("click", () => swapPrev());

function swapNext() {
  let card = document.querySelector(".card:last-child"); // Lấy thẻ cuối cùng
  card.style.animation = "swapN 700ms forwards"; // Áp dụng hiệu ứng animation

  setTimeout(() => {
    card.style.animation = ""; // Xóa animation
    stack.prepend(card); // Đưa thẻ cuối cùng lên đầu
  }, 700);
}

function swapPrev() {
  let card = document.querySelector(".card:first-child"); // Lấy thẻ đầu tiên
  stack.append(card); // Đưa thẻ đầu tiên xuống cuối
  
}
document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
      card.classList.toggle('flip');
  });
});



