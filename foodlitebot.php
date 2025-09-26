<?php
$token = "8275617276:AAEawpLNQ4WKYdeB86Qacr70m18z_z1UaG4";
$website = "https://api.telegram.org/bot".$token;

$input = file_get_contents("php://input");
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'] ?? null;
$message = $update['message']['text'] ?? "";

/* === Función para enviar mensajes === */
function sendMessage($chatId, $text, $replyMarkup = null) {
    global $website;
    $url = $website."/sendMessage";
    
    $post = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    
    if ($replyMarkup) {
        $post['reply_markup'] = json_encode($replyMarkup);
    }
    
    // Usar cURL en lugar de file_get_contents para mejor control
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

/* === Menú principal === */
function menuPrincipal($chatId) {
    $keyboard = [
        'keyboard' => [
            [
                ["text" => "🍎 Ver ingredientes"], 
                ["text" => "📂 Ver catálogo"]
            ],
            [
                ["text" => "📍 Puntos de entrega"]
            ],
            [
                ["text" => "🛒 Hacer pedido"], 
                ["text" => "👨‍💼 Hablar con asesor"]
            ]
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ];
    
    $text = "¡Hola! Somos <b>Food-Lite</b> y vendemos snacks saludables de varios tipos.\n¿En qué podemos ayudarte hoy?";
    
    return sendMessage($chatId, $text, $keyboard);
}

// Validar que tenemos datos válidos
if (!$chatId || !$message) {
    exit;
}

/* === Router de mensajes === */
switch(strtolower(trim($message))) {
    case "/start":
        menuPrincipal($chatId);
        break;

    /* Opción 1: Ingredientes */
    case "🍎 ver ingredientes":
        $keyboard = [
            'keyboard' => [
                [["text" => "Barritas"], ["text" => "Batidos"]],
                [["text" => "Bolitas"], ["text" => "Ensaladas"]],
                [["text" => "⬅️ Volver al menú"]],
            ],
            'resize_keyboard' => true
        ];
        sendMessage($chatId, "¿De qué producto deseas conocer los ingredientes?", $keyboard);
        break;

    case "barritas":
        sendMessage($chatId, "🥜 <b>Ingredientes de Barritas:</b>\n• Avena integral\n• Miel natural\n• Almendras\n• Proteína vegetal");
        break;
        
    case "batidos":
        sendMessage($chatId, "🥤 <b>Ingredientes de Batidos:</b>\n• Frutas naturales\n• Yogur natural\n• Avena\n• Endulzante natural");
        break;
        
    case "bolitas":
        sendMessage($chatId, "🍫 <b>Ingredientes de Bolitas:</b>\n• Dátiles naturales\n• Cacao puro\n• Coco rallado\n• Frutos secos");
        break;
        
    case "ensaladas":
        sendMessage($chatId, "🥗 <b>Ingredientes de Ensaladas:</b>\n• Vegetales frescos orgánicos\n• Aderezo natural casero\n• Semillas y frutos secos");
        break;

    /* Opción 2: Catálogo */
    case "📂 ver catálogo":
        $keyboard = [
            'keyboard' => [
                [["text" => "Energéticos"], ["text" => "Digestivos"]],
                [["text" => "Desintoxicantes"], ["text" => "Veganos"]],
                [["text" => "Proteicos"], ["text" => "⬅️ Volver al menú"]],
            ],
            'resize_keyboard' => true
        ];
        sendMessage($chatId, "📋 <b>Nuestras categorías disponibles:</b>\nElige la que más te interese:", $keyboard);
        break;

    case "energéticos":
        $text = "⚡ <b>Categoría Energéticos:</b>\n\n";
        $text .= "🍌 Batido de banano - $3.50\n";
        $text .= "🍫 Barritas de chocolate con proteína - $2.75\n";
        $text .= "⚽ Bolitas energéticas - $2.25\n\n";
        $text .= "¿Te interesa alguno? ¡Escribe <b>'hacer pedido'</b>!";
        sendMessage($chatId, $text);
        break;

    case "digestivos":
        $text = "🌱 <b>Categoría Digestivos:</b>\n\n";
        $text .= "🥤 Batido de papaya y avena - $3.25\n";
        $text .= "🍪 Galletas integrales - $2.50\n";
        $text .= "🥗 Ensalada verde especial - $4.00";
        sendMessage($chatId, $text);
        break;

    case "desintoxicantes":
        $text = "🍃 <b>Categoría Desintoxicantes:</b>\n\n";
        $text .= "🥬 Batido verde detox - $3.75\n";
        $text .= "🧄 Shots de jengibre - $1.50\n";
        $text .= "🥒 Agua saborizada natural - $2.00";
        sendMessage($chatId, $text);
        break;

    case "veganos":
        $text = "🌿 <b>Categoría Veganos:</b>\n\n";
        $text .= "🥥 Bolitas de coco y cacao - $2.75\n";
        $text .= "🌰 Barritas de almendra - $3.00\n";
        $text .= "🥤 Leche de almendras - $2.50";
        sendMessage($chatId, $text);
        break;

    case "proteicos":
        $text = "💪 <b>Categoría Proteicos:</b>\n\n";
        $text .= "🥤 Batido de proteína natural - $4.00\n";
        $text .= "🥜 Mix de frutos secos - $3.25\n";
        $text .= "🍳 Wrap proteico - $4.50";
        sendMessage($chatId, $text);
        break;

    /* Opción 3: Puntos de entrega */
    case "📍 puntos de entrega":
        $text = "📍 <b>Puntos de entrega disponibles:</b>\n\n";
        $text .= "🏫 <b>Universidad:</b>\n";
        $text .= "   • Entrada de Odontología (UES)\n\n";
        $text .= "🛍️ <b>Centros Comerciales:</b>\n";
        $text .= "   • Metrocentro San Salvador\n";
        $text .= "   • Metrocentro Lourdes\n\n";
        $text .= "🎪 <b>Eventos especiales:</b>\n";
        $text .= "   • BINAES en ferias estudiantiles\n\n";
        $text .= "💡 <i>Horarios: Lunes a Viernes 8:00 AM - 4:00 PM</i>";
        sendMessage($chatId, $text);
        break;

    /* Nodo de retorno */
    case "⬅️ volver al menú":
        menuPrincipal($chatId);
        break;

    /* Pedido */
    case "🛒 hacer pedido":
    case "hacer pedido":
        $text = "🛒 <b>¡Perfecto! Hagamos tu pedido</b>\n\n";
        $text .= "📝 Para procesar tu orden, envía un mensaje con este formato:\n\n";
        $text .= "<b>Producto - Cantidad - Punto de entrega</b>\n\n";
        $text .= "📋 <b>Ejemplo:</b>\n";
        $text .= "<i>Batido de banano - 2 - Metrocentro San Salvador</i>\n\n";
        $text .= "💰 Te confirmaremos precio y tiempo de entrega.";
        sendMessage($chatId, $text);
        break;

    /* Hablar con asesor */
    case "👨‍💼 hablar con asesor":
    case "hablar con un asesor":
        $text = "👨‍💼 <b>Contacto con Asesor</b>\n\n";
        $text .= "🕐 Un asesor te atenderá pronto durante nuestro horario de atención.\n\n";
        $text .= "📱 <b>También puedes contactarnos por:</b>\n";
        $text .= "• WhatsApp: +503 1234-5678\n";
        $text .= "• Email: pedidos@food-lite.com\n\n";
        $text .= "⏰ <b>Horario de atención:</b>\n";
        $text .= "Lunes a Viernes: 8:00 AM - 6:00 PM\n";
        $text .= "Sábados: 9:00 AM - 2:00 PM";
        sendMessage($chatId, $text);
        break;

    default:
        $text = "🤔 No entendí tu mensaje.\n\n";
        $text .= "Por favor, usa las opciones del menú principal:";
        sendMessage($chatId, $text);
        menuPrincipal($chatId);
        break;
}
?>
