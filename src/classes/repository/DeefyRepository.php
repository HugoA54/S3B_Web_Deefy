<?php



namespace iutnc\deefy\repository;
session_start();

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

public function findAllPlaylists(): array
{
    $stmt = $this->pdo->query("SELECT id, nom FROM playlist ORDER BY id ASC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public function findAllTrack(): array
{
    $stmt = $this->pdo->query("SELECT id, titre FROM track ORDER BY id ASC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

public function saveEmptyPlaylist(string $name): bool
{
    if (!$this->pdo) {
        return false;
    }

    $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (?)");
    $success = $stmt->execute([$name]);

    if (!$success) {
        return false;
    }
    if (isset($_SESSION['user'])) {
$userId = $_SESSION['user']['id'];
        $playlistId = $this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (?, ?)");
        $success = $stmt->execute([$userId, $playlistId]);

        return $success;
    }
    return true;
}


public function saveTrack(\iutnc\deefy\audio\tracks\AudioTrack $track): bool
{
    if ($this->pdo) {
        $stmt = $this->pdo->prepare("
            INSERT INTO track (
                titre, genre, duree, filename, type,
                artiste_album, titre_album, annee_album, numero_album,
                auteur_podcast, date_posdcast
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $className = get_class($track);
        if (str_contains($className, 'PodcastTrack')) {
            $type = 'P';
        } else {
            $type = 'A';
        }

        $titre = $track->__get('titre');
        $genre = $track->__get('genre');
        $duree = $track->__get('duree');
        $filename = $track->__get('fichier');

        $artiste_album = null;
        $titre_album = null;
        $annee_album = null;
        $numero_album = null;
        $auteur_podcast = null;
        $date_posdcast = null;

        if ($type === 'A') {
            $artiste_album = $track->__get('artiste') ?? null;
            $titre_album = $track->__get('album') ?? null;
            $annee_album = $track->__get('annee') ?? null;
            $numero_album = $track->__get('numeroPiste') ?? null;
        } else {
            $auteur_podcast = $track->__get('creator') ?? null;
            $date_posdcast = $track->__get('date') ?? null;
        }

        return $stmt->execute([
            $titre,
            $genre,
            $duree,
            $filename,
            $type,
            $artiste_album,
            $titre_album,
            $annee_album,
            $numero_album,
            $auteur_podcast,
            $date_posdcast
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