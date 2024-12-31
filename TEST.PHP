<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accordion List</title>
    <style>
    .accordion {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
}

.accordion-item {
    border-bottom: 1px solid #ddd;
}

.accordion-title {
    background: #f4f4f4;
    padding: 15px;
    font-size: 16px;
    cursor: pointer;
    user-select: none;
    transition: background 0.3s;
}

.accordion-title:hover {
    background: #e0e0e0;
}

.accordion-content {
    display: none; /* Ẩn nội dung mặc định */
    padding: 15px;
    background: #fff;
    transition: all 0.3s ease;
}

.accordion-content p {
    margin: 0;
}

    </style>
</head>
<body>

<h2>Danh Sách Có Thể Mở Rộng</h2>

<div class="accordion">
    <div class="accordion-item">
        <div class="accordion-title">Title 1</div>
        <div class="accordion-content">
            <p>Content 1.1</p>
            <p>Content 1.2</p>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-title">Title 2</div>
        <div class="accordion-content">
            <p>Content 2.1</p>
            <p>Content 2.2</p>
        </div>
    </div>
    <div class="accordion-item">
        <div class="accordion-title">Title 3</div>
        <div class="accordion-content">
            <p>Content 3.1</p>
            <p>Content 3.2</p>
        </div>
    </div>
</div>

<script>
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
            content.style.display = (content.style.display === 'block') ? 'none' : 'block';
        });
    });
});

</script>
</body>
</html>
