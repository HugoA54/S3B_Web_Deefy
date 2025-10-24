<?php
namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;


class Authz {

       const ROLE_ADMIN = 100;
    const ROLE_USER = 1;
 
    function checkRole(int $role): bool{
        if(isset($_SESSION['user'])){
            $userRole = $_SESSION['user']['role'];
            if($userRole == $role){
                return true;
            }
        }
        return false;
    }

public function checkPlaylistOwner(int $playlistId): bool {
        if (!isset($_SESSION['user'])) {
            return false;
        }

        $userEmail = $_SESSION['user']['email'];
        $userRole = $_SESSION['user']['role'];
        if ($userRole == self::ROLE_ADMIN) {
            return true;
        }
        $repo = DeefyRepository::getInstance();
        $pdo = $repo->getPDO();


        $stmt = $pdo->prepare("
            SELECT u.email 
            FROM user2playlist u2p
            INNER JOIN user u ON u2p.id_user = u.id 
            WHERE u2p.id_pl = ? AND u.email = ?
        ");
        $stmt->execute([$playlistId, $userEmail]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        }

        return false;
    }

}
?>
