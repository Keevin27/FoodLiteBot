<?php
$token = "8275617276:AAEawpLNQ4WKYdeB86Qacr70m18z_z1UaG4";
$apiURL = "https://api.telegram.org/bot$token/";

// === Función para enviar mensajes ===
function sendMessage($chatId, $text, $replyMarkup = null) {
    global $apiURL;

    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    if ($replyMarkup) {
        $data['reply_markup'] = json_encode($replyMarkup, JSON_UNESCAPED_UNICODE);
    }

    $ch = curl_init($apiURL . "sendMessage");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);
    curl_close($ch);
}

// === Menú principal ===
function menuPrincipal($chatId) {
    $keyboard = [
        'keyboard' => [
            [["text" => "🍎 Ver ingredientes"], ["text" => "📂 Ver catálogo"]],
            [["text" => "📍 Puntos de entrega"]],
            [["text" => "Hacer pedido"], ["text" => "Hablar con un asesor"]]
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ];
    $text = "¡Hola! Somos <b>Food-Lite</b> y vendemos snacks saludables de varios tipos.  
¿En qué podemos ayudarte hoy?";
    sendMessage($chatId, $text, $keyboard);
}

// === Router de mensajes ===
$update = json_decode(file_get_contents("php://input"), true);
$chatId = $update['message']['chat']['id'] ?? null;
$message = strtolower(trim($update['message']['text'] ?? ""));

switch ($message) {
    // Start
    case "/start":
        menuPrincipal($chatId);
        break;

    // Ingredientes
    case "🍎 ver ingredientes":
        $keyboard = [
            'keyboard' => [
                [["text" => "Barritas"], ["text" => "Batidos"]],
                [["text" => "Bolitas"], ["text" => "Ensaladas"]],
                [["text" => "⬅️ Volver al menú"]]
            ],
            'resize_keyboard' => true
        ];
        sendMessage($chatId, "¿De qué producto deseas conocer los ingredientes?", $keyboard);
        break;

    case "barritas":
        sendMessage($chatId, "Ingredientes de Barritas: avena, miel, almendras, proteína vegetal.");
        break;
    case "batidos":
        sendMessage($chatId, "Ingredientes de Batidos: frutas naturales, yogur, avena.");
        break;
    case "bolitas":
        sendMessage($chatId, "Ingredientes de Bolitas: dátiles, cacao, coco rallado.");
        break;
    case "ensaladas":
        sendMessage($chatId, "Ingredientes de Ensaladas: vegetales frescos, aderezo natural.");
        break;

    // Catálogo
    case "📂 ver catálogo":
        $keyboard = [
            'keyboard' => [
                [["text" => "Energéticos"], ["text" => "Digestivos"]],
                [["text" => "Desintoxicantes"], ["text" => "Veganos"]],
                [["text" => "Proteicos"], ["text" => "⬅️ Volver al menú"]]
            ],
            'resize_keyboard' => true
        ];
        sendMessage($chatId, "Claro, estas son nuestras categorías disponibles. Elige una:", $keyboard);
        break;

    case "energéticos":
        sendMessage($chatId, "Categoría Energéticos:\n- Batido de banano\n- Barritas de chocolate con proteína\n- Bolitas energéticas");
        break;
    case "digestivos":
        sendMessage($chatId, "Categoría Digestivos:\n- Té verde\n- Bolitas de avena y pasas\n- Batido de piña y jengibre");
        break;
    case "desintoxicantes":
        sendMessage($chatId, "Categoría Desintoxicantes:\n- Jugo verde\n- Smoothie detox\n- Ensalada fresca");
        break;
    case "veganos":
        sendMessage($chatId, "Categoría Veganos:\n- Barritas veganas\n- Batido de soya\n- Bolitas de proteína vegetal");
        break;
    case "proteicos":
        sendMessage($chatId, "Categoría Proteicos:\n- Batido de proteína\n- Barritas con whey protein\n- Bolitas energéticas de maní");
        break;

    // Puntos de entrega
    case "📍 puntos de entrega":
        sendMessage($chatId, "Actualmente entregamos en los siguientes puntos:\n- Entrada de Odontología (UES)\n- Metrocentro San Salvador y Lourdes\n- BINAES en eventos/ferias estudiantiles.");
        break;

    // Pedido
    case "hacer pedido":
        sendMessage($chatId, "¡Genial! Para hacer tu pedido, escribe:\n\n<b>Producto - Cantidad - Punto de entrega</b>");
        break;

    // Asesor
    case "hablar con un asesor":
        sendMessage($chatId, "Claro, un asesor te atenderá pronto. También puedes dejar tu número para que te contacten por WhatsApp.");
        break;

    // Volver
    case "⬅️ volver al menú":
        menuPrincipal($chatId);
        break;

    // Por defecto
    default:
        if ($message !== "") {
            sendMessage($chatId, "No entendí tu mensaje 🤔. Usa el menú principal:");
            menuPrincipal($chatId);
        }
        break;
}
?>
