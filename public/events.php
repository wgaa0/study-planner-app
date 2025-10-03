<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$pageTitle = "Events Calendar";
ob_start();
?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">My Events</h1>
    <div id="calendar" class="bg-white rounded-2xl shadow p-4 h-[80vh]"></div>
</div>

<div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Create Event</h2>
        <form id="createForm" class="space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Title</label>
                <input type="text" name="title" required class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Description</label>
                <textarea name="notes" class="w-full border rounded p-2"></textarea>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Start</label>
                <input type="datetime-local" name="start" required class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">End</label>
                <input type="datetime-local" name="end" class="w-full border rounded p-2" />
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 rounded bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Edit Event</h2>
        <form id="editForm" class="space-y-4">
            <input type="hidden" name="id" />
            <div>
                <label class="block mb-1 text-sm font-medium">Title</label>
                <input type="text" name="title" required class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Description</label>
                <textarea name="notes" class="w-full border rounded p-2"></textarea>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Start</label>
                <input type="datetime-local" name="start" required class="w-full border rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">End</label>
                <input type="datetime-local" name="end" class="w-full border rounded p-2" />
            </div>
            <div class="flex justify-between">
                <button type="button" id="deleteBtn" class="px-4 py-2 rounded bg-red-600 text-white">Delete</button>
                <div class="space-x-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="./assets/vendor/fullcalendar/index.global.min.js"></script>
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function toDateTimeLocal(date) {
    if (!date) return '';
    const pad = (n) => n.toString().padStart(2, '0');
    return (
        date.getFullYear() +
        '-' + pad(date.getMonth() + 1) +
        '-' + pad(date.getDate()) +
        'T' + pad(date.getHours()) +
        ':' + pad(date.getMinutes())
    );
}

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        events: async (info, successCallback, failureCallback) => {
            try {
                const res = await fetch('api/events.php');
                const data = await res.json();
                successCallback(data.map(ev => ({
                    id: ev.id,
                    title: ev.title,
                    start: ev.start,
                    end: ev.end,
                    description: ev.notes || ''
                })));
            } catch (err) {
                failureCallback(err);
            }
        },
        select: (info) => {
            const form = document.getElementById('createForm');
            form.reset();
            form.start.value = toDateTimeLocal(info.start);
            if (info.endStr) form.end.value = toDateTimeLocal(info.end);
            openModal('createModal');
        },
        eventClick: (info) => {
            const form = document.getElementById('editForm');
            form.id.value = info.event.id;
            form.title.value = info.event.title;
            form.notes.value = info.event.extendedProps.description || '';
            form.start.value = info.event.startStr.slice(0,16);
            if (info.event.endStr) form.end.value = info.event.endStr.slice(0,16);
            openModal('editModal');
        }
    });

    calendar.render();

    document.getElementById('createForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(e.target).entries());
        const res = await fetch('api/events.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        const result = await res.json();
        if (result.success) {
            closeModal('createModal');
            calendar.refetchEvents();
        } else {
            alert('Error creating event');
        }
    };

    document.getElementById('editForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(e.target).entries());
        const res = await fetch('api/events.php', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(formData)
        });
        const result = await res.json();
        if (result.success) {
            closeModal('editModal');
            calendar.refetchEvents();
        } else {
            alert('Error updating event');
        }
    };

    document.getElementById('deleteBtn').onclick = async () => {
        const id = document.getElementById('editForm').id.value;
        await fetch('api/events.php?id=' + id, { method: 'DELETE' });
        closeModal('editModal');
        calendar.refetchEvents();
    };
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '../partials/layout.php';
?>
