document.addEventListener('DOMContentLoaded', function () {
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
        const title = item.querySelector('.accordion-title');
        const content = item.querySelector('.accordion-content');
        
        title.addEventListener('click', () => {
            // Đóng tất cả các nội dung khác (nếu chỉ muốn mở 1 mục mỗi lần)
            accordionItems.forEach(innerItem => {
                if (innerItem !== item) {
                    innerItem.querySelector('.accordion-content').style.display = 'none';
                }
            });
            
            // Toggle nội dung của mục được click
            content.style.display = (content.style.display === 'flex') ? 'none' : 'flex';
        });
    });
});


// hiển thị form để tạo folder 
