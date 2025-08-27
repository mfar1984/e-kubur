<?php
session_start();
$pageTitle = 'E-Kubur - Sistem Pengurusan Kubur';
$searchResults = [];
$searchType = '';
$searchTerm = '';
$errorMessage = '';

// API Configuration
$apiBaseUrl = 'http://localhost:8000/api/v1';

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchType = trim($_POST['search_type'] ?? '');
    $searchTerm = trim($_POST['search_term'] ?? '');
    
    if (strlen($searchTerm) >= 2) {
        // Determine search type (nama or ic)
        $type = 'nama'; // Default to name search
        if (preg_match('/^\d/', $searchTerm)) {
            $type = 'ic'; // If starts with number, search by IC
        }
        
        // Call Laravel API
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiBaseUrl . '/kematian/search?q=' . urlencode($searchTerm) . '&type=' . $type,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $apiResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $result = json_decode($apiResponse, true);
            if ($result && isset($result['success']) && $result['success']) {
                $searchResults = $result['data'];
            } else {
                $errorMessage = $result['message'] ?? 'Ralat semasa mencari data.';
            }
        } else {
            $errorMessage = 'Ralat sambungan ke server. Sila cuba lagi.';
        }
    } else {
        $errorMessage = 'Sila masukkan sekurang-kurangnya 2 aksara untuk carian.';
    }
}

include 'includes/header.php';
?>

<!-- Hero Section removed per request -->

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="search-container">
            <div class="search-header">
                <div class="search-icon-large">🔍</div>
                <h2>Carian Almarhum / Almarhumah</h2>
                <p class="search-helper">Sila masukkan nama atau nombor kad pengenalan untuk mencari maklumat Almarhum / Almarhumah</p>
            </div>
            
            <?php if ($errorMessage): ?>
                <div class="alert alert-error" role="alert" aria-live="polite"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            
            <!-- Search Form -->
            <form method="POST" class="search-form" novalidate>
                <div class="search-inputs">
                    <div class="input-group">
                        <label for="search_term" class="sr-only">Kata Carian</label>
                        <input type="text" 
                               id="search_term" 
                               name="search_term" 
                               value="<?php echo htmlspecialchars($searchTerm); ?>"
                               placeholder="Masukkan nama atau nombor Kad Pengenalan Almarhum / Almarhumah"
                               inputmode="text"
                               enterkeyhint="search"
                               autocomplete="off"
                               autocapitalize="none"
                               spellcheck="false"
                               required>
                    </div>
                    
                    <input type="hidden" name="search_type" id="search_type" value="kematian">
                    <button type="submit" class="search-btn" aria-label="Cari">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
            
            <!-- Search Results -->
            <?php if (!empty($searchResults)): ?>
                <div class="search-results">
                    <h3>Hasil Carian (<?php echo count($searchResults); ?> rekod)</h3>
                    
                    <!-- Kematian Results -->
                    <div class="results-grid">
                            <?php foreach ($searchResults as $result): ?>
                                <div class="result-card">
                                    <div class="result-block">
                                        <div class="result-section-title"><?php echo htmlspecialchars($result['honorific']); ?> Info</div>
                                        <div class="result-row"><span class="label">Nama Penuh</span><span class="sep">:</span><span class="value"><?php echo htmlspecialchars($result['nama']); ?></span></div>
                                        <div class="result-row"><span class="label">Kad Pengenalan</span><span class="sep">:</span><span class="value"><?php echo htmlspecialchars($result['no_ic']); ?></span></div>
                                        <div class="result-row"><span class="label">Tarikh Meninggal</span><span class="sep">:</span><span class="value"><?php echo htmlspecialchars($result['tarikh_meninggal_formatted']); ?> | <?php echo htmlspecialchars($result['tarikh_hijri']); ?></span></div>
                                        <div class="result-row"><span class="label">Lokasi</span><span class="sep">:</span><span class="value"><a href="https://www.google.com/maps?q=<?php echo $result['latitude']; ?>,<?php echo $result['longitude']; ?>" target="_blank" class="location-link">Lat: <?php echo $result['latitude']; ?> Lng: <?php echo $result['longitude']; ?></a></span></div>
                                    </div>
                                    <div class="result-block">
                                        <div class="result-section-title">Waris Info</div>
                                        <div class="result-row"><span class="label">Nama Waris</span><span class="sep">:</span><span class="value"><?php echo htmlspecialchars($result['waris']); ?></span></div>
                                        <div class="result-row"><span class="label">Telefon Waris</span><span class="sep">:</span><span class="value"><?php echo htmlspecialchars($result['telefon_waris']); ?></span></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($searchResults)): ?>
                <div class="no-results">
                    <p>Tiada maklumat dijumpai untuk carian anda.</p>
                    <p>Sila cuba dengan kata kunci yang berbeza.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="info-section">
    <div class="container">
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon">📊</div>
                <h3>Maklumat Terkini</h3>
                <p>Dapatkan maklumat terkini tentang kematian dan ahli PPJUB dalam masa sebenar.</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon">🔍</div>
                <h3>Carian Mudah</h3>
                <p>Cari maklumat menggunakan nama atau nombor IC dengan cepat dan mudah.</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon">📱</div>
                <h3>Mobile Friendly</h3>
                <p>Website yang responsif dan mudah digunakan pada semua peranti.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
