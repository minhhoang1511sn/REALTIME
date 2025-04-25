<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ticket->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ticket-id" content="{{ $ticket->id }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="assigned-user-id" content="{{ $ticket->assignedUser->id }}">
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
</head>

<body>
    <div class="container">
        <h2>{{ $ticket->title }}</h2>
        <p>{{ $ticket->description }}</p>
        <p><strong>Created by:</strong> {{ $ticket->creator->name }}</p>
        <p><strong>Assigned to:</strong> {{ $ticket->assignedUser->name }}</p>

        <hr>
        <h4>Comments</h4>
        <ul class="list-group mb-3" id="comments-container">
            @foreach ($ticket->comments as $comment)
                <li class="list-group-item" data-comment-id="{{ $comment->id }}">
                    <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                    <span class="text-muted float-end ms-2 status-text">
                        {{ $comment->is_read ? 'Đã đọc' : 'chưa đọc' }}
                    </span>
                    <span class="text-muted float-end time-text" data-time="{{ $comment->created_at->toISOString() }}">
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                </li>
            @endforeach
        </ul>

        <form method="POST" action="{{ route('tickets.comment', $ticket->id) }}">
            @csrf
            <textarea name="message" class="form-control" placeholder="Add comment..."></textarea>
            <button class="btn btn-primary mt-2">Send</button>
        </form>
    </div>

    <script>
        dayjs.extend(dayjs_plugin_relativeTime);

        document.addEventListener('DOMContentLoaded', () => {
            const ticketId = document.head.querySelector('meta[name="ticket-id"]').content;
            const userId = document.head.querySelector('meta[name="user-id"]').content;
            const assignedUserId = document.head.querySelector('meta[name="assigned-user-id"]').content;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let alreadyMarkedRead = false;

            // Cập nhật lại thời gian các comment bằng dayjs
            document.querySelectorAll('.time-text').forEach(span => {
                const isoTime = span.dataset.time;
                span.textContent = dayjs(isoTime).fromNow();
            });

            // Nếu người dùng là assignedUser, tự động đánh dấu các comment chưa đọc là đã đọc
            function checkAndMarkRead() {
                if (alreadyMarkedRead) return;

                const firstUnread = document.querySelector('.comment-item[data-is-read="0"]'); // comment chưa đọc
                console.log('firstUnread' + firstUnread)
                if (firstUnread && isElementInViewport(firstUnread)) {
                    alreadyMarkedRead = true;
                    console.log('vao fetch')
                    console.log(ticketId)

                    fetch(`/tickets/${ticketId}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        // Cập nhật status "Đã đọc" cho tất cả comment chưa đọc khi tải trang
                        document.querySelectorAll('.status-text').forEach(status => {
                            if (status.textContent === 'chưa đọc') {
                                status.textContent = 'Đã đọc';
                            }
                        });
                    }).catch(console.error);
                }
            }
            if (window.Echo) {
                window.Echo.channel(`ticket.${ticketId}`)
                    // Khi có comment được tạo mới
                    .listen('.comment.created', (e) => {
                        const comment = e;

                        if (parseInt(comment.user_id) !== parseInt(userId)) {
                            const container = document.getElementById('comments-container');
                            const userName = comment.user?.name || 'Unknown';

                            const li = document.createElement('li');
                            li.className =
                                'list-group-item comment-item flash-unread'; // thêm class dễ xử lý + hiệu ứng
                            li.setAttribute('data-comment-id', comment.id);
                            li.setAttribute('data-is-read', '0');
                            li.setAttribute('id', `comment-${comment.id}`);

                            const contentText = document.createElement('span');
                            contentText.innerHTML = `<strong>${userName}</strong>: ${comment.content}`;

                            const statusSpan = document.createElement('span');
                            statusSpan.className = 'text-muted float-end ms-2 status-text';
                            statusSpan.textContent = 'chưa đọc';

                            const timeSpan = document.createElement('span');
                            timeSpan.className = 'text-muted float-end time-text';
                            timeSpan.setAttribute('data-time', comment.created_at);
                            timeSpan.textContent = dayjs(comment.created_at).fromNow();

                            li.appendChild(contentText);
                            li.appendChild(statusSpan);
                            li.appendChild(timeSpan);
                            container.appendChild(li);

                            // Optional: auto scroll tới comment mới
                            li.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }

                    });

                // Khi có comment được đánh dấu là đã đọc
                window.Echo.channel(`ticket.${ticketId}`).listen('.comment.read', (e) => {
                    console.log('vao event doc1')
                    const comment = e.comment;
                    const item = document.querySelector(`[data-comment-id="${comment.id}"]`);
                    console.log('vao event doc')

                    if (item) {
                        const statusSpan = item.querySelector('.status-text');

                        // Cập nhật trạng thái comment là đã đọc
                        if (comment.is_read) {
                            statusSpan.textContent = 'Đã đọc';
                        } else {
                            statusSpan.textContent = 'chưa đọc';
                        }

                        // Cập nhật lại thời gian bình luận
                        const timeSpan = item.querySelector('.time-text');
                        if (timeSpan) {
                            timeSpan.textContent = dayjs(comment.created_at).fromNow();
                        }
                    }
                    checkAndMarkRead();
                });
            }
            window.addEventListener('scroll', checkAndMarkRead);
            window.addEventListener('resize', checkAndMarkRead);
            checkAndMarkRead();
        });

        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    </script>


</body>

</html>
