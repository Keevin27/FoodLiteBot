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

if (strtolower(trim($message)) == "/start") {
    
    // Primer mensaje: texto plano (este SÍ funciona)
    $text = "¡Hola! Somos Food-Lite y vendemos snacks saludables.\n\n";
    $text .= "Escribe una de estas opciones:\n";
    $text .= "• ingredientes\n";
    $text .= "• catalogo\n"; 
    $text .= "• puntos\n";
    $text .= "• pedido\n";
    $text .= "• asesor";
    
    $url = $website."/sendMessage?chat_id=".$chatId."&text=".urlencode($text);
    file_get_contents($url);
    
    // MÉTODO MÁS SIMPLE para teclado - usando GET
    $keyboardText = "Menú de opciones:";
    $keyboard = [
        'keyboard' => [
            [['text' => 'Ingredientes']],
            [['text' => 'Catalogo']],
            [['text' => 'Puntos']],
            [['text' => 'Pedido']],
            [['text' => 'Asesor']]
        ],
        'resize_keyboard' => true
    ];
    
    $keyboardJson = json_encode($keyboard);
    $urlKeyboard = $website."/sendMessage?chat_id=".$chatId."&text=".urlencode($keyboardText)."&reply_markup=".urlencode($keyboardJson);
    
    file_get_contents($urlKeyboard);
    
} else {
    
    // Procesar respuestas (sin cambios, porque funciona)
    $msg = strtolower(trim($message));
    
    switch($msg) {
        case "ingredientes":
            $text = "🍎 INGREDIENTES DISPONIBLES:\n\n";
            $text .= "🥜 Barritas: avena, miel, almendras, proteína\n";
            $text .= "🥤 Batidos: frutas naturales, yogur, avena\n";
            $text .= "🍫 Bolitas: dátiles, cacao, coco rallado\n";
            $text .= "🥗 Ensaladas: vegetales frescos, aderezo natural\n\n";
            $text .= "Escribe 'barritas', 'batidos', 'bolitas' o 'ensaladas' para más detalles";
            break;
            
        case "catalogo":
            $text = "📂 NUESTRO CATÁLOGO:\n\n";
            $text .= "⚡ Energéticos - Batidos y barritas energéticas\n";
            $text .= "🌱 Digestivos - Especialidades para digestión\n";
            $text .= "🍃 Desintoxicantes - Batidos verdes y shots\n";
            $text .= "🌿 Veganos - 100% origen vegetal\n";
            $text .= "💪 Proteicos - Alto contenido proteico\n\n";
            $text .= "Escribe 'energeticos', 'digestivos', 'desintoxicantes', 'veganos' o 'proteicos'";
            break;
            
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
            
        case "pedido":
            $text = "🛒 ¡PERFECTO! Para hacer tu pedido:\n\n";
            $text .= "📝 Escribe en este formato:\n";
            $text .= "Producto - Cantidad - Punto de entrega\n\n";
            $text .= "📋 Ejemplo:\n";
            $text .= "'Batido de banano - 2 - Metrocentro'\n\n";
            $text .= "💰 Te confirmaremos precio y tiempo";
            break;
            
        case "asesor":
            $text = "👨‍💼 CONTACTO DIRECTO:\n\n";
            $text .= "📱 WhatsApp: +503 1234-5678\n";
            $text .= "📧 Email: pedidos@food-lite.com\n\n";
            $text .= "⏰ Horarios de atención:\n";
            $text .= "• Lunes a Viernes: 8:00 AM - 6:00 PM\n";
            $text .= "• Sábados: 9:00 AM - 2:00 PM\n\n";
            $text .= "¡Un asesor te contactará pronto!";
            break;
            
        // Categorías del catálogo
        case "energeticos":
            $text = "⚡ ENERGÉTICOS:\n\n";
            $text .= "🍌 Batido de banano - $3.50\n";
            $text .= "🍫 Barritas de chocolate proteico - $2.75\n";
            $text .= "⚽ Bolitas energéticas - $2.25\n\n";
            $text .= "Para pedidos escribe: 'pedido'";
            break;
            
        case "digestivos":
            $text = "🌱 DIGESTIVOS:\n\n";
            $text .= "🥤 Batido papaya-avena - $3.25\n";
            $text .= "🍪 Galletas integrales - $2.50\n";
            $text .= "🥗 Ensalada verde - $4.00";
            break;
            
        // Ingredientes detallados
        case "barritas":
            $text = "🥜 BARRITAS - Ingredientes:\n\n";
            $text .= "• Avena integral certificada\n";
            $text .= "• Miel de abeja pura\n";
            $text .= "• Almendras naturales\n";
            $text .= "• Proteína vegetal\n";
            $text .= "• Sin conservantes artificiales";
            break;
            
        case "batidos":
            $text = "🥤 BATIDOS - Ingredientes:\n\n";
            $text .= "• Frutas frescas de temporada\n";
            $text .= "• Yogur natural probiótico\n";
            $text .= "• Avena molida\n";
            $text .= "• Endulzante natural (stevia)\n";
            $text .= "• Agua purificada";
            break;
            
        default:
            $text = "🤔 No entendí: '$message'\n\nEscribe /start para ver opciones";
            break;
    }
    
    $url = $website."/sendMessage?chat_id=".$chatId."&text=".urlencode($text);
    file_get_contents($url);
}
?>
