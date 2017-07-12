<?php
declare(strict_types=1);

namespace freeman\jals\sessionHandler;

use freeman\jals\interfaces\SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface {

    /**
     * Writes data to the session cookie
     *
     * Will set the session cookies $key equals to $value. If $store is used, it will write the $value to the $key's $store key
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $store
     * @return bool True on success
     */
    public function write(string $key, $value, string $store = null): bool {
        $this->insureStarted();

        if (isset($store, $_SESSION[$key][$store])) {
            $_SESSION[$key][$store] = $value;
            return $this->fieldExists($key, $store);
        } else {
            $_SESSION[$key] = $value;
            return $this->fieldExists($key);
        }
    }

    /**
     * Reads data from session cookie
     *
     * @param string $key
     * @param string|null $store
     * @return int|null Returns value in session cookie or null on failure
     */
    public function read(string $key, string $store = null) {

        if (!$this->sessionExists()) {
            return null;
        }

        if (isset($store, $_SESSION[$key][$store])) {
            return $_SESSION[$key][$store];
        }

        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    /**
     * Deletes data from session cookie
     *
     * @return bool True if deleted, false otherwise
     */
    public function destroy(): bool {

        session_destroy();
        unset($_SESSION);

        return !$this->sessionExists();
    }

    /**
     * Deletes key and value from session cookie
     *
     * @param string $key
     * @param string|null $store
     * @return bool True if value does not exist, otherwise false
     */
    public function deleteKey(string $key, string $store = null): bool{

        if(isset($store)){
            unset($_SESSION[$key][$store]);
            return $this->fieldExists($key, $store);
        }

        unset($_SESSION[$key]);

        return $this->fieldExists($key, $store);
    }

    /**
     * Checks if non-empty data in session cookie
     *
     * @param string $key
     * @param string|null $store
     * @return bool True if value is found, false otherwise
     */
    public function fieldExists(string $key, string $store = null):bool {

        if(isset($store)){
            return !empty($this->read($key, $store));
        }

        return !empty($this->read($key));
    }

    /**
     * Checks if session exists
     *
     * @return bool False if session exists, true otherwise
     */
    public function sessionExists(): bool {

        return !empty(session_id());
    }

    /**
     * Insures session is started, otherwise starts it
     */
    public function insureStarted(): void {

        if (empty(session_id())) {
            session_start();
        }
    }
}