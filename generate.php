<?php

if(isset($_POST['prompt'])) {

    $prompt = $_POST['prompt'];
    $apiKey = "AIzaSyDfsVGpPl2RV6MNnEVVCXVBwDw3NLJ9gEg";
    $modelName = "gemini-flash-latest";

    // Prompt asks for code + setup instructions
    $data = [
        "contents" => [[
            "parts" => [[
                "text" => "Generate a complete working PHP form (single file: frontend + backend) and MySQL SQL schema.

STRICT REQUIREMENTS (VERY IMPORTANT):
1. The SQL table and PHP code MUST match EXACTLY.
2. Use ONE table only.
3. Every input field in the form MUST exist as a column in the SQL table.
4. Column names in SQL MUST be used EXACTLY in PHP (no mismatches).
5. PHP INSERT query MUST use the SAME column names as SQL.
6. Use mysqli (not PDO).
7. Include database connection inside the PHP file.
8. Use POST method.
9. No extra fields, no missing fields.

FORM REQUIREMENTS:
- Generate form fields based on this request: $prompt
- Include proper labels and inputs
- Include submit button
- Include a css style block in the head for basic styling
- On successful form submission, display a success message. On failure, display an error message.
- The css should be clean , smoothan and modern looking and professional and dynamic.

SQL REQUIREMENTS:
- Include CREATE DATABASE
- Include USE database
- Include CREATE TABLE with proper datatypes
- Add AUTO_INCREMENT primary key id

Return ONLY JSON with no markdown, no backticks, no preamble:
{
  \"php_code\": \"...\",
  \"sql_code\": \"...\",
  \"setup_instructions\": \"Step-by-step plain-text instructions on how to set up these files on a local or live server (XAMPP, WAMP, or cPanel). Cover: 1) DB setup using the SQL file, 2) configuring DB credentials in the PHP file, 3) where to place the PHP file, 4) how to access the form in a browser. Keep it concise, numbered, beginner-friendly.\"
}
Prompt: $prompt"
            ]]
        ]]
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-goog-api-key: ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die(json_encode(["error" => "Curl error: " . curl_error($ch)]));
    }
    curl_close($ch);

    $result = json_decode($response, true);

    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        die(json_encode(["error" => "Invalid response from Gemini", "raw" => $result]));
    }

    $text = $result['candidates'][0]['content']['parts'][0]['text'];

    // Clean any markdown fences if model slips them in
    $text = preg_replace('/```json\s*/i', '', $text);
    $text = preg_replace('/```\s*/',      '', $text);
    $text = trim($text);

    $json = json_decode($text, true);

    if (!$json) {
        die(json_encode(["error" => "JSON parsing failed", "raw" => $text]));
    }

    // Save to timestamped folder under /generated/ 
    $timestamp  = date('Y-m-d_H-i-s');
    $slug       = preg_replace('/[^a-z0-9]+/', '-', strtolower(substr($prompt, 0, 40)));
    $folderName = $timestamp . '_' . trim($slug, '-');
    $folderPath = __DIR__ . '/generated/' . $folderName;

    if (!is_dir(__DIR__ . '/generated')) {
        mkdir(__DIR__ . '/generated', 0755, true);
    }
    mkdir($folderPath, 0755, true);

    $phpFileName = 'form.php';
    $sqlFileName = 'database.sql';

    $phpFile = $folderPath . '/form.php';
    $sqlFile = $folderPath . '/database.sql';

    file_put_contents($phpFile, $json['php_code']);
    file_put_contents($sqlFile, $json['sql_code']);

    // Also write latest copies to root 
    // file_put_contents(__DIR__ . '/form.php',     $json['php_code']);
    // file_put_contents(__DIR__ . '/database.sql', $json['sql_code']);

    // Return structured JSON to the frontend
    header('Content-Type: application/json');
    echo json_encode([
        "model"              => $modelName,
        "folder"             => $folderName,
        // IMPORTANT: pass folder + file
        "php_file" => "download.php?file=$folderName/$phpFileName",
        "sql_file" => "download.php?file=$folderName/$sqlFileName",
        "setup_instructions" => $json['setup_instructions'] ?? "No setup instructions returned."
    ]);
}
?>