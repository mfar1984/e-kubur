<?php
// Get current page for navigation active state
$currentPage = $_SERVER['REQUEST_URI'];
$isHome = ($currentPage == '/' || $currentPage == '/index.php');
$isFeedback = (strpos($currentPage, '/feedback') !== false);
$isContact = (strpos($currentPage, '/contact') !== false);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi - PPJUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .contact-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .contact-info {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .contact-info:hover {
            transform: translateY(-5px);
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 24px;
        }
        
        .team-member {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .role-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .contact-link {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .contact-link:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="contact-card text-center">
                    <h1 class="display-4 mb-3">
                        <i class="fas fa-address-book me-3"></i>
                        Hubungi Kami
                    </h1>
                    <p class="lead">Pertubuhan Pengurusan Jenazah Ummah Bintulu</p>
                </div>
            </div>
        </div>

        <!-- Organization Info -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h4>Alamat</h4>
                    <p class="mb-2">
                        <strong>Pertubuhan Pengurusan Jenazah Ummah</strong><br>
                        Lot 2891 Jalan Sibiyu<br>
                        97000 Bintulu, Sarawak
                    </p>
                    <div class="mt-3">
                        <a href="mailto:satu@ppjub.com.my" class="contact-link me-3">
                            <i class="fas fa-envelope me-2"></i>satu@ppjub.com.my
                        </a>
                    </div>
                    <div class="mt-2">
                        <a href="https://www.ppjub.com.my" target="_blank" class="contact-link">
                            <i class="fas fa-globe me-2"></i>www.ppjub.com.my
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="contact-info">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Waktu Operasi</h4>
                    <p class="mb-2">
                        <strong>24 Jam Setiap Hari</strong><br>
                        Khidmat Pengurusan Jenazah
                    </p>
                    <p class="mb-0">
                        <strong>Pejabat:</strong><br>
                        Isnin - Jumaat: 8:00 AM - 5:00 PM
                    </p>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">
                    <i class="fas fa-users me-3"></i>
                    Pasukan Pembangunan
                </h2>
            </div>
        </div>

        <div class="row">
            <!-- S/U PPJUB -->
            <div class="col-md-4">
                <div class="team-member">
                    <div class="role-badge">
                        <i class="fas fa-user-tie me-2"></i>S/U PPJUB
                    </div>
                    <h5 class="mb-2">En. Hadi</h5>
                    <p class="text-muted mb-2">Setiausaha Pertubuhan</p>
                    <div class="contact-details">
                        <a href="tel:0135731599" class="contact-link d-block mb-1">
                            <i class="fas fa-phone me-2"></i>013-573 1599
                        </a>
                        <a href="mailto:hadi@ppjub.com.my" class="contact-link d-block">
                            <i class="fas fa-envelope me-2"></i>hadi@ppjub.com.my
                        </a>
                    </div>
                </div>
            </div>

            <!-- Programmer -->
            <div class="col-md-4">
                <div class="team-member">
                    <div class="role-badge">
                        <i class="fas fa-code me-2"></i>Programmer
                    </div>
                    <h5 class="mb-2">Faizan Rahman</h5>
                    <p class="text-muted mb-2">Pembangun Sistem</p>
                    <div class="contact-details">
                        <a href="tel:0178591411" class="contact-link d-block mb-1">
                            <i class="fas fa-phone me-2"></i>017-859 1411
                        </a>
                        <a href="mailto:faizan@kflegfacyresources.com" class="contact-link d-block">
                            <i class="fas fa-envelope me-2"></i>faizan@kflegfacyresources.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Designer -->
            <div class="col-md-4">
                <div class="team-member">
                    <div class="role-badge">
                        <i class="fas fa-palette me-2"></i>Designer
                    </div>
                    <h5 class="mb-2">Hazwani</h5>
                    <p class="text-muted mb-2">Pereka Grafik</p>
                    <div class="contact-details">
                        <a href="tel:0102724770" class="contact-link d-block mb-1">
                            <i class="fas fa-phone me-2"></i>010-272 4770
                        </a>
                        <a href="mailto:7shadoww@gmail.com" class="contact-link d-block">
                            <i class="fas fa-envelope me-2"></i>7shadoww@gmail.com
                        </a>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
