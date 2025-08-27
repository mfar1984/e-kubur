<?php

namespace App\Http\Controllers;

use App\Models\EmailConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;

class EmailConfigurationController extends Controller
{
    public function index()
    {
        $emailConfig = EmailConfiguration::first();
        if (!$emailConfig) {
            // Create default configuration if none exists
            $emailConfig = EmailConfiguration::create([
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 587,
                'username' => 'noreply@ekubur.com',
                'password' => '',
                'encryption' => 'TLS',
                'authentication' => 'Required',
                'from_name' => 'E-Kubur System',
                'reply_to' => 'support@ekubur.com',
                'connection_timeout' => 30,
                'max_retries' => 3,
                'is_active' => true
            ]);
        }
        
        return response()->json($emailConfig);
    }

    public function update(Request $request, EmailConfiguration $emailConfiguration)
    {
        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'username' => 'required|email|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'required|in:TLS,SSL,None',
            'authentication' => 'required|in:Required,None',
            'from_name' => 'required|string|max:255',
            'reply_to' => 'required|email|max:255',
            'connection_timeout' => 'required|integer|min:1|max:300',
            'max_retries' => 'required|integer|min:0|max:10',
        ], [
            'smtp_host.required' => 'SMTP Host diperlukan',
            'smtp_port.required' => 'SMTP Port diperlukan',
            'smtp_port.integer' => 'SMTP Port mesti nombor',
            'smtp_port.min' => 'SMTP Port mesti lebih dari 0',
            'smtp_port.max' => 'SMTP Port mesti kurang dari 65536',
            'username.required' => 'Username/Email diperlukan',
            'username.email' => 'Username mesti format email yang sah',
            'encryption.required' => 'Encryption diperlukan',
            'encryption.in' => 'Encryption mesti TLS, SSL atau None',
            'authentication.required' => 'Authentication diperlukan',
            'authentication.in' => 'Authentication mesti Required atau None',
            'from_name.required' => 'From Name diperlukan',
            'reply_to.required' => 'Reply To diperlukan',
            'reply_to.email' => 'Reply To mesti format email yang sah',
            'connection_timeout.required' => 'Connection Timeout diperlukan',
            'connection_timeout.integer' => 'Connection Timeout mesti nombor',
            'connection_timeout.min' => 'Connection Timeout mesti lebih dari 0',
            'connection_timeout.max' => 'Connection Timeout mesti kurang dari 301',
            'max_retries.required' => 'Max Retries diperlukan',
            'max_retries.integer' => 'Max Retries mesti nombor',
            'max_retries.min' => 'Max Retries mesti 0 atau lebih',
            'max_retries.max' => 'Max Retries mesti 10 atau kurang',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat validasi',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Don't update password if it's empty (keep existing)
            if (empty($data['password'])) {
                unset($data['password']);
            }
            
            $emailConfiguration->update($data);

            activity('integrations')
                ->event('email_updated')
                ->causedBy(Auth::user())
                ->performedOn($emailConfiguration)
                ->withProperties(array_merge(
                    collect($data)->only(['smtp_host','smtp_port','username','encryption','authentication','from_name','reply_to','connection_timeout','max_retries'])->toArray(),
                    [
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                ))
                ->log('Konfigurasi Email dikemaskini');
            
            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi email berjaya dikemas kini',
                'data' => $emailConfiguration
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa menyimpan konfigurasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testEmail(Request $request)
    {
        try {
            $emailConfig = EmailConfiguration::first();
            if (!$emailConfig) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi email tidak dijumpai'
                ], 404);
            }

            // Validate recipient email
            $request->validate([
                'recipient_email' => 'required|email'
            ], [
                'recipient_email.required' => 'Email penerima diperlukan',
                'recipient_email.email' => 'Format email tidak sah'
            ]);

            $recipientEmail = $request->recipient_email;

            // Test email configuration with recipient
            $testResult = $this->testSmtpConnection($emailConfig, $recipientEmail);
            
            // Update last test info
            $emailConfig->update([
                'last_test' => now(),
                'last_test_status' => $testResult['success'] ? 'success' : 'failed',
                'last_test_message' => $testResult['message']
            ]);

            activity('integrations')
                ->event('email_tested')
                ->causedBy(Auth::user())
                ->performedOn($emailConfig)
                ->withProperties([
                    'recipient' => $recipientEmail,
                    'result' => $testResult['success'] ? 'success' : 'failed',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Ujian SMTP dijalankan');

            return response()->json($testResult);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa menguji email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function smtpHealth(Request $request)
    {
        try {
            $emailConfig = EmailConfiguration::first();
            if (!$emailConfig) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi email tidak dijumpai'
                ], 404);
            }

            $host = $emailConfig->smtp_host;
            $port = (int) $emailConfig->smtp_port;

            $start = microtime(true);
            $errno = 0; $errstr = '';
            $timeout = max(1, (int) $emailConfig->connection_timeout);
            $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
            $ms = (int) ((microtime(true) - $start) * 1000);

            if ($fp) {
                fclose($fp);
                return response()->json([
                    'success' => true,
                    'message' => 'Sambungan ke SMTP berjaya.',
                    'latency_ms' => $ms,
                    'details' => [ 'host' => $host, 'port' => $port ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menyambung ke SMTP: ' . $errstr,
                'details' => [ 'host' => $host, 'port' => $port, 'errno' => $errno ]
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa menguji SMTP: ' . $e->getMessage()
            ], 500);
        }
    }

    private function testSmtpConnection($emailConfig, $recipientEmail)
    {
        try {
            // Determine encryption based on port
            $encryption = $emailConfig->encryption;
            if ($emailConfig->smtp_port == 465) {
                $encryption = 'ssl';
            } elseif ($emailConfig->smtp_port == 587) {
                $encryption = 'tls';
            }
            
            // Set mail configuration temporarily
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $emailConfig->smtp_host,
                'mail.mailers.smtp.port' => $emailConfig->smtp_port,
                'mail.mailers.smtp.username' => $emailConfig->username,
                'mail.mailers.smtp.password' => $emailConfig->password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout' => $emailConfig->connection_timeout,
                'mail.mailers.smtp.auth_mode' => null,
                'mail.mailers.smtp.verify_peer' => false,
                'mail.mailers.smtp.verify_peer_name' => false,
                'mail.from.address' => $emailConfig->username,
                'mail.from.name' => $emailConfig->from_name,
            ]);

            // Test connection by trying to send a test email
            Mail::raw('Test email dari E-Kubur System

Ini adalah email test untuk mengesahkan konfigurasi SMTP berfungsi dengan baik.

Maklumat Konfigurasi:
- SMTP Host: ' . $emailConfig->smtp_host . '
- SMTP Port: ' . $emailConfig->smtp_port . '
- Username: ' . $emailConfig->username . '
- Encryption: ' . $emailConfig->encryption . '
- From Name: ' . $emailConfig->from_name . '

Masa Test: ' . now()->format('d/m/Y H:i:s') . '

Jika anda menerima email ini, bermakna konfigurasi SMTP berfungsi dengan sempurna!', function ($message) use ($emailConfig, $recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('Test Email - E-Kubur System SMTP Configuration')
                        ->from($emailConfig->username, $emailConfig->from_name);
            });

            return [
                'success' => true,
                'message' => 'Sambungan SMTP berjaya! Email test berjaya dihantar ke ' . $recipientEmail . '. Sila check inbox dan spam folder.',
                'details' => [
                    'host' => $emailConfig->smtp_host,
                    'port' => $emailConfig->smtp_port,
                    'username' => $emailConfig->username,
                    'encryption' => $emailConfig->encryption,
                    'recipient' => $recipientEmail
                ]
            ];

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('SMTP Test Error: ' . $e->getMessage(), [
                'host' => $emailConfig->smtp_host,
                'port' => $emailConfig->smtp_port,
                'username' => $emailConfig->username,
                'encryption' => $emailConfig->encryption,
                'recipient' => $recipientEmail,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menyambung ke SMTP: ' . $e->getMessage(),
                'details' => [
                    'host' => $emailConfig->smtp_host,
                    'port' => $emailConfig->smtp_port,
                    'username' => $emailConfig->username,
                    'encryption' => $emailConfig->encryption,
                    'recipient' => $recipientEmail,
                    'error' => $e->getMessage()
                ]
            ];
        }
    }

    // API version (Sanctum) - expects JSON { recipient_email }
    public function testEmailApi(Request $request)
    {
        // Optional: check abilities via token
        $token = $request->user()?->currentAccessToken();
        if ($token && !(in_array('admin:all', $token->abilities ?? []) || in_array('write:integrations', $token->abilities ?? []))) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak mempunyai keizinan mencukupi.'
            ], 403);
        }

        $request->validate([
            'recipient_email' => 'required|email'
        ]);

        $emailConfig = EmailConfiguration::first();
        if (!$emailConfig) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi email tidak dijumpai'
            ], 404);
        }

        $result = $this->testSmtpConnection($emailConfig, $request->recipient_email);

        // Update last test info
        $emailConfig->update([
            'last_test' => now(),
            'last_test_status' => $result['success'] ? 'success' : 'failed',
            'last_test_message' => $result['message']
        ]);

        activity('integrations')
            ->event('email_tested_api')
            ->causedBy(Auth::user())
            ->performedOn($emailConfig)
            ->withProperties([
                'recipient' => $request->recipient_email,
                'result' => $result['success'] ? 'success' : 'failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Ujian SMTP melalui API');

        return response()->json($result);
    }
}
