<?php

/**
 * Created by UniverseCode.
 */

namespace App\Helpers;

use App\{
    Models\EmailTemplate,
    Models\Setting
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\{PHPMailer, Exception, SMTP}; 

class EmailHelper
{

    public $mail;
    public $setting;

    public function __construct()
    {
        $this->setting = Setting::first();
        $this->mail = new PHPMailer(true);

        if ($this->setting->smtp_check == 1) {
            try {
                // Server settings
                $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
                $this->mail->isSMTP();
                $this->mail->Host = $this->setting->email_host;
                $this->mail->SMTPAuth = true;
                $this->mail->Username = $this->setting->email_user;
                $this->mail->Password = $this->setting->email_pass;
                
                // Encryption
                if ($this->setting->email_encryption == 'ssl') {
                    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $this->mail->Port = 465; // Force SSL port
                } else {
                    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $this->mail->Port = 587; // Force TLS port
                }
                
                // Override port if specifically set
                if (!empty($this->setting->email_port)) {
                    $this->mail->Port = $this->setting->email_port;
                }

                // Connection settings
                $this->mail->Timeout = 30;
                $this->mail->SMTPKeepAlive = true;
                $this->mail->CharSet = 'UTF-8';
                
                // Important for some servers
                $this->mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
                
                // Test connection
                if (!$this->mail->smtpConnect()) {
                    throw new Exception('Failed to connect to SMTP server');
                }
                
            } catch (Exception $e) {
                Log::error("SMTP Connection Error: " . $e->getMessage());
                throw new Exception("SMTP setup failed: " . $e->getMessage());
            }
        }
    }

    public function sendTemplateMail(array $emailData)
    {
        $template = EmailTemplate::whereType($emailData['type'])->first();
        try {
            // Set debug output level - comment out in production
            $this->mail->SMTPDebug = 2; // Uncomment for detailed debugging
            
            // Use proper timeout settings
            $this->mail->Timeout = 30; // Timeout in seconds
            $this->mail->SMTPKeepAlive = true; // Keep the connection open
            
            $email_body = preg_replace("/{user_name}/", $emailData['user_name'] ?? '', $template->body);
            $email_body = preg_replace("/{order_cost}/", $emailData['order_cost'] ?? '', $email_body);
            $email_body = preg_replace("/{transaction_number}/", $emailData['transaction_number'] ?? '', $email_body);
            $email_body = preg_replace("/{site_title}/", $this->setting->title, $email_body);

            $this->mail->setFrom($this->setting->email_from, $this->setting->email_from_name);
            $this->mail->addAddress($emailData['to']);
            $this->mail->isHTML(true);
            $this->mail->Subject = $template->subject;
            $this->mail->Body = $email_body;
            $this->mail->send();
            
            if ($this->setting->order_mail == 1) {
                $this->adminMail($emailData);
            }
        } catch (Exception $e) {
            // Log the error instead of hiding it
            \Log::error('Mail sending failed: ' . $e->getMessage());
            // Optionally rethrow or return false to indicate failure
            return false;
        }

        return true;
    }

    public function sendCustomMail(array $emailData)
    {
        try {

            $this->mail->setFrom($this->setting->email_from, $this->setting->email_from_name);
            $this->mail->addAddress($emailData['to']);
            $this->mail->isHTML(true);
            $this->mail->Subject = $emailData['subject'];
            $this->mail->Body = $emailData['body'];
            $this->mail->send();
        } catch (Exception $e) {
            dd($e->getMessage());
        }

        return true;
    }


    public static function getEmail()
    {
        $user = Auth::user();
        if (isset($user)) {
            $email = $user->email;
        } else {
            $email = Session::get('billing_address')['bill_email'];
        }
        return $email;
    }


    public function adminMail(array $emailData)
    {

        try {

            $template = EmailTemplate::whereType('New Order Admin')->first();
            $email_body = preg_replace("/{user_name}/", $emailData['user_name'], $template->body);
            $email_body = preg_replace("/{order_cost}/", $emailData['order_cost'], $email_body);
            $email_body = preg_replace("/{transaction_number}/", $emailData['transaction_number'], $email_body);
            $email_body = preg_replace("/{site_title}/", $this->setting->title, $email_body);
            $this->mail->setFrom($this->setting->email_from, $this->setting->email_from_name);
            $this->mail->clearAddresses();
            $this->mail->addAddress($this->setting->contact_email);
            $this->mail->isHTML(true);
            $this->mail->Subject = $template->subject;
            $this->mail->Body = $email_body;



            $this->mail->send();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
