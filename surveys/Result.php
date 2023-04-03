<?php declare(strict_types=1);

namespace Surveys;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

final class Result
{
    private function __construct(
        private bool $successful,
        private array $payload = [],
        private ?string $error = null,
        private ?string $redirectTo = null,
    ) {
    }

    public static function success(array $payload = []): self
    {
        return new self(true, payload: $payload);
    }

    public static function failed(array $payload = [], ?string $error = null): self
    {
        return new self(false, payload: $payload, error: $error);
    }

    public static function redirect(string $redirectTo): self
    {
        return new self(false, redirectTo: $redirectTo);
    }

    public function successful(): bool
    {
        return $this->successful;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function isRedirect(): bool
    {
        return ! is_null($this->redirectTo);
    }

    public function redirectResponse(): RedirectResponse
    {
        return redirect($this->redirectTo);
    }
}
