<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {

            
            // Check if request is FormData (has files) or JSON
            $isFormData = $request->hasFile('attachments') || $request->has('nama');
            
            if ($isFormData) {
                // Validate FormData request
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'emel' => 'required|email|max:255',
                    'telefon' => 'required|string|max:20',
                    'mesej' => 'required|string|max:5000',
                    'tarikh' => 'required|string',
                    'ip_address' => 'nullable|string',
                    'user_agent' => 'nullable|string',
                    'g-recaptcha-response' => 'required|string',
                    'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:15360', // 15MB max
                ]);
            } else {
                // Validate JSON request
                $validated = $request->validate([
                    'feedback.nama' => 'required|string|max:255',
                    'feedback.emel' => 'required|email|max:255',
                    'feedback.telefon' => 'required|string|max:20',
                    'feedback.mesej' => 'required|string|max:5000',
                    'feedback.tarikh' => 'required|string',
                    'feedback.ip_address' => 'nullable|string',
                    'feedback.user_agent' => 'nullable|string',
                    'g-recaptcha-response' => 'required|string',
                    'attachments' => 'nullable|array',
                    'attachments.*.name' => 'required_with:attachments|string',
                    'attachments.*.path' => 'required_with:attachments|string',
                    'attachments.*.size' => 'required_with:attachments|integer',
                    'attachments.*.type' => 'required_with:attachments|string',
                ]);
            }

            // Verify reCAPTCHA
            $recaptchaEnabled = \App\Models\Tetapan::isRecaptchaEnabled();

            
            // Convert string "1" to boolean true
            $recaptchaEnabled = ($recaptchaEnabled == "1" || $recaptchaEnabled === true);
            
            if (!$recaptchaEnabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA tidak diaktifkan dalam sistem.'
                ], 400);
            }

            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secretKey = \App\Models\Tetapan::getRecaptchaSecretKey();
            
            // Debug logging

            
            if (empty($secretKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA secret key tidak dikonfigurasi.'
                ], 400);
            }

            // Verify with Google reCAPTCHA
            $recaptchaVerification = $this->verifyRecaptcha($recaptchaResponse, $secretKey);

            
            if (!$recaptchaVerification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'reCAPTCHA verification gagal. Sila cuba lagi.'
                ], 400);
            }

            // Process data based on request type
            if ($isFormData) {
                // Process FormData (with files)
                $feedback = [
                    'nama' => $validated['nama'],
                    'emel' => $validated['emel'],
                    'telefon' => $validated['telefon'],
                    'mesej' => $validated['mesej'],
                    'tarikh' => $validated['tarikh'],
                    'ip_address' => $validated['ip_address'] ?? request()->ip(),
                    'user_agent' => $validated['user_agent'] ?? request()->userAgent(),
                ];
                
                // Process uploaded files
                $attachments = [];
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('feedback-attachments', 'public');
                        $attachments[] = [
                            'name' => $file->getClientOriginalName(),
                            'path' => storage_path('app/public/' . $path),
                            'size' => $file->getSize(),
                            'type' => $file->getMimeType()
                        ];
                    }
                }
            } else {
                // Process JSON data
                $feedback = $validated['feedback'];
                $attachments = $validated['attachments'] ?? [];
            }

            // Generate verification code
            $verificationCode = $this->generateVerificationCode();
            
            // Store feedback temporarily with verification code
            $tempFeedback = [
                'nama' => $feedback['nama'],
                'emel' => $feedback['emel'],
                'telefon' => $feedback['telefon'] ?? 'N/A',
                'mesej' => $feedback['mesej'],
                'tarikh' => $feedback['tarikh'],
                'ip_address' => $feedback['ip_address'],
                'user_agent' => $feedback['user_agent'],
                'attachments' => $attachments,
                'verification_code' => $verificationCode,
                'expires_at' => now()->addMinutes(15) // 15 minutes expiry
            ];

            // Store in session for verification
            $sessionKey = 'temp_feedback_' . $verificationCode;
            session([$sessionKey => $tempFeedback]);
            
            // Force session save
            session()->save();
            

            


            // Send verification code email
            $this->sendVerificationEmail($feedback, $verificationCode);

            return response()->json([
                'success' => true,
                'message' => 'Kod pengesahan telah dihantar ke e-mel anda. Sila masukkan kod tersebut untuk menghantar maklum balas.',
                'data' => [
                    'verification_required' => true,
                    'message' => 'Sila semak e-mel anda untuk kod pengesahan.',
                    'session_id' => session()->getId()
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Maklum balas berjaya diterima dan akan diproses.',
                'data' => [
                    'reference' => 'FB-' . date('Ymd') . '-' . uniqid(),
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Convert validation errors to readable format
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = $field . ': ' . implode(', ', $messages);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Data tidak sah: ' . implode('; ', $errorMessages),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error processing feedback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ralat dalaman. Sila cuba lagi.'
            ], 500);
        }
    }

    private function sendFeedbackEmail(array $feedback, array $attachments): void
    {
        try {
            // Get admin email from email_configurations table
            $emailConfig = \App\Models\EmailConfiguration::first();
            $adminEmail = $emailConfig ? $emailConfig->username : 'notify@ppjub.com.my';
            
            // Configure SMTP settings dynamically from database
            if ($emailConfig) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $emailConfig->smtp_host,
                    'mail.mailers.smtp.port' => $emailConfig->smtp_port,
                    'mail.mailers.smtp.username' => $emailConfig->username,
                    'mail.mailers.smtp.password' => $emailConfig->password,
                    'mail.mailers.smtp.encryption' => $emailConfig->encryption,
                    'mail.mailers.smtp.timeout' => $emailConfig->connection_timeout ?? 30,
                    'mail.from.address' => $emailConfig->username,
                    'mail.from.name' => $emailConfig->from_name ?? 'PPJUB Support'
                ]);
            }
            

            
            // Prepare email data
            $emailData = [
                'nama' => $feedback['nama'] ?? 'N/A',
                'emel' => $feedback['emel'] ?? 'N/A',
                'mesej' => $feedback['mesej'] ?? 'N/A',
                'tarikh' => $feedback['tarikh'] ?? 'N/A',
                'ip_address' => $feedback['ip_address'] ?? 'Unknown',
                'user_agent' => $feedback['user_agent'] ?? 'Unknown',
                'attachments' => $attachments
            ];

            // Send email using Laravel Mail (simple text email)
            Mail::raw($this->formatFeedbackEmail($feedback, $attachments), function ($message) use ($adminEmail, $feedback, $attachments) {
                $message->to($adminEmail)
                        ->subject('Maklum Balas Baru - PPJUB Website')
                        ->from('notify@ppjub.com.my', 'PPJUB Support')
                        ->replyTo($feedback['emel'], $feedback['nama']);

                // Attach files if any
                foreach ($attachments as $attachment) {
                    if (file_exists($attachment['path'])) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'],
                            'mime' => $attachment['type']
                        ]);
                    }
                }
            });



        } catch (\Exception $e) {
            Log::error('Failed to send feedback email', [
                'error' => $e->getMessage(),
                'feedback' => $feedback
            ]);
            
            // Don't throw exception to avoid breaking the API response
            // The feedback is still logged and processed
        }
    }

    private function formatFeedbackEmail(array $feedback, array $attachments): string
    {
        $emailContent = "Maklum Balas Baru dari PPJUB Website\n";
        $emailContent .= "=====================================\n\n";
        $emailContent .= "Nama: " . ($feedback['nama'] ?? 'N/A') . "\n";
        $emailContent .= "E-mel: " . ($feedback['emel'] ?? 'N/A') . "\n";
        $emailContent .= "Telefon: " . ($feedback['telefon'] ?? 'N/A') . "\n";
        $emailContent .= "Tarikh: " . ($feedback['tarikh'] ?? 'N/A') . "\n";
        $emailContent .= "IP Address: " . ($feedback['ip_address'] ?? 'Unknown') . "\n";
        $emailContent .= "User Agent: " . ($feedback['user_agent'] ?? 'Unknown') . "\n\n";
        $emailContent .= "Mesej:\n";
        $emailContent .= "------\n";
        $emailContent .= $feedback['mesej'] . "\n\n";
        
        if (!empty($attachments)) {
            $emailContent .= "Lampiran:\n";
            $emailContent .= "---------\n";
            foreach ($attachments as $attachment) {
                $emailContent .= "- " . $attachment['name'] . " (" . number_format($attachment['size'] / 1024 / 1024, 2) . " MB)\n";
            }
            $emailContent .= "\n";
        }
        
        $emailContent .= "---\n";
        $emailContent .= "Maklum balas ini dihantar secara automatik dari PPJUB Website.";
        
        return $emailContent;
    }

    private function sendConfirmationEmail(array $feedback): void
    {
        try {
            // Get email configuration from database
            $emailConfig = \App\Models\EmailConfiguration::first();
            
            // Configure SMTP settings dynamically from database
            if ($emailConfig) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $emailConfig->smtp_host,
                    'mail.mailers.smtp.port' => $emailConfig->smtp_port,
                    'mail.mailers.smtp.username' => $emailConfig->username,
                    'mail.mailers.smtp.password' => $emailConfig->password,
                    'mail.mailers.smtp.encryption' => $emailConfig->encryption,
                    'mail.mailers.smtp.timeout' => $emailConfig->connection_timeout ?? 30,
                    'mail.from.address' => $emailConfig->username,
                    'mail.from.name' => $emailConfig->from_name ?? 'PPJUB Support'
                ]);
            }
            
            $userEmail = $feedback['emel'] ?? 'N/A';
            $userName = $feedback['nama'] ?? 'N/A';
            
            $confirmationContent = "Terima kasih atas maklum balas anda!\n\n";
            $confirmationContent .= "Hai " . $userName . ",\n\n";
            $confirmationContent .= "Kami telah menerima maklum balas anda dan akan memprosesnya secepat mungkin.\n";
            $confirmationContent .= "Pasukan kami akan memberikan respons dalam tempoh 1-3 hari bekerja.\n\n";
            $confirmationContent .= "Maklumat maklum balas anda:\n";
            $confirmationContent .= "- Tarikh: " . ($feedback['tarikh'] ?? 'N/A') . "\n";
            $confirmationContent .= "- Telefon: " . ($feedback['telefon'] ?? 'N/A') . "\n";
            $confirmationContent .= "- Mesej: " . substr($feedback['mesej'] ?? '', 0, 100) . (strlen($feedback['mesej'] ?? '') > 100 ? '...' : '') . "\n\n";
            $confirmationContent .= "Jika anda mempunyai pertanyaan segera, sila hubungi kami di notify@ppjub.com.my\n\n";
            $confirmationContent .= "Terima kasih,\n";
            $confirmationContent .= "Pasukan PPJUB\n";
            $confirmationContent .= "Portal Pengurusan Jenazah dan Urusan Berkaitan\n";
            
            Mail::raw($confirmationContent, function ($message) use ($userEmail, $userName) {
                $message->to($userEmail, $userName)
                        ->subject('Maklum Balas Anda Telah Diterima - PPJUB')
                        ->from('notify@ppjub.com.my', 'PPJUB Support');
            });
            

            
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email', [
                'error' => $e->getMessage(),
                'user_email' => $feedback['emel']
            ]);
        }
    }

    /**
     * Verify feedback with verification code
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'verification_code' => 'required|string|size:6',
                'session_id' => 'required|string',
            ]);

            $verificationCode = $request->input('verification_code');
            $sessionId = $request->input('session_id');
            $sessionKey = 'temp_feedback_' . $verificationCode;
            

            
            // Force session ID to match the one from store
            if (session()->getId() !== $sessionId) {
                // Try to restore session data from database
                $sessionData = \Illuminate\Support\Facades\DB::table('sessions')
                    ->where('id', $sessionId)
                    ->first();
                    
                if ($sessionData) {
                    // Decode session data and restore
                    $sessionPayload = unserialize(base64_decode($sessionData->payload));
                    if (isset($sessionPayload[$sessionKey])) {
                        session([$sessionKey => $sessionPayload[$sessionKey]]);
                    }
                }
            }
            
            if (!session()->has($sessionKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kod pengesahan tidak sah atau telah tamat tempoh.'
                ], 400);
            }

            $tempFeedback = session($sessionKey);
            
            // Check if code has expired
            if (now()->isAfter($tempFeedback['expires_at'])) {
                session()->forget($sessionKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Kod pengesahan telah tamat tempoh. Sila hantar semula maklum balas.'
                ], 400);
            }

            // Clear session
            session()->forget($sessionKey);



            // Send email notification to admin
            $this->sendFeedbackEmail($tempFeedback, $tempFeedback['attachments'] ?? []);
            
            // Send confirmation email to user
            $this->sendConfirmationEmail($tempFeedback);

            return response()->json([
                'success' => true,
                'message' => 'Maklum balas berjaya disahkan dan dihantar!',
                'data' => [
                    'reference' => 'FB-' . date('Ymd') . '-' . uniqid(),
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error verifying feedback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ralat dalaman. Sila cuba lagi.'
            ], 500);
        }
    }

    /**
     * Verify reCAPTCHA with Google
     */
    private function verifyRecaptcha(string $response, string $secretKey): array
    {
        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => $secretKey,
                'response' => $response,
                'remoteip' => request()->ip()
            ];



            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            
            if ($result === false) {
                Log::error('reCAPTCHA API request failed', [
                    'error' => 'file_get_contents returned false'
                ]);
                return ['success' => false, 'message' => 'Gagal berhubung dengan Google reCAPTCHA'];
            }

            $resultData = json_decode($result, true);
            

            
            return [
                'success' => $resultData['success'] ?? false,
                'message' => $resultData['error-codes'] ?? 'Unknown error'
            ];

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => 'Error dalam reCAPTCHA verification'];
        }
    }

    /**
     * Generate 6-digit verification code
     */
    private function generateVerificationCode(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send verification code email
     */
    private function sendVerificationEmail(array $feedback, string $verificationCode): void
    {
        try {
            $emailConfig = \App\Models\EmailConfiguration::first();
            
            if ($emailConfig) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $emailConfig->smtp_host,
                    'mail.mailers.smtp.port' => $emailConfig->smtp_port,
                    'mail.mailers.smtp.username' => $emailConfig->username,
                    'mail.mailers.smtp.password' => $emailConfig->password,
                    'mail.mailers.smtp.encryption' => $emailConfig->encryption,
                    'mail.mailers.smtp.timeout' => $emailConfig->connection_timeout ?? 30,
                    'mail.from.address' => $emailConfig->username,
                    'mail.from.name' => $emailConfig->from_name ?? 'PPJUB Support'
                ]);
            }
            
            $userEmail = $feedback['emel'];
            $userName = $feedback['nama'];
            
            $verificationContent = "Kod Pengesahan Maklum Balas PPJUB\n";
            $verificationContent .= "==================================\n\n";
            $verificationContent .= "Hai " . $userName . ",\n\n";
            $verificationContent .= "Anda telah menghantar maklum balas kepada PPJUB.\n";
            $verificationContent .= "Untuk melengkapkan proses, sila masukkan kod pengesahan berikut:\n\n";
            $verificationContent .= "KOD PENGESAHAN: " . $verificationCode . "\n\n";
            $verificationContent .= "Maklumat maklum balas anda:\n";
            $verificationContent .= "- Nama: " . $feedback['nama'] . "\n";
            $verificationContent .= "- Telefon: " . ($feedback['telefon'] ?? 'N/A') . "\n";
            $verificationContent .= "- Mesej: " . substr($feedback['mesej'], 0, 50) . (strlen($feedback['mesej']) > 50 ? '...' : '') . "\n\n";
            $verificationContent .= "Kod ini akan tamat tempoh dalam 15 minit.\n";
            $verificationContent .= "Jika anda tidak menghantar maklum balas, sila abaikan e-mel ini.\n\n";
            $verificationContent .= "Terima kasih,\n";
            $verificationContent .= "Pasukan PPJUB\n";
            $verificationContent .= "Portal Pengurusan Jenazah dan Urusan Berkaitan\n";
            
            Mail::raw($verificationContent, function ($message) use ($userEmail, $userName) {
                $message->to($userEmail, $userName)
                        ->subject('Kod Pengesahan Maklum Balas - PPJUB')
                        ->from('notify@ppjub.com.my', 'PPJUB Support');
            });
            

            
        } catch (\Exception $e) {
            Log::error('Failed to send verification code email', [
                'error' => $e->getMessage(),
                'user_email' => $feedback['emel']
            ]);
        }
    }
}
