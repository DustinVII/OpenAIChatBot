<?php
// PHP CLI ChatGPT clientt
// Run with: php main.php
// Create your API key at https://platform.openai.com/account/api-keys

$apiKey = "xxx"; // Replace with your OpenAI API key
$model = "gpt-3.5-turbo";

function chat_with_gpt($prompt, $apiKey, $model)
{
    $url = "https://api.openai.com/v1/chat/completions";
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ];

    $data = [
        "model" => $model,
        "messages" => [
            ["role" => "user", "content" => $prompt]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return "🌐 Network error — " . curl_error($ch);
    }

    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true);

    // Friendly error messages
    switch ($statusCode) {
        case 401:
            return "Invalid API key. Please verify your key.";
        case 429:
            return "Rate limit reached or insufficient quota. Check your billing settings.";
        case 500:
        case 502:
        case 503:
        case 504:
            return "Server error — please try again later.";
    }

    if (isset($decoded['error'])) {
        return "API Error: " . $decoded['error']['message'];
    }

    return $decoded['choices'][0]['message']['content'] ?? "No response received.";
}

// Main chat loop
echo "ChatGPT is ready! Type 'exit' or 'quit' to stop.\n\n";

while (true) {
    echo "You: ";
    $user_input = trim(fgets(STDIN));

    if (in_array(strtolower($user_input), ["quit", "exit", "bye"])) {
        echo "Goodbye!\n";
        break;
    }

    $response = chat_with_gpt($user_input, $apiKey, $model);
    echo "Chatbot: $response\n\n";
}
?>
