<?php
$token = "8275617276:AAEawpLNQ4WKYdeB86Qacr70m18z_z1UaG4";
$website = "https://api.telegram.org/bot".$token;

$input = file_get_contents("php://input");
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'] ?? null;
$message = $update['message']['text'] ?? "";

if (!$chatId) {
    exit;
}

// Usar exactamente el mismo método que funcionó
if (strtolower(trim($message)) == "/start") {
    
    // Primer mensaje: texto plano
    $text = "¡Hola! Somos Food-Lite y vendemos snacks saludables.\n\n";
    $text .= "Escribe una de estas opciones:\n";
    $text .= "• ingredientes\n";
    $text .= "• catalogo\n"; 
    $text .= "• puntos\n";
    $text .= "• pedido\n";
    $text .= "• asesor";
    
    $url = $website."/sendMessage?chat_id=".$chatId."&text=".urlencode($text);
    file_get_contents($url);
    
    // Segundo mensaje: con teclado
    $keyboard = json_encode([
        'keyboard' => [
            [['text' => '🍎 Ingredientes'], ['text' => '📂 Catálogo']],
            [['text' => '📍 Puntos de entrega']],
            [['text' => '🛒 Hacer pedido'], ['text' => '👨‍💼 Asesor']]
        ],
        'resize_keyboard' => true
    ]);
    
    $url2 = $website."/sendMessage";
    $data = "chat_id=".$chatId."&text=".urlencode("O usa estos botones:")."&reply_markup=".urlencode($keyboard);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $data
        ]
    ]);
    
    file_get_contents($url2, false, $context);
    
} else {
    
    // Procesar otras opciones
    $msg = strtolower(trim($message));
    
    switch($msg) {
        case "🍎 ingredientes":
        case "ingredientes":
            $text = "🍎 INGREDIENTES DISPONIBLES:\n\n";
            $text .= "🥜 Barritas: avena, miel, almendras, proteína\n";
            $text .= "🥤 Batidos: frutas naturales, yogur, avena\n";
            $text .= "🍫 Bolitas: dátiles, cacao, coco rallado\n";
            $text .= "🥗 Ensaladas: vegetales frescos, aderezo natural\n\n";
            $text .= "Escribe 'barritas', 'batidos', 'bolitas' o 'ensaladas' para más detalles";
            break;
            
        case "📂 catálogo":
        case "catalogo":
            $text = "📂 NUESTRO CATÁLOGO:\n\n";
            $text .= "⚡ Energéticos - Batidos y barritas\n";
            $text .= "🌱 Digestivos - Especialidades para digestión\n";
            $text .= "🍃 Desintoxicantes - Batidos verdes y shots\n";
            $text .= "🌿 Veganos - 100% origen vegetal\n";
            $text .= "💪 Proteicos - Alto contenido proteico\n\n";
            $text .= "Escribe la categoría que te interese";
            break;
            
        case "📍 puntos de entrega":
        case "puntos":
            $text = "📍 PUNTOS DE ENTREGA:\n\n";
            $text .= "🏫 Universidad:\n";
            $text .= "• Entrada de Odontología (UES)\n\n";
            $text .= "🛍️ Centros Comerciales:\n";
            $text .= "• Metrocentro San Salvador\n";
            $text .= "• Metrocentro Lourdes\n\n";
            $text .= "🎪 Eventos Especiales:\n";
            $text .= "• BINAES en ferias estudiantiles\n\n";
            $text .= "⏰ Horarios: Lunes a Viernes 8:00 AM - 4:00 PM";
            break;
            
        case "🛒 hacer pedido":
        case "pedido":
            $text = "🛒 ¡PERFECTO! Para hacer tu pedido:\n\n";
            $text .= "📝 Escribe en este formato:\n";
            $text .= "Producto - Cantidad - Punto de entrega\n\n";
            $text .= "📋 Ejemplo:\n";
            $text .= "'Batido de banano - 2 - Metrocentro'\n\n";
            $text .= "💰 Te confirmaremos precio y tiempo";
            break;
            
        case "👨‍💼 asesor":
        case "asesor":
            $text = "👨‍💼 CONTACTO DIRECTO:\n\n";
            $text .= "📱 WhatsApp: +503 1234-5678\n";
            $text .= "📧 Email: pedidos@food-lite.com\n\n";
            $text .= "⏰ Horarios de atención:\n";
            $text .= "• Lunes a Viernes: 8:00 AM - 6:00 PM\n";
            $text .= "• Sábados: 9:00 AM - 2:00 PM\n\n";
            $text .= "¡Un asesor te contactará pronto!";
            break;
            
        // Detalles de ingredientes
        case "barritas":
            $text = "🥜 BARRITAS - Ingredientes detallados:\n\n";
            $text .= "• Avena integral certificada\n";
            $text .= "• Miel de abeja pura\n";
            $text .= "• Almendras naturales\n";
            $text .= "• Proteína vegetal (guisante)\n";
            $text .= "• Sin conservantes artificiales";
            break;
            
        case "batidos":
            $text = "🥤 BATIDOS - Ingredientes detallados:\n\n";
            $text .= "• Frutas frescas de temporada\n";
            $text .= "• Yogur natural probiótico\n";
            $text .= "• Avena molida\n";
            $text .= "• Endulzante natural (stevia)\n";
            $text .= "• Agua purificada";
            break;
            
        default:
            $text = "🤔 No entendí tu mensaje.\n\nEscribe /start para ver el menú principal";
            break;
    }
    
    $url = $website."/sendMessage?chat_id=".$chatId."&text=".urlencode($text);
    file_get_contents($url);
}
?>
