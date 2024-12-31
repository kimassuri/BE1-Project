let cardCount = 1; // Biến đếm số lượng block

// Hàm xóa block form và cập nhật lại thứ tự
function deleteBlock(element) {
    element.closest('.form-block').remove();
    updateBlockNumbers();
}



// Hàm thêm khối form mới
function addNewCard() {
    cardCount++; // Tăng số thứ tự khối mới
    const form = document.getElementById('dynamic-form');

    // Tạo HTML cho block mới
    const newBlock = document.createElement('div');
    newBlock.classList.add('form-block');
    newBlock.innerHTML = `
        <div class="btn-wrapper">
            <input type="button" class="delete-btn" name="delete[]" onclick="deleteBlock(this)" value="X">
        </div>
        <div class="form-header">${cardCount}</div>
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Term</label>
                <input type="text" id="term_${cardCount}" name="term[]"  class="form-control" placeholder="Enter term">
            </div>
            <div class="col-md-5">
                <label class="form-label">Definition</label>
                <input type="text" id="definition_${cardCount}" name="definition[]" class="form-control" placeholder="Enter definition">
            </div>
        </div>
    `;

    // Thêm block mới vào cuối form
    form.appendChild(newBlock);
}

// Hàm cập nhật lại số thứ tự của các form-header
function updateBlockNumbers() {
    const blocks = document.querySelectorAll('.form-block');
    blocks.forEach((block, index) => {
        const header = block.querySelector('.form-header');
        header.textContent = index + 1;
        const termInput = block.querySelector('input[name^="term_"]');
        const definitionInput = block.querySelector('input[name^="definition_"]');
        if (termInput) termInput.name = `term_${index + 1}`;
        if (definitionInput) definitionInput.name = `definition_${index + 1}`;
    });
    cardCount = blocks.length; // Cập nhật lại số lượng block
}