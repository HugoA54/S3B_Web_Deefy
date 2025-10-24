<?php



namespace iutnc\deefy\repository;

use iutnc\deefy\audio\tracks\AlbumTrack;
use PDO;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use DateTime;

class DeefyRepository {

    static private array $config = [];
    static private ?DeefyRepository $instance = null;
    private ?PDO $pdo = null;

    static public function setConfig($file): void {
        self::$config = parse_ini_file($file);
    }

    static public function getInstance(): DeefyRepository {
        if (self::$instance === null) {
            self::$instance = new DeefyRepository();
        }
        return self::$instance;
    }

private function __construct() {
    if (!empty(self::$config)) {
        $driver = self::$config['driver'];
        $host = self::$config['host'];
        $database = self::$config['database'];
        $user = self::$config['username'];
        $pass = self::$config['password'];
        $dsn = "$driver:host=$host;dbname=$database";
        try {
            $this->pdo = new PDO($dsn, $user, $pass);
        } catch (\PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Configuration vide<br>";
    }
}

    public function getPDO(): ?PDO {
        return $this->pdo;
    }

     public function findAllPlaylists(): array {

        $playlists = [];
        if ($this->pdo) {

            $stmt = $this->pdo->query("SELECT * FROM playlist");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $playlists[] = $row;
            }
        }
        return $playlists;
    }

public function findPlaylistById(int $id): ?Playlist {
    if (!$this->pdo) {
        return null;
    }

    $stmt = $this->pdo->prepare("SELECT * FROM playlist WHERE id = ?");
    $stmt->execute([$id]);
    $playlistData = $stmt->fetch(PDO::FETCH_ASSOC);
    

    if (!$playlistData) {
        return null;
    }

    $stmt = $this->pdo->prepare("
        SELECT t.*, p2t.no_piste_dans_liste
        FROM track t
        INNER JOIN playlist2track p2t ON t.id = p2t.id_track
        WHERE p2t.id_pl = ?
        ORDER BY p2t.no_piste_dans_liste ASC
    ");
    $stmt->execute([$id]);
    $tracksData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tracks = [];
    foreach ($tracksData as $trackData) {
            if ($trackData['type'] === 'A') {
                // Créer un AlbumTrack
                $track = new AlbumTrack(
                    $trackData['filename'],
                    $trackData['numero_album'] ?? 0
                );
            } else {
                $track = new PodcastTrack(
                    $trackData['titre'],
                    $trackData['filename']
                );
            }
            $tracks[] = $track;
        
    }
    return new Playlist($playlistData['nom'], $tracks);
}

public function saveEmptyPlaylist(string $name): bool {
    if ($this->pdo) {
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (?)");
        return $stmt->execute([$name]);
    }
    return false;
}

public function saveTrack(
    string $titre,
    string $genre,
    int $duree,
    string $filename,
    string $type,
    ?string $artiste_album = null,
    ?string $titre_album = null,
    ?int $annee_album = null,
    ?int $numero_album = null,
    ?string $auteur_podcast = null,
    ?string $date_podcast = null
): bool {
    if ($this->pdo) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO track (
                titre, genre, duree, filename, type,
                artiste_album, titre_album, annee_album, numero_album,
                auteur_podcast, date_posdcast
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $titre, $genre, $duree, $filename, $type,
            $artiste_album, $titre_album, $annee_album, $numero_album,
            $auteur_podcast, $date_podcast
        ]);
    }
    return false;
}

    public function addTrackToPlaylist(int $trackId, int $playlistId, int $no_piste_dans_liste = 1): bool {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (?, ?, ?)"
            );
            return $stmt->execute([$playlistId, $trackId, $no_piste_dans_liste]);
        }
        return false;
    }


 
}