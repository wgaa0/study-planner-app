<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events Calendar</title>
    <link href="../node_modules/fullcalendar/index.global.min.css" rel="stylesheet">
    <script src="../node_modules/fullcalendar/index.global.min.js"></script>
    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">My Events</h1>
    <div id="calendar"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
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
            selectable: true,
            select: async (info) => {
                const title = prompt('Event Title:');
                if (!title) return;

                const description = prompt('Event Description (optional):', '');
                const start = prompt('Start (YYYY-MM-DD HH:MM)', info.startStr.slice(0,16));
                const end = prompt('End (YYYY-MM-DD HH:MM)', info.endStr ? info.endStr.slice(0,16) : '');

                const newEvent = {
                    title: title,
                    notes: description,
                    start: start,
                    end: end
                };

                const res = await fetch('api/events.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(newEvent)
                });
                const result = await res.json();
                if (result.success) {
                    calendar.refetchEvents();
                } else {
                    alert('Error creating event');
                }
            },
            eventClick: async (info) => {
                const action = prompt(
                    `Edit or Delete "${info.event.title}"?\nType 'edit' or 'delete':`
                );
                if (action === 'delete') {
                    await fetch('api/events.php?id=' + info.event.id, { method: 'DELETE' });
                    calendar.refetchEvents();
                } else if (action === 'edit') {
                    const newTitle = prompt('New Title:', info.event.title) || info.event.title;
                    const newDescription = prompt('New Description:', info.event.extendedProps.description || '');
                    const newStart = prompt('New Start (YYYY-MM-DD HH:MM):', info.event.startStr.slice(0,16));
                    const newEnd = prompt('New End (YYYY-MM-DD HH:MM):', info.event.endStr ? info.event.endStr.slice(0,16) : '');

                    const updateEvent = {
                        id: info.event.id,
                        title: newTitle,
                        notes: newDescription,
                        start: newStart,
                        end: newEnd
                    };

                    await fetch('api/events.php', {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(updateEvent)
                    });
                    calendar.refetchEvents();
                }
            }
        });

        calendar.render();
    });
    </script>
</body>
</html>
