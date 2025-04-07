<?php

namespace App\Http\ServerResponse;

class ServerResponse
{
    public const RESPONSE_200 = [
        'status' => 200,
        'message' => 'Успешно'
    ];

    public const RESPONSE_201 = [
        'status' => 201,
        'message' => 'Успешно создано'
    ];

    public const RESPONSE_403 = [
        'status' => 403,
        'message' => 'Доступ запрещен'
    ];

    public const RESPONSE_404 = [
        'status' => 404,
        'message' => 'Не найдено'
    ];

    public const RESPONSE_500 = [
        'status' => 500,
        'message' => 'Ошибка сервера'
    ];

}
