let cardCount = 0; // Biến đếm số lượng block

// Kiểm tra xem đã có danh sách xóa trong sessionStorage chưa
// theo dõi thẻ cần được chỉnh sửa
const userActions = {
    editedCards: [],
    deletedCards: [],
};

// Hàm xóa block form và cập nhật lại thứ tự
function deleteBlock(button) {
    let userActions = JSON.parse(sessionStorage.getItem('userActions') || '{"deletedCards": []}');

    const formBlock = button.closest('.form-block') // Lấy form-block chứa nút
    const cardId = formBlock.getAttribute('data-card-id') // Lấy card ID
    const newCard = formBlock.getAttribute('data-new') // kiểm tra có phải thẻ được tạo ra từ form (không có trong cơ sở dữ liệu)


    // nếu thẻ chưa có trong danh sách cần xóa và nó thuộc thẻ không phải mới 
    if (cardId && !userActions.deletedCards.includes(cardId) && !newCard ) {    
        userActions.deletedCards.push(cardId);
    }
    // Ẩn thẻ
    formBlock.remove();
    // Lưu đối tượng userActions vào sessionStorage
    sessionStorage.setItem('userActions', JSON.stringify(userActions));

    // Cập nhật lại số thứ tự các block
    updateBlockNumbers("delete");
}

// Hàm thêm khối form mới
function addNewCard() { 
    const blocks = document.querySelectorAll('.form-block'); // Lấy tất cả các thẻ form-block
    cardCount++; // Tăng số thứ tự khối mới
    console.log(cardCount)
    const form = document.getElementById('dynamic-form');

    // Tạo HTML cho block mới
    const newBlock = document.createElement('div');
    newBlock.classList.add('form-block');
    newBlock.setAttribute('data-card-id', `${cardCount}`); // Thêm data-card-id duy nhất cho mỗi thẻ
    newBlock.setAttribute('data-new', true);
    newBlock.innerHTML = `
        <div class="btn-wrapper">
            <input type="button" class="delete-btn" onclick="deleteBlock(this)" value="X">
        </div>
        
        <div class="form-header">${cardCount}</div>
        <div class="row">
            <div class="col-md-5">
                <label class="form-label" for="term">Term</label>
                <input type="text" name="term[]" class="form-control term-input" placeholder="Enter term">
            </div>
            <div class="col-md-5">
                <label class="form-label" for="definition">Definition</label>
                <input type="text" name="definition[]" class="form-control definition-input" placeholder="Enter definition">
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <div class="image-box w-100">Image</div>
            </div>
        </div>
    `;

    // Thêm block mới vào cuối form
    form.appendChild(newBlock);

    // Cập nhật lại số thứ tự sau khi thêm block mới
    updateBlockNumbers("add card");
}

// hàm theo dõi người dùng chỉnh sửa 
document.querySelectorAll('.term-input, .definition-input').forEach(input => {
    input.addEventListener('input', function () {
        const cardBlock = this.closest('.form-block');

        if (!cardBlock) {
            return;
        }

        const cardId = cardBlock.dataset.cardId;
        const oldData = JSON.parse(cardBlock.dataset.original);

        const termInput = cardBlock.querySelector('.term-input');
        const definitionInput = cardBlock.querySelector('.definition-input');


        const newTerm = termInput.value;
        const newDefinition = definitionInput.value;

        // Khởi tạo hoặc lấy `actions` từ sessionStorage
        let actions = JSON.parse(sessionStorage.getItem('userActions') || '{"editedCards": []}');
        actions.editedCards = actions.editedCards || [];

        // So sánh dữ liệu cũ và mới để cập nhật danh sách editedCards
        if (newTerm !== oldData.term || newDefinition !== oldData.definition) {
            const existingCard = actions.editedCards.find(card => card.id === cardId);

            if (!existingCard) {
                actions.editedCards.push({
                    id: cardId,
                    term: newTerm,
                    definition: newDefinition,
                });
            } else {
                existingCard.term = newTerm;
                existingCard.definition = newDefinition;
            }
        } else {
            actions.editedCards = actions.editedCards.filter(card => card.id !== cardId);
        }

        // Lưu `actions` vào sessionStorage
        sessionStorage.setItem('userActions', JSON.stringify(actions));
    });
});

// Hàm submitForm() để thêm deleteList và editcards submit form
function submitForm() {
    let userActions = JSON.parse(sessionStorage.getItem('userActions') || {});
    const editedCards = userActions.editedCards || [];
    const deleteList = userActions.deletedCards || [];

    // Tạo 2 input ẩn để gửi deleteList editedCards
    // input ẩn cho deleteList
    const form = document.getElementById('dynamic-form');
    const deleteListInput = document.createElement('input');
    deleteListInput.type = 'hidden';
    deleteListInput.name = 'deleteList';  // Tên của input khi gửi tới PHP
    deleteListInput.value = JSON.stringify(deleteList);  // Chuyển deleteList thành chuỗi JSON

    // input ẩn cho
    const editedCardsInput = document.createElement('input');
    editedCardsInput.type = 'hidden'
    editedCardsInput.name = 'editedCards'
    editedCardsInput.value = JSON.stringify(editedCards)


    // Thêm input vào form
    form.appendChild(deleteListInput);
    form.appendChild(editedCardsInput);
    editedCards = userActions.editedCards = [];
    deleteList = userActions.deletedCards = [];
}

// Hàm cập nhật lại số thứ tự của các form-header và input
function updateBlockNumbers(message) {
    const blocks = document.querySelectorAll('.form-block'); // Lấy tất cả các thẻ form-block
    
    blocks.forEach((block, index) => {
        // Cập nhật lại số thứ tự trong form-header
        const header = block.querySelector('.form-header');
        if (header) {
            header.textContent = index + 1;
        }

    });
}


window.addEventListener('DOMContentLoaded', function () {
    updateBlockNumbers("reload");

    // Lấy lại hành động xóa của người dùng
    let userActions = JSON.parse(sessionStorage.getItem('userActions'));

    // Kiểm tra và khởi tạo userActions nếu chưa tồn tại
    if (!userActions) {
        userActions = {
            deletedCards: [],
            editedCards: []
        };
    } else {
        // Đảm bảo các mảng deletedCards và editedCards tồn tại
        userActions.deletedCards = userActions.deletedCards || [];
        userActions.editedCards = userActions.editedCards || [];
    }

    // Xóa danh sách deletedCards và editedCards trong userActions
    userActions.deletedCards = [];
    userActions.editedCards = [];

    // Cập nhật lại userActions trong sessionStorage
    sessionStorage.setItem('userActions', JSON.stringify(userActions));
});
