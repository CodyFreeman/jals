<?php
declare(strict_types=1);

namespace freeman\jals\sessionHandler;

use freeman\jals\interfaces\SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface {

    /**
     * Writes data to the session cookie
     *
     * Will set the session cookies $key equals to $value. If $child is used, it will write the $value to the $key's $child key
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $child
     * @return bool True on success
     */
    public function write(string $key, $value, string $child = null): bool {
        $this->insureStarted();

        if (isset($child, $_SESSION[$key][$child])) {
            $_SESSION[$key][$child] = $value;
            return $this->fieldExists($key, $child);
        } else {
            $_SESSION[$key] = $value;
            return $this->fieldExists($key);
        }
    }

    /**
     * Reads data from session cookie
     *
     * @param string $key
     * @param string|null $child
     * @return int|null Returns value in session cookie or null on failure
     */
    public function read(string $key, string $child = null) {

        if (!$this->sessionExists()) {
            return null;
        }

        if (isset($child, $_SESSION[$key][$child])) {
            return $_SESSION[$key][$child];
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
     * Deletes value from session cookie
     *
     * @param string $key
     * @param string|null $child
     * @return bool True if value does not exist, otherwise false
     */
    public function deleteValue(string $key, string $child = null): bool{

        if(isset($child)){
            unset($_SESSION[$key][$child]);
            return $this->fieldExists($key, $child);
        }

        unset($_SESSION[$key]);

        return $this->fieldExists($key, $child);
    }

    /**
     * Checks if non-empty data in session cookie
     *
     * @param string $key
     * @param string|null $child
     * @return bool True if value is found, false otherwise
     */
    public function fieldExists(string $key, string $child = null):bool {

        if(isset($child)){
            return !empty($this->read($key, $child));
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