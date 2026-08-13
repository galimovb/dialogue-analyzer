<?php

namespace App\Enums;

enum ErrorCode: string
{
    case BadRequest = 'BAD_REQUEST';
    case Unauthorized = 'UNAUTHORIZED';
    case InvalidCredentials = 'INVALID_CREDENTIALS';
    case Forbidden = 'FORBIDDEN';
    case NotFound = 'NOT_FOUND';
    case MethodNotAllowed = 'METHOD_NOT_ALLOWED';
    case CsrfTokenMismatch = 'CSRF_TOKEN_MISMATCH';
    case ValidationError = 'VALIDATION_ERROR';
    case TooManyRequests = 'TOO_MANY_REQUESTS';
    case InternalError = 'INTERNAL_ERROR';

    /**
     * HTTP-статус по умолчанию для этого кода.
     */
    public function status(): int
    {
        return match ($this) {
            self::BadRequest => 400,
            self::Unauthorized, self::InvalidCredentials => 401,
            self::Forbidden => 403,
            self::NotFound => 404,
            self::MethodNotAllowed => 405,
            self::CsrfTokenMismatch => 419,
            self::ValidationError => 422,
            self::TooManyRequests => 429,
            self::InternalError => 500,
        };
    }

    /**
     * Человекочитаемое описание по умолчанию.
     */
    public function message(): string
    {
        return match ($this) {
            self::BadRequest => 'Некорректный запрос.',
            self::Unauthorized => 'Требуется авторизация.',
            self::InvalidCredentials => 'Неверный email или пароль.',
            self::Forbidden => 'Доступ запрещён.',
            self::NotFound => 'Ресурс не найден.',
            self::MethodNotAllowed => 'Метод не поддерживается.',
            self::CsrfTokenMismatch => 'Недействительный CSRF-токен.',
            self::ValidationError => 'Ошибка валидации.',
            self::TooManyRequests => 'Слишком много запросов.',
            self::InternalError => 'Внутренняя ошибка сервера.',
        };
    }

    /**
     * Подобрать код по HTTP-статусу (для не-типизированных HTTP-исключений).
     */
    public static function fromStatus(int $status): self
    {
        return match ($status) {
            400 => self::BadRequest,
            401 => self::Unauthorized,
            403 => self::Forbidden,
            404 => self::NotFound,
            405 => self::MethodNotAllowed,
            419 => self::CsrfTokenMismatch,
            422 => self::ValidationError,
            429 => self::TooManyRequests,
            default => self::InternalError,
        };
    }
}
