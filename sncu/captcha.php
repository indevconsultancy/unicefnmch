<?php
session_start();

// You can customize your captcha settings here
$captcha_code = '';
$captcha_image_height = 40;
$captcha_image_width = 160;
$total_characters_on_image = 6;

// Avoid all confusing characters and numbers (e.g., l, 1, and i)
$possible_captcha_letters = 'abcdfghjkmnpqrstvwxyz23456789';
$captcha_font = './monofontcaptcha.ttf'; // Ensure this path is correct

$random_captcha_dots = 10;
$random_captcha_lines = 10;
$captcha_text_color = "0x142864";
$captcha_noise_color = "0x142864";

// Generate the captcha code
for ($count = 0; $count < $total_characters_on_image; $count++) {
    $captcha_code .= substr($possible_captcha_letters, mt_rand(0, strlen($possible_captcha_letters) - 1), 1);
}

// Create the captcha image resource
$captcha_image = @imagecreate($captcha_image_width, $captcha_image_height);

/* Setting the background color */
$background_color = imagecolorallocate($captcha_image, 255, 255, 255); // White background

/* Setting the text and noise colors */
$array_text_color = hextorgb($captcha_text_color);
$captcha_text_color = imagecolorallocate($captcha_image, $array_text_color['red'], $array_text_color['green'], $array_text_color['blue']);

$array_noise_color = hextorgb($captcha_noise_color);
$image_noise_color = imagecolorallocate($captcha_image, $array_noise_color['red'], $array_noise_color['green'], $array_noise_color['blue']);

/* Generate random dots in the background of the captcha image */
// for ($i = 0; $i < $random_captcha_dots; $i++) {
//     imagefilledellipse($captcha_image, mt_rand(0, $captcha_image_width), mt_rand(0, $captcha_image_height), 2, 3, $image_noise_color);
// }

/* Generate random lines in the background of the captcha image */
for ($i = 0; $i < $random_captcha_lines; $i++) {
    imageline($captcha_image, mt_rand(0, $captcha_image_width), mt_rand(0, $captcha_image_height), mt_rand(0, $captcha_image_width), mt_rand(0, $captcha_image_height), $image_noise_color);
}

/* Create a text box and add the captcha letters */
$captcha_font_size = $captcha_image_height * 0.65;
$text_box = imagettfbbox($captcha_font_size, 0, $captcha_font, $captcha_code);

/* Calculating X and Y position and avoiding float-to-int conversion warning */
$x = intval(($captcha_image_width - $text_box[4]) / 2); // Explicitly casting to int
$y = intval(($captcha_image_height - $text_box[5]) / 2); // Explicitly casting to int


/* Adding the CAPTCHA text to the image */
imagettftext($captcha_image, $captcha_font_size, 0, $x, $y, $captcha_text_color, $captcha_font, $captcha_code);

/* Show captcha image in the HTML page */
// Defining the image type to be shown in the browser
header('Content-Type: image/jpeg');
imagejpeg($captcha_image); // Output the image
imagedestroy($captcha_image); // Destroy the image resource to free memory

// Store the captcha code in session
$_SESSION['captcha'] = $captcha_code;

// Function to convert HEX to RGB
function hextorgb($hexstring) {
    $integar = hexdec($hexstring);
    return array(
        "red" => 0xFF & ($integar >> 0x10),
        "green" => 0xFF & ($integar >> 0x8),
        "blue" => 0xFF & $integar
    );
}
?>
