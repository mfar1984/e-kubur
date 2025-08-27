<?php
/**
 * Database Connection for PPJUB Public Website
 * Connects to existing E-Kubur database
 */

// Database configuration - same as E-Kubur system
$host = 'localhost';
$dbname = 'ekubur_db'; // Update with your actual database name
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

/**
 * Search Kematian by Nama or IC
 */
function searchKematian($search, $type) {
    global $pdo;
    
    try {
        if ($type == 'nama') {
            $sql = "SELECT 
                        k.id,
                        k.nama,
                        k.tarikh_lahir,
                        k.ic,
                        k.tarikh_meninggal,
                        k.latitude,
                        k.longitude,
                        k.lokasi,
                        k.status_perkhidmatan,
                        k.created_at
                    FROM kematian k 
                    WHERE k.nama LIKE :search 
                    ORDER BY k.tarikh_meninggal DESC";
        } else {
            $sql = "SELECT 
                        k.id,
                        k.nama,
                        k.tarikh_lahir,
                        k.ic,
                        k.tarikh_meninggal,
                        k.latitude,
                        k.longitude,
                        k.lokasi,
                        k.status_perkhidmatan,
                        k.created_at
                    FROM kematian k 
                    WHERE k.ic LIKE :search 
                    ORDER BY k.tarikh_meninggal DESC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);
        return $stmt->fetchAll();
        
    } catch(PDOException $e) {
        error_log("Search error: " . $e->getMessage());
        return [];
    }
}

/**
 * Search PPJUB by Nama or IC
 */
function searchPPJUB($search, $type) {
    global $pdo;
    
    try {
        if ($type == 'nama') {
            $sql = "SELECT 
                        p.id,
                        p.nama,
                        p.ic,
                        p.telefon,
                        p.email,
                        p.alamat,
                        p.status_keahlian,
                        p.created_at
                    FROM ppjub p 
                    WHERE p.nama LIKE :search 
                    ORDER BY p.created_at DESC";
        } else {
            $sql = "SELECT 
                        p.id,
                        p.nama,
                        p.ic,
                        p.telefon,
                        p.email,
                        p.alamat,
                        p.status_keahlian,
                        p.created_at
                    FROM ppjub p 
                    WHERE p.ic LIKE :search 
                    ORDER BY p.created_at DESC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);
        return $stmt->fetchAll();
        
    } catch(PDOException $e) {
        error_log("PPJUB search error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get Kematian Details by ID
 */
function getKematianDetails($id) {
    global $pdo;
    
    try {
        $sql = "SELECT 
                    k.*,
                    w.nama as nama_waris,
                    w.hubungan,
                    w.telefon as telefon_waris
                FROM kematian k
                LEFT JOIN waris w ON k.id = w.kematian_id
                WHERE k.id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
        
    } catch(PDOException $e) {
        error_log("Details error: " . $e->getMessage());
        return null;
    }
}

/**
 * Sanitize input for security
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Format date to Malaysian format
 */
function formatDate($date) {
    if (!$date) return '-';
    return date('d/m/Y', strtotime($date));
}

/**
 * Check if search term is valid
 */
function isValidSearch($search) {
    return strlen($search) >= 2;
}
?>
