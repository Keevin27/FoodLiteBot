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
    file_get_contents($url."?".http_build_query($post));
}

/* === Menú principal === */
function menuPrincipal($chatId) {
    $keyboard = [
        'keyboard' => [
            [["text" => "🍎 Ver ingredientes"], ["text" => "📂 Ver catálogo"]],
            [["text" => "📍 Puntos de entrega"]],
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ];
    $text = "¡Hola! Somos <b>Food-Lite</b> y vendemos snacks saludables de varios tipos. 
¿En qué podemos ayudarte hoy?";
    sendMessage($chatId, $text, $keyboard);
}

/* === Router de mensajes === */
switch(strtolower($message)) {
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
        sendMessage($chatId, "Claro, estas son nuestras categorías disponibles. Elige una:", $keyboard);
        break;

    case "energéticos":
        sendMessage($chatId, "Categoría Energéticos:\n- Batido de banano\n- Barritas de chocolate con proteína\n- Bolitas energéticas");
        break;

    /* Opción 3: Puntos de entrega */
    case "📍 puntos de entrega":
        sendMessage($chatId, "Actualmente entregamos en los siguientes puntos:\n- Entrada de Odontología (UES)\n- Metrocentro San Salvador y Lourdes\n- BINAES en eventos/ferias estudiantiles.");
        break;

    /* Nodo de retorno */
    case "⬅️ volver al menú":
        menuPrincipal($chatId);
        break;

    /* Pedido */
    case "hacer pedido":
        sendMessage($chatId, "¡Genial! Para hacer tu pedido, por favor escribe:\n\n<b>Producto - Cantidad - Punto de entrega</b>");
        break;

    /* Hablar con asesor */
    case "hablar con un asesor":
        sendMessage($chatId, "Claro, un asesor te atenderá pronto. También puedes dejar tu número para que te contacten por WhatsApp.");
        break;

    default:
        sendMessage($chatId, "No entendí tu mensaje 🤔. Usa el menú principal:", null);
        menuPrincipal($chatId);
        break;
}
?>
