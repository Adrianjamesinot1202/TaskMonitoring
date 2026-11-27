<?php
declare(strict_types=1);

if (
    isset($_SERVER['SCRIPT_FILENAME'])
    && realpath((string)$_SERVER['SCRIPT_FILENAME']) === __FILE__
) {
    http_response_code(404);
    exit;
}

/**
 * Send the documented AJAX response and end the request.
 * Commit successful writes before calling this; any open transaction is rolled back.
 */
function json_msg_response(
    int|string $code,
    string $status,
    string $msg,
    ?PDO $conn = null,
    mixed $data = null,
    ?array $field_errors = null,
    ?string $title = null,
    ?string $actionText = null
): void {
    if ($conn instanceof PDO && $conn->inTransaction()) {
        $conn->rollBack();
    }

    $response = [
        'code' => $code,
        'status' => $status,
        'msg' => $msg,
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    if ($field_errors !== null) {
        $response['fields'] = $field_errors;
    }

    if ($title !== null) {
        $response['title'] = $title;
    }

    if ($actionText !== null) {
        $response['actionText'] = $actionText;
    }

    header('Content-Type: application/json; charset=utf-8');

    try {
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        http_response_code(500);
        echo json_encode([
            'code' => 500,
            'status' => 'error',
            'msg' => 'Unable to prepare the server response.',
        ]);
    }

    exit;
}
