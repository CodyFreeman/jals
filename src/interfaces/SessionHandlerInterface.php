<?php
declare(strict_types=1);

namespace freeman\jals\interfaces;

interface SessionHandlerInterface {

    public function write(string $key, $value, string $store = null): bool;

    public function read(string $key, string $store = null);

    public function destroy(): bool;

    public function deleteKey(string $key, string $store = null): bool;

    public function fieldExists(string $key, string $store = null): bool;

    public function sessionExists(): bool;

    public function insureStarted(): void;

    public function regenSession(): void;

}