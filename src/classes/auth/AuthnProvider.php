<?php
namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {

    public static function signin(string $email, string $password): array {
        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();

        $stmt = $pdo->prepare("SELECT id, email, passwd, role FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row || !password_verify($password, $row['passwd'])) {
            throw new AuthnException("Email ou mot de passe incorrect.");
        }

        return [
            'id'    => $row['id'],
            'email' => $row['email'],
            'role'  => $row['role']
        ];
    }

    public static function register(string $email, string $password): void {
        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();

        if (!$pdo) {
            throw new AuthnException("Connexion à la base de données impossible.");
        }

        if (strlen($password) < 10) {
            throw new AuthnException("Le mot de passe doit contenir au moins 10 caractères.");
        }

        $stmt = $pdo->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
            throw new AuthnException("Un compte avec cet email existe déjà.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO user (email, passwd, role) VALUES (?, ?, 1)");
        $insert->execute([$email, $hashedPassword]);
    }

    public static function getSignedInUser(): ?array {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        throw new AuthnException("Aucun utilisateur connecté.");
    }
}
