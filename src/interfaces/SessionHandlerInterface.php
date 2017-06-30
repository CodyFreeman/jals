<?php
declare(strict_types=1);

namespace freeman\jals\interfaces;

interface SessionHandlerInterface {

    public function write(string $key, $value, string $child = null): bool;

    public function read(string $key, string $child = null);

    public function destroy(): bool;

    public function deleteValue(string $key, string $child = null): bool;

    public function fieldExists(string $key, string $child = null): bool;

    public function sessionExists(): bool;

    public function insureStarted(): void;

}