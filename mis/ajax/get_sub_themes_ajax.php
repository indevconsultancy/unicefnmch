<?php include_once('../includes/config.php'); ?>
<?php
if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $sub_themeQuery = mysqli_query($conn, "SELECT theme_id, theme_name FROM theme WHERE status='0' AND category_id='" . $category_id . "' ORDER BY theme_id DESC");

    echo '<option value="">Sub Theme</option>';
    while ($sub_theme = mysqli_fetch_array($sub_themeQuery)) {
        echo '<option value="' . $sub_theme['theme_id'] . '">' . $sub_theme['theme_name'] . '</option>';
    }
}
?>