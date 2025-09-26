<?php
$token = "8275617276:AAEawpLNQ4WKYdeB86Qacr70m18z_z1UaG4";
$website = "https://api.telegram.org/bot".$token;

$input = file_get_contents("php://input");
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'] ?? $update['callback_query']['message']['chat']['id'] ?? null;
$message = $update['message']['text'] ?? "";
$callbackData = $update['callback_query']['data'] ?? "";

if (!$chatId) exit;

// Función para enviar mensajes
function sendMessage($chatId, $text, $keyboard = null) {
    global $website;
    $url = $website . "/sendMessage";
    $post = [
        'chat_id' => $chatId,
        'text' => $text
    ];
    if ($keyboard) {
        $post['reply_markup'] = json_encode($keyboard);
    }
    $finalUrl = $url . "?" . http_build_query($post);
    return file_get_contents($finalUrl);
}

// Menú principal con botones inline
function menuPrincipal($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '🍎 Ver ingredientes', 'callback_data' => 'ingredientes']],
            [['text' => '📂 Ver catálogo', 'callback_data' => 'catalogo']],
            [['text' => '📍 Puntos de entrega', 'callback_data' => 'puntos']],
            [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']]
        ]
    ];
    
    $text = "¡Hola! Somos Food-Lite y vendemos snacks saludables.\n¿En qué podemos ayudarte?";
    sendMessage($chatId, $text, $keyboard);
}

// Procesar mensajes de texto
if ($message == "/start") {
    menuPrincipal($chatId);
}

// Procesar botones inline
if ($callbackData) {
    switch($callbackData) {
        case 'ingredientes':
            sendMessage($chatId, "🍎 Ingredientes disponibles:\n• Barritas: avena, miel, almendras\n• Batidos: frutas, yogur, avena\n• Bolitas: dátiles, cacao, coco");
            break;
        case 'catalogo':
            sendMessage($chatId, "📂 Nuestro catálogo:\n• Energéticos\n• Digestivos\n• Desintoxicantes\n• Veganos\n• Proteicos");
            break;
        case 'puntos':
            sendMessage($chatId, "📍 Puntos de entrega:\n• UES - Odontología\n• Metrocentro San Salvador\n• Metrocentro Lourdes");
            break;
        case 'pedido':
            sendMessage($chatId, "🛒 Para hacer pedido escribe:\nProducto - Cantidad - Punto de entrega");
            break;
    }
}
?>
