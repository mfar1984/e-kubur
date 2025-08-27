<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'PPJUB - Carian Si Mati'; ?></title>
    <meta name="description" content="Sistem Carian Si Mati PPJUB - Maklumat terkini tentang kematian dan ahli PPJUB">
    <meta name="keywords" content="PPJUB, kematian, carian, si mati, Malaysia">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="css/images/icon.png">
    
    <!-- Custom CSS for animations -->
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="topbar-content">
                <!-- Left Side - Date & Time -->
                <div class="topbar-left">
                    <div class="datetime-info">
                        <span class="date-text" id="current-date"></span>
                        <span class="time-separator">|</span>
                        <span class="time-text" id="current-time"></span>
                    </div>
                </div>
                
                <!-- Right Side - Social Media -->
                <div class="topbar-right">
                    <div class="social-links">
                        <a href="https://facebook.com/ppjub" target="_blank" class="social-link facebook" title="Facebook PPJUB">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="mailto:satu@ppjub.com.my" class="social-link email" title="Email PPJUB">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a href="https://wa.me/60135731599" target="_blank" class="social-link whatsapp" title="WhatsApp PPJUB">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="tel:+60135731599" class="social-link contact" title="Call PPJUB">
                            <i class="fas fa-phone"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="/">
                        <div class="logo-image">
                            <img src="css/images/logo.png" alt="PPJUB Logo" class="logo-img">
                        </div>
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="nav">
                    <div class="mobile-menu-header">
                        <h3>Menu</h3>
                        <button class="mobile-menu-close" id="mobileMenuClose">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <ul class="nav-menu">
                        <?php
                        $currentPage = $_SERVER['REQUEST_URI'];
                        $isHome = ($currentPage == '/' || $currentPage == '/index.php');
                        $isFeedback = (strpos($currentPage, '/feedback') !== false);
                        $isContact = (strpos($currentPage, '/contact') !== false);
                        ?>
                        <li><a href="/" class="nav-link <?php echo $isHome ? 'active' : ''; ?>"><i class="fas fa-home"></i> Utama</a></li>
                        <li><a href="/feedback" class="nav-link <?php echo $isFeedback ? 'active' : ''; ?>"><i class="fas fa-comment"></i> Borang Maklum Balas</a></li>
                        <li><a href="/contact" class="nav-link <?php echo $isContact ? 'active' : ''; ?>"><i class="fas fa-address-book"></i> Hubungi</a></li>
                    </ul>
                </nav>
                
                <!-- Mobile Menu Button -->
                <div class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" class="mobile-menu-overlay" aria-hidden="true"></div>
    
    
    
    <!-- Main Content Container -->
    <main class="main-content">
