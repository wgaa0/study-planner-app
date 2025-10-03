# API Documentation

This document describes the REST API endpoints available in the Study Planner App.
All endpoints require the user to be **logged in** (session-based authentication).

---

## Authentication
- All API requests require an active session (`$_SESSION['user_id']`).
- If the user is not logged in, the API returns:

    ```json
    {
        "error": "Unauthorized"
    }
    ```
    with HTTP status **401 Unauthorized**.

---

## Events API

1. **Get all events**
    **Endpoint:**
        `GET /api/events.php`

    **Response (200 OK):**
        ```json
        [
            {
                "id": 1,
                "title": "Math Exam",
                "start": "2025-10-01 10:00:00",
                "end": "2025-10-01 12:00:00",
                "notes": "Bring calculator"
            },
            {
                "id: 2,
                "title": "Group Study",
                "start": "2025-10-03 15:00:00",
                "end": "2025-10-03 17:00:00",
                "notes": null
            }
        ]
        ```

2. **Create a new event**
    **Endpoint:**
        `POST /api/events.php`

    **Request body (JSON):**
        ```json
        {
            "title": "Project Meeting",
            "start": "2025-10-10 14:00:00",
            "end": "2025-10-10 15:00:00:,
            "notes": "Discuss presentation"
        }
        ```
    **Response (200 OK):**
        ```json
            { "success": true }
        ```

    **Error (400 Bad Request):**
        ```json
            { "error": "Missing required fields" }
        ```

3. Update an event:
    **Endpoint:**
        `PUT /api/events.php`

    **Request body (JSON):**
        ```json
        {
            "id": 1,
            "title": "Updated Title",
            "start": "2025-10-01 11:00:00",
            "end": "2025-10-01 13:00:00",
            "notes": "Updated notes"
        }
        ```

    **Response (200 OK):**
        ```json
        { "success": true }
        ```

    **Error (400 Bad Request):**
        ```json
        { "error": "Missing event ID" }
        ```

4. Delete an event:
    **Endpoint:**
        `DELETE /api/events.php?id=1`

    **Response (200 OK):**
        ```json
        { "success": true }
        ```

    **Error (400 Bad Request):**
        ```json
        { "error": "Missing event ID" }
        ```

---

## Tasks Summary API

1. **Get completed tasks by week**
    **Endpoint:**
        `GET /api/tasks_summary.php`

    **Response (200 OK):**
        ```json
        [
            {
                "week": "202540",
                "completed_count": 3
            },
            {
                "week": "202541",
                "completed_count": 5
            }
        ]
        ```

---

## Error Codes Summary
- **401 Unauthorized** -> User is not logged in.
- **400 Bad Request** -> Missing required fields or parameters.
- **405 Method Not Allowed** -> Invalid HTTP method.
- **500 Internal Server Error** -> Database or server error.

