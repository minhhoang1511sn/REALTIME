if (data.ticket != "" || data.comment != "") {

btnLoaders.forEach(btnLoader => btnLoader.classList.add('d-none'));
btnSubmitReply.classList.add('show')
clearFiles('box-files')
listFileAttachments = [];

if (data.ticket) {
let priority = data.ticket.Priority;
window.setPriority(priority)
updateLiColors(data.ticket.Status)
}
if (data.comment) {
const commentText = document.getElementById('areaComment');
if (commentText) {
commentText.value = '';
}
if (data.uploadedFiles.length > 0) {
addCommentToUI(data.comment, data.uploadedFiles, '', data.fullName)

} else {
addCommentToUI(data.comment, '', '', data.fullName)
}
updateUIBoxTicketDetail(data.ticket, data.countComment)

const btnCustomSelect = document.querySelector(".btn-custom-select");
const boxSelectSubmitAs = document.querySelector(".box-select-submit-as");
const iconSubmitAs = btnCustomSelect.querySelector(".icon-btn-custom-select");

if (!boxSelectSubmitAs.classList.contains("show")) {
iconSubmitAs.classList.remove("mdi-chevron-down");
iconSubmitAs.classList.add("mdi-chevron-up");
} else {
iconSubmitAs.classList.remove("mdi-chevron-up");
iconSubmitAs.classList.add("mdi-chevron-down");
boxSelectSubmitAs.classList.remove("show");
}

if (status) {
document.querySelector('#statusTicketRight .selected-value').textContent = status;
updateLiColors(status);
}
} else {
Swal.fire(
'Error!',
data.message,
'error'
);
}

} giữ toàn bộ logic đoạn này thêm logic xử lý gọi event tạo cmt từ này if (window.Echo && !isEchoInitialized) {
window.Echo.channel(ticket.${ticketToken})
.listen('.comment.created', (e) => {
const comment = e;

const container = document.getElementById('boxChats');
const fullName = comment.userCreate?.name || 'Unknown';

let div = document.createElement('div');
div.className = card item-conversition ${['admin', 'staff'].includes(comment.UserType) ? 'bg-ad' : ''};

// const readStatus = comment.UserType === 'admin' ? (comment.IsRead ? 'Đã đọc' : 'chưa đọc') : '';
let readStatus = '';
console.log('123');
if (currentUser.id === comment.UserID) {
// Người gửi sẽ có status "Đã đọc"
readStatus = comment.UserType === 'admin' ? 'Đã đọc' : ''; // admin có status "Đã đọc", user không có
}
// const readStatus = 'Đã đọc';
console.log(readStatus);
// Tạo phần tử cho thông tin reply
const row1 = document.createElement('div');
row1.className = 'row';
row1.innerHTML =
<div class="w-36"></div>
<div class="col-6 d-flex mt-3 p-2">
    <p>Reply by </p>
    <span class="fw-bold">${fullName}</span>
    <span class="mdi mdi-arch rotate-90 icon"></span>
    <p class="time-period">${dayjs(comment.CreatedAtTime).utc().tz(userTimezone).format('YYYY-MM-DD HH:mm:ss')}</p>
</div>
<div class="col d-flex box-modify">
    <input hidden value="${comment.IDComment}" />
    <a class="color-custom btn" type="button" href="/comments/${comment.IDComment}/edit">
        <span class="mdi fs-5 mdi-tooltip-edit-outline"></span>
        <span class="fw-bold text-primary">Edit</span>
    </a>
    <div class="dropdown h-44 color-custom p-1 br-radius">
        <div class="dropdown-menu dropdown-comment" aria-labelledby="dropdownButtonComment${comment.IDComment}">
            <span class="dropdown-item c-pointer fw-500 color-black text-danger"
                onclick="handleDeleteReply('${comment.IDTicket}', '${comment.IDComment}')">Delete Reply</span>
        </div>
        <span class="fw-bold text-primary dropdown-toggle" type="button" id="dropdownButtonComment${comment.IDComment}"
            data-bs-toggle="dropdown">
            More <i class="mdi mdi-chevron-down fs-6 color-black"></i>
        </span>
    </div>
</div>
;

// Tạo phần tử cho nội dung comment
const row2 = document.createElement('div');
row2.className = 'row';
console.log('before:' + readStatus)
row2.innerHTML =
<p class="comment-text ml-36 fz-16">${comment.content}</p>
<p class="comment-text ml-36 fz-16">
    <span class="text-muted float-end ms-2 status-text">${readStatus}</span>
</p>
;
console.log('after:' + readStatus)
console.log('row2:' + row2)

// Tạo phần tử cho phần attachments
const row3 = document.createElement('div');
row3.className = 'row box-attachments';
row3.innerHTML =
<ul class="ml-36">
    ${(comment.attachments || []).map(attachment =>
    <li class="item-attach">
        <span class="mdi mdi-trash-can-outline fs-5 c-pointer"
            onclick="handleDeleteFile('${comment.IDTicket}', '${comment.IDComment}', '${attachment.IDAttach}')"></span>
        <span class="mdi mdi-paperclip"></span>
        <a href="/uploads/${attachment.IDTicket}/${attachment.IDComment}/${attachment.NameFile}"
            target="_blank">${attachment.NameFile}</a>
    </li>
    ).join('')}
</ul>
;

// Append các row vào div
div.appendChild(row1);
div.appendChild(row2);
div.appendChild(row3);

console.log('div:' + div)
// Append div vào container
container.appendChild(div);
console.log('container:' + container)
})
.listen('.comment.read', (e) => {
console.log('vo ent doc admin')
const comment = e.comment;
const item = document.querySelector([data-comment-id="${comment.id}"]);
if (item) {
const statusSpan = item.querySelector('.status-text');
console.log('before: ' + statusSpan);
if (statusSpan) {
console.log('before inner: ' + after);
statusSpan.textContent = comment.IsRead ? 'Đã đọc' : 'chưa đọc';
console.log('after inner: ' + after);
}
console.log('after: ' + after);
}
});
isEchoInitialized = true;
}
