<?php
namespace iutnc\deefy\exception;

// Exception pour tout problème d'authentification
class AuthnException extends \Exception {

    public function __construct(string $message = "") {
        parent::__construct($message);
    }

};
?>