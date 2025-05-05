<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiKey;
    protected string $senderName;

    public function __construct()
    {
        $this->apiKey = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name');
    }

    /**
     * Format the phone number to the +63 standard
     * 
     * @param string $phone The phone number to format
     * @return string The formatted phone number
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // If starts with 09, convert to +63 format
        if (substr($phone, 0, 2) === '09') {
            return '+63' . substr($phone, 1);
        }

        // If starts with just 9, add +63 prefix
        if (substr($phone, 0, 1) === '9') {
            return '+63' . $phone;
        }

        // If already in +63 format, return as is
        if (substr($phone, 0, 3) === '+63') {
            return $phone;
        }

        // Log error for invalid format
        Log::error('Invalid phone number format: ' . $phone);
        return $phone;
    }

    /**
     * Send SMS via Semaphore API
     *
     * @param string $number The recipient's phone number
     * @param string $message The message to send
     * @return array
     */
    public function sendSms(string $number, string $message): array
    {
        try {
            // Format the phone number to ensure it's in the correct format
            $formattedNumber = $this->formatPhoneNumber($number);
            
            $payload = [
                'apikey' => $this->apiKey,  // Include the API key
                'number' => $formattedNumber,
                'message' => $message,
                // 'sendername' => $this->senderName, // Optional sender name
            ];

            // Log the request payload
            Log::info('Semaphore SMS Request:', $payload);

            $response = Http::asForm() // Automatically sets 'Content-Type' to 'application/x-www-form-urlencoded'
                ->post('https://api.semaphore.co/api/v4/messages', $payload);

            $responseData = $response->json(); // Parse JSON response

            // Log the response
            Log::info('Semaphore SMS Response:', $responseData);

            return $responseData;
        } catch (\Exception $e) {
            Log::error('Semaphore SMS Failed: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}


 // $smsService = new SmsService();

    // // Hardcoded number for testing
    // $number = '+639366303145'; // Already formatted correctly for Semaphore
    // $message = $data['message']; // Message entered in the form
    
    // // Send the SMS
    // $response = $smsService->sendSms($number, $message);
    
    // // Log the response
    // \Log::info('SMS Response:', $response);
    
    // // Handle response
    // if (isset($response['error']) && $response['error']) {
    //     FilamentForm::notification('Failed to send SMS: ' . $response['message']);
    // } else {
    //     FilamentForm::notification('SMS sent successfully to ' . $number);
    // }