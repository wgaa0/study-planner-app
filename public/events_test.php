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
    <title>Events API Test</title>
</head>
<body>
    <h1>Events API Test</h1>

    <h2>Create Event</h2>
    <form id="eventForm">
        <label>Title: <input type="text" id="title" required></label><br>
        <label>Start (YYYY-MM-DD HH:MM:SS): <input type="text" id="start" required></label><br>
        <label>End (optional): <input type="text" id="end"></label><br>
        <label>Notes: <input type="text" id="notes"></label><br>
        <button type="submit">Create Event</button>
    </form>

    <h2>All Events</h2>
    <button id="loadEvents">Load Events</button>
    <ul id="eventsList"></ul>

    <script>
    const form = document.getElementById('eventForm');
    const eventsList = document.getElementById('eventsList');

    // Create event
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = {
            title: document.getElementById('title').value,
            start: document.getElementById('start').value,
            end: document.getElementById('end').value || null,
            notes: document.getElementById('notes').value || null
        };

        const res = await fetch('api/events.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await res.json();
        alert('Created: ' + JSON.stringify(result));
    });

    // Load events
    document.getElementById('loadEvents').addEventListener('click', async () => {
        const res = await fetch('api/events.php');
        const events = await res.json();
        eventsList.innerHTML = '';
        events.forEach(ev => {
            const li = document.createElement('li');
            li.textContent = `${ev.title} (${ev.start} → ${ev.end || 'no end'})`;
            
            // Delete link
            const del = document.createElement('a');
            del.href = '#';
            del.textContent = ' [delete]';
            del.onclick = async () => {
                if (confirm('Delete this event?')) {
                    await fetch('api/events.php?id=' + ev.id, { method: 'DELETE' });
                    li.remove();
                }
            };
            li.appendChild(del);
            eventsList.appendChild(li);
        });
    });
    </script>
</body>
</html>
