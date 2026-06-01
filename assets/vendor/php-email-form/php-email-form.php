<?php
/**
 * PHP Email Form Library
 * Version: 1.0
 */

class PHP_Email_Form {
    public $ajax = false;
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $smtp = array();
    public $messages = array();
    public $error = '';
    
    public function __construct() {
        $this->messages = array(
            'loading' => 'Loading',
            'sent' => 'Your message has been sent. Thank you!',
            'error' => 'Message could not be sent.'
        );
    }
    
    public function add_message($content, $label = '', $priority = 0) {
        $this->messages[] = array(
            'content' => $content,
            'label' => $label,
            'priority' => $priority
        );
    }
    
    public function send() {
        // Basic validation
        if (empty($this->to) || empty($this->from_email) || empty($this->subject)) {
            $this->error = 'Missing required email parameters.';
            return false;
        }
        
        // Validate email format
        if (!filter_var($this->from_email, FILTER_VALIDATE_EMAIL) || !filter_var($this->to, FILTER_VALIDATE_EMAIL)) {
            $this->error = 'Invalid email format.';
            return false;
        }
        
        // Build message body
        $message_body = '';
        foreach ($this->messages as $message) {
            if (is_array($message)) {
                $message_body .= $message['label'] . ': ' . $message['content'] . "\n";
            } else {
                $message_body .= $message . "\n";
            }
        }
        
        // Check if SMTP is configured
        if (!empty($this->smtp) && isset($this->smtp['host'])) {
            return $this->send_smtp($message_body);
        } else {
            return $this->send_mail($message_body);
        }
    }
    
    private function send_mail($message_body) {
        // Email headers
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/plain; charset=UTF-8';
        $headers[] = 'From: ' . $this->from_name . ' <' . $this->from_email . '>';
        $headers[] = 'Reply-To: ' . $this->from_email;
        $headers[] = 'X-Mailer: PHP/' . phpversion();
        
        // Send email using PHP mail() function
        if (mail($this->to, $this->subject, $message_body, implode("\r\n", $headers))) {
            return true;
        } else {
            $this->error = 'Message could not be sent using mail() function.';
            return false;
        }
    }
    
    private function send_smtp($message_body) {
        // Simple SMTP implementation
        $host = $this->smtp['host'];
        $port = isset($this->smtp['port']) ? $this->smtp['port'] : 587;
        $username = $this->smtp['username'];
        $password = $this->smtp['password'];
        
        // Create socket connection
        $socket = fsockopen($host, $port, $errno, $errstr, 30);
        if (!$socket) {
            $this->error = "Failed to connect to SMTP server: $errstr ($errno)";
            return false;
        }
        
        // Read server response
        $response = fgets($socket, 512);
        if (substr($response, 0, 3) != '220') {
            $this->error = "SMTP Error: $response";
            fclose($socket);
            return false;
        }
        
        // Send EHLO command
        fputs($socket, "EHLO localhost\r\n");
        $response = fgets($socket, 512);
        
        // Start TLS if port 587
        if ($port == 587) {
            fputs($socket, "STARTTLS\r\n");
            $response = fgets($socket, 512);
            if (substr($response, 0, 3) != '220') {
                $this->error = "STARTTLS failed: $response";
                fclose($socket);
                return false;
            }
            
            // Enable crypto
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->error = "Failed to enable TLS encryption";
                fclose($socket);
                return false;
            }
            
            // Send EHLO again after TLS
            fputs($socket, "EHLO localhost\r\n");
            $response = fgets($socket, 512);
        }
        
        // Authenticate
        fputs($socket, "AUTH LOGIN\r\n");
        $response = fgets($socket, 512);
        if (substr($response, 0, 3) != '334') {
            $this->error = "AUTH LOGIN failed: $response";
            fclose($socket);
            return false;
        }
        
        fputs($socket, base64_encode($username) . "\r\n");
        $response = fgets($socket, 512);
        if (substr($response, 0, 3) != '334') {
            $this->error = "Username authentication failed: $response";
            fclose($socket);
            return false;
        }
        
        fputs($socket, base64_encode($password) . "\r\n");
        $response = fgets($socket, 512);
        if (substr($response, 0, 3) != '235') {
            $this->error = "Password authentication failed: $response";
            fclose($socket);
            return false;
        }
        
        // Send email
        fputs($socket, "MAIL FROM: <{$this->from_email}>\r\n");
        $response = fgets($socket, 512);
        
        fputs($socket, "RCPT TO: <{$this->to}>\r\n");
        $response = fgets($socket, 512);
        
        fputs($socket, "DATA\r\n");
        $response = fgets($socket, 512);
        
        // Send headers and message
        $email_content = "From: {$this->from_name} <{$this->from_email}>\r\n";
        $email_content .= "To: {$this->to}\r\n";
        $email_content .= "Subject: {$this->subject}\r\n";
        $email_content .= "MIME-Version: 1.0\r\n";
        $email_content .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $email_content .= $message_body . "\r\n.\r\n";
        
        fputs($socket, $email_content);
        $response = fgets($socket, 512);
        
        // Quit
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        
        if (substr($response, 0, 3) == '250') {
            return true;
        } else {
            $this->error = "Email sending failed: $response";
            return false;
        }
    }
}
?>
