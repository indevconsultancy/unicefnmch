<?php include('../includes/config.php'); ?>

<?php
// dishcharge data
if (isset($_POST['post_id']) && isset($_POST['uniq_id'])) {

    $id    = $_POST['post_id'];
    $uniqueid    = $_POST['uniq_id'];

    $query = mysqli_query($conn, "SELECT r.unique_id_of_body,f.id,f.type_of_time,f.schedule_follow_up_date,f.date_of_visit,f.baby_weight,f.baby_length,f.baby_head_circumference,f.immunization_status,f.type_of_feed,f.other_feed,f.times_baby_breastfed,f.check_health_examination,f.mention_identified_health from follow_up AS f JOIN registration_form AS r ON f.registration_id=r.id WHERE f.id = $id");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        echo "<table cellpadding='6' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";

        echo "<tr><td><strong>Unique Id</strong></td><td>:</td><td>" . $uniqueid . "</td></tr>";
        echo "<tr><td><strong>Type of Period</strong></td><td>:</td><td>" . $data['type_of_time'] . "</td></tr>";
        echo "<tr><td><strong>Schedule Date</strong></td><td>:</td><td>" . $data['schedule_follow_up_date'] . "</td></tr>";
        echo "<tr><td><strong>Date of Visit</strong></td><td>:</td><td>" . $data['date_of_visit'] . "</td></tr>";
        echo "<tr><td><strong>Baby Weight</strong></td><td>:</td><td>" . $data['baby_weight'] . "</td></tr>";
        echo "<tr><td><strong>Baby Length</strong></td><td>:</td><td>" . $data['baby_length'] . "</td></tr>";
        echo "<tr><td><strong>Baby Head Circumference</strong></td><td>:</td><td>" . $data['baby_head_circumference'] . "</td></tr>";
        echo "<tr><td><strong>Immunization Status</strong></td><td>:</td><td>" . $data['immunization_status'] . "</td></tr>";
        echo "<tr><td><strong>Type of Feed</strong></td><td>:</td><td>" . $data['type_of_feed'] . "</td></tr>";
        echo "<tr><td><strong>Other Feed</strong></td><td>:</td><td>" . $data['other_feed'] . "</td></tr>";
        echo "<tr><td><strong>Times Baby Breastfed</strong></td><td>:</td><td>" . $data['times_baby_breastfed'] . "</td></tr>";
        echo "<tr><td><strong>Health Examination</strong></td><td>:</td><td>" . $data['check_health_examination'] . "</td></tr>";
        echo "<tr><td><strong>Mention Identified Health</strong></td><td>:</td><td>" . $data['mention_identified_health'] . "</td></tr>";

        echo "</table>";
    } else {
        echo "<p>No data found.</p>";
    }
}
?>