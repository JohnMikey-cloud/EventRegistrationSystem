<?php
// ── queries.php ──
// All database queries for the Registrant Viewer (Role 4)

// Fetch all events for the dropdown filter
function getEvents($conn) {
    $sql = "SELECT id, title FROM events ORDER BY event_date ASC";
    return mysqli_query($conn, $sql);
}

// Fetch all registrants (with optional event filter)
function getRegistrants($conn, $event_id = 0) {
    if ($event_id > 0) {
        $event_id = intval($event_id); // sanitize
        $sql = "
            SELECT 
                users.name AS student_name,
                users.email,
                events.title AS event_title,
                events.event_date,
                registrations.registered_at
            FROM registrations
            JOIN users ON registrations.user_id = users.id
            JOIN events ON registrations.event_id = events.id
            WHERE events.id = $event_id
            ORDER BY registrations.registered_at DESC
        ";
    } else {
        $sql = "
            SELECT 
                users.name AS student_name,
                users.email,
                events.title AS event_title,
                events.event_date,
                registrations.registered_at
            FROM registrations
            JOIN users ON registrations.user_id = users.id
            JOIN events ON registrations.event_id = events.id
            ORDER BY registrations.registered_at DESC
        ";
    }

    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
?>
