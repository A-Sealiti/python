<?php
// Configuration
$width = 800;
$height = 600;
$background_colors = [
    '#3498db', '#2ecc71', '#e74c3c', '#f1c40f', '#9b59b6',
    '#1abc9c', '#e67e22', '#34495e', '#16a085', '#d35400',
    '#27ae60', '#2980b9', '#8e44ad', '#c0392b', '#f39c12'
];

// Create images directory if it doesn't exist
if (!file_exists('images')) {
    mkdir('images', 0777, true);
}

// List of all product images needed
$products = [
    'phone.jpg' => 'Smartphone X',
    'laptop.jpg' => 'Laptop Pro',
    'earbuds.jpg' => 'Wireless Earbuds',
    'gaming-mouse.jpg' => 'Gaming Mouse',
    'monitor.jpg' => '4K Monitor',
    'keyboard.jpg' => 'Mechanical Keyboard',
    'smartwatch.jpg' => 'Smart Watch',
    'speaker.jpg' => 'Bluetooth Speaker',
    'tablet.jpg' => 'Tablet Pro',
    'wireless-mouse.jpg' => 'Wireless Mouse',
    'usb-hub.jpg' => 'USB-C Hub',
    'headset.jpg' => 'Gaming Headset',
    'webcam.jpg' => 'Webcam HD',
    'powerbank.jpg' => 'Power Bank',
    'ssd.jpg' => 'External SSD',
    'gpu.jpg' => 'Graphics Card',
    'ram.jpg' => 'RAM Kit',
    'cpu-cooler.jpg' => 'CPU Cooler'
];

// Generate placeholder images
foreach ($products as $filename => $text) {
    // Create image
    $image = imagecreatetruecolor($width, $height);
    
    // Set background color
    $color_index = array_rand($background_colors);
    $bg_color = $background_colors[$color_index];
    list($r, $g, $b) = sscanf($bg_color, "#%02x%02x%02x");
    $background = imagecolorallocate($image, $r, $g, $b);
    imagefill($image, 0, 0, $background);
    
    // Add text
    $white = imagecolorallocate($image, 255, 255, 255);
    
    // Center text (approximately)
    $font = 5; // Larger built-in font
    $text_width = imagefontwidth($font) * strlen($text);
    $text_height = imagefontheight($font);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    // Draw text
    imagestring($image, $font, $x, $y, $text, $white);
    
    // Save image
    imagejpeg($image, 'images/' . $filename, 90);
    imagedestroy($image);
    
    echo "Generated: images/$filename\n";
}

echo "All placeholder images have been generated!\n";
?> 