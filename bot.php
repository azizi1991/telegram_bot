<?php
$token = "8149232864:AAGXL7tmbBYzSSkKnwd_HzmYyTyG37xarG0";
$api_url = "https://api.telegram.org/bot$token/";

$update = json_decode(file_get_contents("php://input"), TRUE);
$chat_id = $update["message"]["chat"]["id"] ?? null;
$text = $update["message"]["text"] ?? null;

// ارسال لاگ برای دیباگ
file_put_contents("php://stderr", print_r($update, true));

function send_main_menu($chat_id) {
    global $api_url;
    $keyboard = [
        [['text' => "درس 1"], ['text' => "درس 2"]],
        [['text' => "درس 3"], ['text' => "درس 4"]],
        [['text' => "درس 5"]],
    ];
    $reply_markup = json_encode(["keyboard" => $keyboard, "resize_keyboard" => true]);
    file_get_contents($api_url . "sendMessage?chat_id=$chat_id&text=درس مورد نظر را انتخاب کنید&reply_markup=" . urlencode($reply_markup));
}

function send_lesson_words($chat_id, $lesson_number) {
    global $api_url;
    $words = [
        1 => "لغات درس 1: ...",
        2 => "لغات درس 2: ...",
        3 => "لغات درس 3: ...",
        4 => "لغات درس 4: ...",
        5 => "لغات درس 5: ...",
    ];
    $text = $words[$lesson_number] ?? "درس مورد نظر یافت نشد.";
    file_get_contents($api_url . "sendMessage?chat_id=$chat_id&text=" . urlencode($text));
}

if ($text == "/start") {
    send_main_menu($chat_id); // ارسال منوی اصلی به کاربر
} elseif (strpos($text, "درس") !== false) {
    $lesson_number = intval(str_replace("درس ", "", $text)); // استخراج شماره درس
    send_lesson_words($chat_id, $lesson_number); // ارسال لغات درس
} else {
    file_get_contents($api_url . "sendMessage?chat_id=$chat_id&text=دستور نامعتبر است.");
}
