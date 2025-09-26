<?php
$token = "8275617276:AAEawpLNQ4WKYdeB86Qacr70m18z_z1UaG4";
$website = "https://api.telegram.org/bot".$token;

$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Detectar chat_id
$chatId = $update['message']['chat']['id'] ?? ($update['callback_query']['message']['chat']['id'] ?? null);

// Detectar mensaje de texto o callback_data
$message = $update['message']['text'] ?? null;
$callback = $update['callback_query']['data'] ?? null;

/* === Función para enviar mensajes con teclado inline === */
function sendMessage($chatId, $text, $inlineKeyboard = null) {
    global $website;
    $post = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    if ($inlineKeyboard) {
        $post['reply_markup'] = json_encode($inlineKeyboard);
    }
    file_get_contents($website."/sendMessage?".http_build_query($post));
}

/* === Función para el menú principal === */
function menuPrincipal($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [["text" => "🍎 Ver ingredientes", "callback_data" => "ingredientes"]],
            [["text" => "📂 Ver catálogo", "callback_data" => "catalogo"]],
            [["text" => "📍 Puntos de entrega", "callback_data" => "puntos"]],
        ]
    ];
    $text = "¡Hola! Somos <b>Food-Lite</b> y vendemos snacks saludables de varios tipos.\n¿En qué podemos ayudarte hoy?";
    sendMessage($chatId, $text, $keyboard);
}

/* === Función para mostrar ingredientes === */
function mostrarIngredientes($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [["text" => "Barritas", "callback_data" => "barritas"]],
            [["text" => "Batidos", "callback_data" => "batidos"]],
            [["text" => "Bolitas", "callback_data" => "bolitas"]],
            [["text" => "Ensaladas", "callback_data" => "ensaladas"]],
            [["text" => "⬅️ Volver al menú", "callback_data" => "menu")]
        ]
    ];
    sendMessage($chatId, "¿De qué producto deseas conocer los ingredientes?", $keyboard);
}

/* === Función para mostrar catálogo === */
function mostrarCatalogo($chatId) {
    $keyboard = [
        'inline_keyboard' => [
            [["text" => "Energéticos", "callback_data" => "energeticos"]],
            [["text" => "Digestivos", "callback_data" => "digestivos"]],
            [["text" => "Desintoxicantes", "callback_data" => "desintoxicantes"]],
            [["text" => "Veganos", "callback_data" => "veganos"]],
            [["text" => "Proteicos", "callback_data" => "proteicos"]],
            [["text" => "⬅️ Volver al menú", "callback_data" => "menu"]]
        ]
    ];
    sendMessage($chatId, "Claro, estas son nuestras categorías disponibles. Elige una:", $keyboard);
}

/* === Función para mostrar puntos de entrega === */
function mostrarPuntos($chatId) {
    $text = "Actualmente entregamos en los siguientes puntos:\n".
            "- Entrada de Odontología (UES)\n".
            "- Metrocentro San Salvador y Lourdes\n".
            "- BINAES en eventos/ferias estudiantiles.";
    sendMessage($chatId, $text);
}

/* === Función para mostrar ingredientes específicos === */
function ingredientesProducto($chatId, $producto) {
    $ingredientes = [
        "barritas" => "Ingredientes de Barritas: avena, miel, almendras, proteína vegetal.",
        "batidos" => "Ingredientes de Batidos: frutas naturales, yogur, avena.",
        "bolitas" => "Ingredientes de Bolitas: dátiles, cacao, coco rallado.",
        "ensaladas" => "Ingredientes de Ensaladas: vegetales frescos, aderezo natural."
    ];
    sendMessage($chatId, $ingredientes[$producto] ?? "Producto no encontrado.");
}

/* === Función para mostrar productos de categoría === */
function productosCategoria($chatId, $categoria) {
    $productos = [
        "energeticos" => "Categoría Energéticos:\n- Batido de banano\n- Barritas de chocolate con proteína\n- Bolitas energéticas",
        "digestivos" => "Categoría Digestivos:\n- Batido de papaya\n- Barritas de avena integral",
        "desintoxicantes" => "Categoría Desintoxicantes:\n- Jugo verde detox\n- Ensalada depurativa",
        "veganos" => "Categoría Veganos:\n- Batido de almendra\n- Bolitas energéticas veganas",
        "proteicos" => "Categoría Proteicos:\n- Batido de proteína\n- Barritas de proteína"
    ];
    sendMessage($chatId, $productos[$categoria] ?? "Categoría no encontrada.");
}

/* === Router principal === */
$action = $callback ?? strtolower($message);

switch($action) {
    case "/start":
        menuPrincipal($chatId);
        break;

    // Menú principal
    case "ingredientes":
        mostrarIngredientes($chatId);
        break;
    case "catalogo":
        mostrarCatalogo($chatId);
        break;
    case "puntos":
        mostrarPuntos($chatId);
        break;

    // Ingredientes de productos
    case "barritas":
    case "batidos":
    case "bolitas":
    case "ensaladas":
        ingredientesProducto($chatId, $action);
        break;

    // Categorías del catálogo
    case "energeticos":
    case "digestivos":
    case "desintoxicantes":
    case "veganos":
    case "proteicos":
        productosCategoria($chatId, $action);
        break;

    // Volver al menú
    case "menu":
        menuPrincipal($chatId);
        break;

    default:
        sendMessage($chatId, "No entendí tu mensaje 🤔. Usa el menú principal:");
        menuPrincipal($chatId);
        break;
}
?>
