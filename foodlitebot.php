<?php
$token = "8275617276:AAEawpLNQ4WKYdeB86Qacr70m18z_z1UaG4";
$website = "https://api.telegram.org/bot".$token;

$input = file_get_contents("php://input");
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'] ?? null;
$message = $update['message']['text'] ?? "";

if (!$chatId) exit;

// Función para enviar mensajes
function sendMessage($chatId, $text, $keyboard = null) {
    global $website;
    $url = $website."/sendMessage";
    
    $data = "chat_id=".$chatId."&text=".urlencode($text);
    if ($keyboard) {
        $data .= "&reply_markup=".urlencode(json_encode($keyboard));
    }
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $data
        ]
    ]);
    
    return file_get_contents($url, false, $context);
}

// Menú principal
function menuPrincipal($chatId) {
    $keyboard = [
        'keyboard' => [
            [['text' => '🍎 Ver ingredientes'], ['text' => '📂 Ver catálogo']],
            [['text' => '📍 Puntos de entrega']],
            [['text' => '🛒 Hacer pedido'], ['text' => '👨‍💼 Hablar con asesor']]
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ];
    
    $text = "¡Hola! Somos Food-Lite y vendemos snacks saludables de varios tipos.\n¿En qué podemos ayudarte hoy?";
    sendMessage($chatId, $text, $keyboard);
}

// Router de mensajes
$msg = strtolower(trim($message));

switch($msg) {
    case "/start":
        menuPrincipal($chatId);
        break;

    // INGREDIENTES
    case "🍎 ver ingredientes":
        $keyboard = [
            'keyboard' => [
                [['text' => 'Barritas'], ['text' => 'Batidos']],
                [['text' => 'Bolitas'], ['text' => 'Ensaladas']],
                [['text' => '⬅️ Volver al menú']]
            ],
            'resize_keyboard' => true
        ];
        sendMessage($chatId, "¿De qué producto deseas conocer los ingredientes?", $keyboard);
        break;

    case "barritas":
        sendMessage($chatId, "🥜 Ingredientes de Barritas:\n• Avena integral\n• Miel natural\n• Almendras\n• Proteína vegetal");
        break;

    case "batidos":
        sendMessage($chatId, "🥤 Ingredientes de Batidos:\n• Frutas naturales\n• Yogur natural\n• Avena\n• Endulzante natural");
        break;

    case "bolitas":
        sendMessage($chatId, "🍫 Ingredientes de Bolitas:\n• Dátiles naturales\n• Cacao puro\n• Coco rallado\n• Frutos secos");
        break;

    case "ensaladas":
        sendMessage($chatId, "🥗 Ingredientes de Ensaladas:\n• Vegetales frescos orgánicos\n• Aderezo natural casero\n• Semillas y frutos secos");
        break;

    // CATÁLOGO
    case "📂 ver catálogo":
        $keyboard = [
            'keyboard' => [
                [['text' => 'Energéticos'], ['text' => 'Digestivos']],
                [['text' => 'Desintoxicantes'], ['text' => 'Veganos']],
                [['text' => 'Proteicos'], ['text' => '⬅️ Volver al menú']]
            ],
            'resize_keyboard' => true
        ];
        sendMessage($chatId, "Nuestras categorías disponibles.\nElige la que más te interese:", $keyboard);
        break;

    case "energéticos":
        $text = "⚡ ENERGÉTICOS:\n\n";
        $text .= "🍌 Batido de banano - $3.50\n";
        $text .= "🍫 Barritas de chocolate con proteína - $2.75\n";
        $text .= "⚽ Bolitas energéticas - $2.25\n\n";
        $text .= "¿Te interesa alguno? Escribe 'hacer pedido'";
        sendMessage($chatId, $text);
        break;

    case "digestivos":
        $text = "🌱 DIGESTIVOS:\n\n";
        $text .= "🥤 Batido de papaya y avena - $3.25\n";
        $text .= "🍪 Galletas integrales - $2.50\n";
        $text .= "🥗 Ensalada verde especial - $4.00";
        sendMessage($chatId, $text);
        break;

    case "desintoxicantes":
        $text = "🍃 DESINTOXICANTES:\n\n";
        $text .= "🥬 Batido verde detox - $3.75\n";
        $text .= "🧄 Shots de jengibre - $1.50\n";
        $text .= "🥒 Agua saborizada natural - $2.00";
        sendMessage($chatId, $text);
        break;

    case "veganos":
        $text = "🌿 VEGANOS:\n\n";
        $text .= "🥥 Bolitas de coco y cacao - $2.75\n";
        $text .= "🌰 Barritas de almendra - $3.00\n";
        $text .= "🥤 Leche de almendras - $2.50";
        sendMessage($chatId, $text);
        break;

    case "proteicos":
        $text = "💪 PROTEICOS:\n\n";
        $text .= "🥤 Batido de proteína natural - $4.00\n";
        $text .= "🥜 Mix de frutos secos - $3.25\n";
        $text .= "🍳 Wrap proteico - $4.50";
        sendMessage($chatId, $text);
        break;

    // PUNTOS DE ENTREGA
    case "📍 puntos de entrega":
        $text = "📍 PUNTOS DE ENTREGA:\n\n";
        $text .= "🏫 UNIVERSIDAD:\n";
        $text .= "• Entrada de Odontología (UES)\n\n";
        $text .= "🛍️ CENTROS COMERCIALES:\n";
        $text .= "• Metrocentro San Salvador\n";
        $text .= "• Metrocentro Lourdes\n\n";
        $text .= "🎪 EVENTOS ESPECIALES:\n";
        $text .= "• BINAES en ferias estudiantiles\n\n";
        $text .= "⏰ Horarios: Lunes a Viernes 8:00 AM - 4:00 PM";
        sendMessage($chatId, $text);
        break;

    // HACER PEDIDO
    case "🛒 hacer pedido":
    case "hacer pedido":
        $text = "🛒 ¡PERFECTO! Hagamos tu pedido\n\n";
        $text .= "📝 Envía un mensaje con este formato:\n\n";
        $text .= "Producto - Cantidad - Punto de entrega\n\n";
        $text .= "📋 Ejemplo:\n";
        $text .= "Batido de banano - 2 - Metrocentro San Salvador\n\n";
        $text .= "💰 Te confirmaremos precio y tiempo de entrega.";
        sendMessage($chatId, $text);
        break;

    // HABLAR CON ASESOR
    case "👨‍💼 hablar con asesor":
    case "hablar con asesor":
        $text = "👨‍💼 CONTACTO CON ASESOR\n\n";
        $text .= "🕐 Un asesor te atenderá pronto.\n\n";
        $text .= "📱 También puedes contactarnos:\n";
        $text .= "• WhatsApp: +503 1234-5678\n";
        $text .= "• Email: pedidos@food-lite.com\n\n";
        $text .= "⏰ Horario de atención:\n";
        $text .= "Lunes a Viernes: 8:00 AM - 6:00 PM\n";
        $text .= "Sábados: 9:00 AM - 2:00 PM";
        sendMessage($chatId, $text);
        break;

    // VOLVER AL MENÚ
    case "⬅️ volver al menú":
        menuPrincipal($chatId);
        break;

    // MENSAJE NO RECONOCIDO
    default:
        sendMessage($chatId, "🤔 No entendí tu mensaje. Usa las opciones del menú:");
        menuPrincipal($chatId);
        break;
}
?>
