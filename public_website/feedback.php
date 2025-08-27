<?php
session_start();
$pageTitle = 'Borang Maklum Balas - PPJUB';
include 'includes/header.php';

// API Configuration
$apiBaseUrl = 'http://localhost:8000/api/v1';
$sanctumToken = '13|qDO5hdLWsMotsbJJH3UcGTrEVImX7VSzgVYO4NiJe0d6b876'; // Token Sanctum
$debugMode = true; // Set to false in production

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Validate inputs
        $nama = trim($_POST['nama'] ?? '');
        $emel = trim($_POST['emel'] ?? '');
        $telefon = trim($_POST['telefon'] ?? '');
        $mesej = trim($_POST['mesej'] ?? '');
        
        if (empty($nama) || empty($emel) || empty($telefon) || empty($mesej)) {
            throw new Exception('Sila isi semua medan yang diperlukan.');
        }
        
        if (!filter_var($emel, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format e-mel tidak sah.');
        }
        
        // Validate phone number (basic validation)
        if (!preg_match('/^[\d\-\+\(\)\s]+$/', $telefon)) {
            throw new Exception('Format nombor telefon tidak sah.');
        }
        
        // Prepare feedback data
        $feedbackData = [
            'nama' => $nama,
            'emel' => $emel,
            'telefon' => $telefon,
            'mesej' => $mesej,
            'tarikh' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        
        // Handle file attachments
        $attachments = [];
        if (!empty($_FILES['attachments']['name'][0])) {
            // Upload to Laravel storage directory that can be accessed by both systems
            $uploadDir = '../storage/app/public/feedback/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['attachments']['name'][$key];
                    $fileSize = $_FILES['attachments']['size'][$key];
                    $fileType = $_FILES['attachments']['type'][$key];
                    
                    // Validate file size (15MB max)
                    if ($fileSize > 15 * 1024 * 1024) {
                        throw new Exception("Fail $fileName terlalu besar. Maksimum 15MB.");
                    }
                    
                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!in_array($fileType, $allowedTypes)) {
                        throw new Exception("Jenis fail $fileName tidak dibenarkan.");
                    }
                    
                    // Generate unique filename
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $uniqueName = uniqid() . '_' . time() . '.' . $fileExt;
                    $filePath = $uploadDir . $uniqueName;
                    
                    if (move_uploaded_file($tmp_name, $filePath)) {
                        $attachments[] = [
                            'name' => $fileName,
                            'path' => $filePath,
                            'size' => $fileSize,
                            'type' => $fileType
                        ];
                    }
                }
            }
        }
        
        // Send feedback via API
        $apiData = [
            'feedback' => $feedbackData,
            'attachments' => $attachments
        ];
        
        // Make API call to Laravel (no authentication required)
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $apiBaseUrl . '/feedback',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($apiData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $apiResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Debug information - Display to user for troubleshooting
        $debugInfo = [
            'URL' => $apiBaseUrl . '/feedback',
            'HTTP Code' => $httpCode,
            'Response' => $apiResponse,
            'CURL Error' => $curlError
        ];
        
        // Log debug info
        error_log("API Call Debug: " . json_encode($debugInfo));
        
        if ($httpCode === 200) {
            $result = json_decode($apiResponse, true);
            if ($result && isset($result['success']) && $result['success']) {
                $response['success'] = true;
                $response['message'] = 'Maklum balas berjaya dihantar! Terima kasih atas cadangan anda.';
                
                // Clear form data on success
                $_POST = [];
            } else {
                throw new Exception($result['message'] ?? 'Ralat semasa menghantar maklum balas.');
            }
        } else {
            // Show debug information for troubleshooting
            $errorMessage = 'Ralat sambungan ke server. Sila cuba lagi.';
            if ($curlError) {
                $errorMessage .= ' CURL Error: ' . $curlError;
            }
            if ($httpCode !== 0) {
                $errorMessage .= ' HTTP Code: ' . $httpCode;
            }
            if ($apiResponse) {
                $errorMessage .= ' Response: ' . $apiResponse;
            }
            throw new Exception($errorMessage);
        }
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}
?>

<style>
/* Verification Form Styles */
.verification-section {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    margin-top: 2rem;
}

.verification-form .input-group {
    margin-bottom: 1.5rem;
}

.verification-form .input-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

        .verification-form .input-button-row {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Mobile responsive for verification form */
        @media (max-width: 768px) {
            .verification-form .input-button-row {
                flex-direction: column !important;
                gap: 0.75rem !important;
                align-items: stretch !important;
            }
            
            .verification-form .input-button-row input {
                width: 100% !important;
                margin-bottom: 0.5rem !important;
                flex: none !important;
                min-width: 100% !important;
            }
            
            .verification-form .input-button-row button {
                width: 100% !important;
                flex: none !important;
                min-width: 100% !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
                min-height: 44px !important;
            }
            
            /* Force button visibility */
            .verification-form .search-btn {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 100% !important;
                height: auto !important;
                min-height: 44px !important;
                position: static !important;
                overflow: visible !important;
                clip: auto !important;
            }
        }

.verification-form .input-group input {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.verification-form .input-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.verification-form .search-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
}

.verification-form .search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.verification-form .search-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Form Separator Line */
.form-separator {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
    margin: 1.5rem 0;
    border-radius: 1px;
}

/* Attachment Section Styling */
.input-group:has(#attachments) {
    margin-bottom: 1rem;
}

/* Submit Button Section */
.submit-row {
    border-top: 1px solid #f1f5f9;
    padding-top: 1rem;
    margin-top: 0.5rem;
}
</style>

<!-- Feedback Section (reuse search card styles, wider) -->
<section class="search-section" style="background: url('css/images/bg1.jpg') center top / cover no-repeat;">
    <div class="container">
        <div class="search-container" style="max-width: 960px;">
            <div class="search-header">
                <div class="search-icon-large">✉️</div>
                <h2>Maklum Balas</h2>
                <p class="search-helper">Berkongsi cadangan, pertanyaan atau laporan isu anda di sini</p>
            </div>

            <?php if (isset($response)): ?>
                <div class="alert <?= $response['success'] ? 'alert-success' : 'alert-error' ?>" style="margin-bottom: 1rem; padding: 1rem; border-radius: 8px; <?= $response['success'] ? 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' ?>">
                    <?= htmlspecialchars($response['message']) ?>
                </div>
                
                <?php if ($debugMode && !$response['success'] && isset($debugInfo)): ?>
                    <div class="alert alert-warning" style="margin-bottom: 1rem; padding: 1rem; border-radius: 8px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">
                        <strong>Debug Information:</strong><br>
                        <small>
                            URL: <?= htmlspecialchars($debugInfo['URL']) ?><br>
                            HTTP Code: <?= htmlspecialchars($debugInfo['HTTP Code']) ?><br>
                            Response: <?= htmlspecialchars($debugInfo['Response']) ?><br>
                            CURL Error: <?= htmlspecialchars($debugInfo['CURL Error']) ?>
                        </small>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form method="post" action="#" class="search-form" enctype="multipart/form-data" id="feedbackForm">
                <div class="search-inputs" style="flex-direction: column; align-items: stretch; gap: .75rem;">
                    <div class="input-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" placeholder="Nama penuh" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="emel">E-mel</label>
                        <input type="email" id="emel" name="emel" placeholder="alamat@email.com" value="<?= htmlspecialchars($_POST['emel'] ?? '') ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="telefon">Nombor Telefon</label>
                        <input type="tel" id="telefon" name="telefon" placeholder="012-3456789" value="<?= htmlspecialchars($_POST['telefon'] ?? '') ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="mesej">Mesej</label>
                        <textarea id="mesej" name="mesej" rows="5" placeholder="Tulis maklum balas anda" style="width:100%; padding:.75rem 1rem; border:2px solid #e9ecef; border-radius:8px; font-size:13px;" required><?= htmlspecialchars($_POST['mesej'] ?? '') ?></textarea>
                    </div>
                    <div class="input-group">
                        <label for="attachments">Lampiran (jpeg, jpg, png, pdf) — maksimum 15MB setiap fail</label>
                        <input type="file" id="attachments" name="attachments[]" accept="image/jpeg,image/jpg,image/png,application/pdf" multiple>
                        <small class="help-text">Anda boleh pilih lebih daripada satu fail.</small>
                        <ul id="attachment-list" class="attachment-list" style="margin:.15rem 0 0 0; padding-left:1rem;"></ul>
                    </div>
                    
                    <!-- reCAPTCHA Container -->
                    <div id="recaptcha-container" style="margin: 0.5rem 0; min-height: 10px;"></div>
                    
                    <div class="submit-row" style="display:flex; justify-content:flex-end; border-top: 1px solid #e2e8f0; padding-top: 0.75rem; margin-top: 0.25rem;">
                        <button type="submit" class="search-btn" style="padding:.6rem 1.25rem;" id="submitBtn">
                            <span class="btn-text">Hantar</span>
                            <span class="btn-loading" style="display:none;">Menghantar...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Include reCAPTCHA Helper -->
<script src="js/recaptcha-helper.js"></script>

<script>
// Initialize reCAPTCHA
let recaptchaHelper;
document.addEventListener('DOMContentLoaded', async function() {
    try {
        recaptchaHelper = new RecaptchaHelper();
        await recaptchaHelper.init();

    } catch (error) {

    }
});

// File attachment preview
document.getElementById('attachments').addEventListener('change', function(e) {
    const list = document.getElementById('attachment-list');
    list.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const li = document.createElement('li');
        li.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        list.appendChild(li);
    });
});

// Form submission with reCAPTCHA verification
document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    try {
        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline';
        
        // Execute reCAPTCHA if enabled
        if (recaptchaHelper && recaptchaHelper.isReady()) {
    
            const token = await recaptchaHelper.execute();

            
            if (token) {
                // Check if files are selected
                const fileInput = this.querySelector('[name="attachments[]"]');
                const hasFiles = fileInput && fileInput.files.length > 0;
                
                let response;
                
                if (hasFiles) {
                    // Use FormData for file uploads
                    const formData = new FormData(this);
                    formData.append('g-recaptcha-response', token);
                    formData.append('tarikh', new Date().toISOString().split('T')[0]);
                    
                    response = await fetch('http://localhost:8000/api/v1/feedback', {
                        method: 'POST',
                        body: formData
                    });
                } else {
                    // Use JSON for text-only submissions
                    response = await fetch('http://localhost:8000/api/v1/feedback', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            feedback: {
                                nama: this.querySelector('[name="nama"]').value,
                                emel: this.querySelector('[name="emel"]').value,
                                telefon: this.querySelector('[name="telefon"]').value,
                                mesej: this.querySelector('[name="mesej"]').value,
                                tarikh: new Date().toISOString().split('T')[0],
                                ip_address: '127.0.0.1',
                                user_agent: navigator.userAgent
                            },
                            'g-recaptcha-response': token
                        })
                    });
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Show verification modal/page with session ID
                    showVerificationModal(result.data.reference, result.data.session_id);
                } else {
                    throw new Error(result.message || 'Verification failed');
                }
                
            } else {
                throw new Error('reCAPTCHA token not received');
            }
            
        } else {

            // Submit form normally if reCAPTCHA not ready
            this.submit();
        }
        
    } catch (error) {
        
        alert('reCAPTCHA verification failed. Sila cuba lagi.');
        
        // Reset button state
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
        
        // Reset reCAPTCHA
        if (recaptchaHelper) {
            recaptchaHelper.reset();
        }
    }
});

// Show verification modal
function showVerificationModal(reference, sessionId) {
    // Hide form
    document.getElementById('feedbackForm').style.display = 'none';
    
    // Show verification section
    const verificationSection = document.createElement('div');
    verificationSection.className = 'verification-section';
    verificationSection.innerHTML = `
        <div class="search-header">
            <div class="search-icon-large">🔐</div>
            <h2>Verification Code</h2>
            <p class="search-helper">Sila masukkan kod verification yang telah dihantar ke email anda</p>
        </div>
        
        <div class="verification-form">
            <div class="input-group">
                <label for="verificationCode">Kod Verification (6 digit)</label>
                <div class="input-button-row">
                    <input type="text" id="verificationCode" name="verificationCode" placeholder="123456" maxlength="6" required>
                    <button type="button" class="search-btn" onclick="verifyCode('${reference}', '${sessionId}')">
                        Sahkan
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.querySelector('.search-container').appendChild(verificationSection);
}

// Verify code function
async function verifyCode(reference, sessionId) {
    const code = document.getElementById('verificationCode').value;
    const verifyBtn = document.querySelector('.verification-form .search-btn');
    
    if (!code || code.length !== 6) {
        alert('Sila masukkan kod verification 6 digit');
        return;
    }
    
    // Show loading state
    const originalText = verifyBtn.innerHTML;
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<span style="display:inline-block; animation:spin 1s linear infinite;">⏳</span> Verifikasi...';
    
    try {
        const response = await fetch('http://localhost:8000/api/v1/feedback/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Session-ID': sessionId
            },
            body: JSON.stringify({
                verification_code: code,
                session_id: sessionId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Feedback berjaya dihantar! Terima kasih.');
            window.location.reload();
        } else {
            alert(result.message || 'Verification failed');
        }
        
    } catch (error) {
        
        alert('Verification failed. Sila cuba lagi.');
    } finally {
        // Reset button state
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = originalText;
    }
}
</script>

<?php include 'includes/footer.php'; ?>


