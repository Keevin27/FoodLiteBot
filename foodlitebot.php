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

// Menú principal
function menuPrincipal($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '🍎 Ver ingredientes', 'callback_data' => 'ingredientes']],
            [['text' => '📂 Ver catálogo', 'callback_data' => 'catalogo']],
            [['text' => '📍 Puntos de entrega', 'callback_data' => 'puntos']],
            [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']],
            [['text' => '👨‍💼 Hablar con asesor', 'callback_data' => 'asesor']]
        ]
    ];
    
    $text = "¡Hola! Somos Food-Lite y vendemos snacks saludables de varios tipos.\n¿En qué podemos ayudarte hoy?";
    sendMessage($chatId, $text, $keyboard);
}

// Menú de ingredientes
function menuIngredientes($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '🥜 Barritas', 'callback_data' => 'ing_barritas']],
            [['text' => '🥤 Batidos', 'callback_data' => 'ing_batidos']],
            [['text' => '🍫 Bolitas', 'callback_data' => 'ing_bolitas']],
            [['text' => '🥗 Ensaladas', 'callback_data' => 'ing_ensaladas']],
            [['text' => '⬅️ Volver al menú', 'callback_data' => 'menu']]
        ]
    ];
    
    $text = "🍎 INGREDIENTES\n\n¿De qué producto deseas conocer los ingredientes?";
    sendMessage($chatId, $text, $keyboard);
}

// Menú de catálogo
function menuCatalogo($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [['text' => '⚡ Energéticos', 'callback_data' => 'cat_energeticos']],
            [['text' => '🌱 Digestivos', 'callback_data' => 'cat_digestivos']],
            [['text' => '🍃 Desintoxicantes', 'callback_data' => 'cat_desintoxicantes']],
            [['text' => '🌿 Veganos', 'callback_data' => 'cat_veganos']],
            [['text' => '💪 Proteicos', 'callback_data' => 'cat_proteicos']],
            [['text' => '⬅️ Volver al menú', 'callback_data' => 'menu']]
        ]
    ];
    
    $text = "📂 CATÁLOGO\n\nElige la categoría que más te interese:";
    sendMessage($chatId, $text, $keyboard);
}

// Procesar mensajes de texto
if ($message == "/start") {
    menuPrincipal($chatId);
}

// Procesar botones inline
if ($callbackData) {
    switch($callbackData) {
        // MENÚ PRINCIPAL
        case 'menu':
            menuPrincipal($chatId);
            break;
            
        case 'ingredientes':
            menuIngredientes($chatId);
            break;
            
        case 'catalogo':
            menuCatalogo($chatId);
            break;
            
        case 'puntos':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver al menú', 'callback_data' => 'menu']]
                ]
            ];
            
            $text = "📍 PUNTOS DE ENTREGA\n\n";
            $text .= "🏫 UNIVERSIDAD:\n";
            $text .= "• Entrada de Odontología (UES)\n\n";
            $text .= "🛍️ CENTROS COMERCIALES:\n";
            $text .= "• Metrocentro San Salvador\n";
            $text .= "• Metrocentro Lourdes\n\n";
            $text .= "🎪 EVENTOS ESPECIALES:\n";
            $text .= "• BINAES en ferias estudiantiles\n\n";
            $text .= "⏰ Horarios: Lunes a Viernes 8:00 AM - 4:00 PM";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'pedido':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver al menú', 'callback_data' => 'menu']]
                ]
            ];
            
            $text = "🛒 ¡PERFECTO! Hagamos tu pedido\n\n";
            $text .= "📝 Para procesar tu orden, envía un mensaje con este formato:\n\n";
            $text .= "Producto - Cantidad - Punto de entrega\n\n";
            $text .= "📋 EJEMPLO:\n";
            $text .= "Batido de banano - 2 - Metrocentro San Salvador\n\n";
            $text .= "💰 Te confirmaremos precio y tiempo de entrega.";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'asesor':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver al menú', 'callback_data' => 'menu']]
                ]
            ];
            
            $text = "👨‍💼 CONTACTO CON ASESOR\n\n";
            $text .= "🕐 Un asesor te atenderá pronto durante nuestro horario.\n\n";
            $text .= "📱 TAMBIÉN PUEDES CONTACTARNOS:\n";
            $text .= "• WhatsApp: +503 1234-5678\n";
            $text .= "• Email: pedidos@food-lite.com\n\n";
            $text .= "⏰ HORARIO DE ATENCIÓN:\n";
            $text .= "• Lunes a Viernes: 8:00 AM - 6:00 PM\n";
            $text .= "• Sábados: 9:00 AM - 2:00 PM";
            
            sendMessage($chatId, $text, $keyboard);
            break;
        
        // INGREDIENTES DETALLADOS
        case 'ing_barritas':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver a ingredientes', 'callback_data' => 'ingredientes']]
                ]
            ];
            
            $text = "🥜 BARRITAS - Ingredientes detallados:\n\n";
            $text .= "• Avena integral certificada\n";
            $text .= "• Miel de abeja pura\n";
            $text .= "• Almendras naturales\n";
            $text .= "• Proteína vegetal (guisante)\n";
            $text .= "• Sin conservantes artificiales\n";
            $text .= "• Sin gluten";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'ing_batidos':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver a ingredientes', 'callback_data' => 'ingredientes']]
                ]
            ];
            
            $text = "🥤 BATIDOS - Ingredientes detallados:\n\n";
            $text .= "• Frutas frescas de temporada\n";
            $text .= "• Yogur natural probiótico\n";
            $text .= "• Avena molida\n";
            $text .= "• Endulzante natural (stevia)\n";
            $text .= "• Agua purificada\n";
            $text .= "• Hielo natural";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'ing_bolitas':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver a ingredientes', 'callback_data' => 'ingredientes']]
                ]
            ];
            
            $text = "🍫 BOLITAS - Ingredientes detallados:\n\n";
            $text .= "• Dátiles naturales Medjool\n";
            $text .= "• Cacao puro orgánico\n";
            $text .= "• Coco rallado natural\n";
            $text .= "• Frutos secos mixtos\n";
            $text .= "• Aceite de coco virgen\n";
            $text .= "• Vainilla natural";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'ing_ensaladas':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '⬅️ Volver a ingredientes', 'callback_data' => 'ingredientes']]
                ]
            ];
            
            $text = "🥗 ENSALADAS - Ingredientes detallados:\n\n";
            $text .= "• Vegetales frescos orgánicos\n";
            $text .= "• Lechuga hidropónica\n";
            $text .= "• Tomate cherry\n";
            $text .= "• Aderezo natural casero\n";
            $text .= "• Semillas de chía y girasol\n";
            $text .= "• Frutos secos tostados";
            
            sendMessage($chatId, $text, $keyboard);
            break;
        
        // CATÁLOGO DETALLADO
        case 'cat_energeticos':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']],
                    [['text' => '⬅️ Volver a catálogo', 'callback_data' => 'catalogo']]
                ]
            ];
            
            $text = "⚡ ENERGÉTICOS\n\n";
            $text .= "🍌 Batido de banano - $3.50\n";
            $text .= "🍫 Barritas de chocolate con proteína - $2.75\n";
            $text .= "⚽ Bolitas energéticas - $2.25\n";
            $text .= "🥤 Smoothie energético - $4.00\n";
            $text .= "🍪 Galletas energéticas - $2.50\n\n";
            $text .= "💡 Perfectos para antes del ejercicio o estudio intenso.";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'cat_digestivos':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']],
                    [['text' => '⬅️ Volver a catálogo', 'callback_data' => 'catalogo']]
                ]
            ];
            
            $text = "🌱 DIGESTIVOS\n\n";
            $text .= "🥤 Batido de papaya y avena - $3.25\n";
            $text .= "🍪 Galletas integrales - $2.50\n";
            $text .= "🥗 Ensalada verde especial - $4.00\n";
            $text .= "🍯 Té digestivo con miel - $2.00\n\n";
            $text .= "💡 Ideales después de comidas pesadas.";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'cat_desintoxicantes':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']],
                    [['text' => '⬅️ Volver a catálogo', 'callback_data' => 'catalogo']]
                ]
            ];
            
            $text = "🍃 DESINTOXICANTES\n\n";
            $text .= "🥬 Batido verde detox - $3.75\n";
            $text .= "🧄 Shots de jengibre - $1.50\n";
            $text .= "🥒 Agua saborizada natural - $2.00\n";
            $text .= "🍋 Limonada detox - $2.25\n\n";
            $text .= "💡 Para limpiar tu organismo naturalmente.";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'cat_veganos':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']],
                    [['text' => '⬅️ Volver a catálogo', 'callback_data' => 'catalogo']]
                ]
            ];
            
            $text = "🌿 VEGANOS\n\n";
            $text .= "🥥 Bolitas de coco y cacao - $2.75\n";
            $text .= "🌰 Barritas de almendra - $3.00\n";
            $text .= "🥤 Leche de almendras - $2.50\n";
            $text .= "🥗 Bowl vegano completo - $4.50\n\n";
            $text .= "💡 100% ingredientes de origen vegetal.";
            
            sendMessage($chatId, $text, $keyboard);
            break;
            
        case 'cat_proteicos':
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🛒 Hacer pedido', 'callback_data' => 'pedido']],
                    [['text' => '⬅️ Volver a catálogo', 'callback_data' => 'catalogo']]
                ]
            ];
            
            $text = "💪 PROTEICOS\n\n";
            $text .= "🥤 Batido de proteína natural - $4.00\n";
            $text .= "🥜 Mix de frutos secos - $3.25\n";
            $text .= "🍳 Wrap proteico - $4.50\n";
            $text .= "🥛 Smoothie proteico - $4.25\n\n";
            $text .= "💡 Perfectos para después del gimnasio.";
            
            sendMessage($chatId, $text, $keyboard);
            break;
    }
}

// Procesar mensajes de texto normales (para pedidos)
if ($message && $message != "/start") {
    // Si contiene guiones, probablemente es un pedido
    if (strpos($message, "-") !== false) {
        $text = "✅ PEDIDO RECIBIDO\n\n";
        $text .= "📋 Tu pedido: " . $message . "\n\n";
        $text .= "🕐 Te confirmaremos el precio y tiempo de entrega en breve.\n";
        $text .= "📱 También puedes llamarnos al +503 1234-5678\n\n";
        $text .= "¡Gracias por elegir Food-Lite! 🍎";
        
        sendMessage($chatId, $text);
        
        // Volver al menú después de 2 segundos (opcional)
        sleep(2);
        menuPrincipal($chatId);
    }
}
?>
