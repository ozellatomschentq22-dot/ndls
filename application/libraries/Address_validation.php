<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address_validation {
    
    protected $CI;
    
    // Valid US state codes
    private $valid_states = array(
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
        'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
        'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
        'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
        'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY',
        'DC', 'AS', 'GU', 'MP', 'PR', 'VI'
    );
    
    // State names mapping
    private $state_names = array(
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
        'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
        'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
        'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
        'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
        'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
        'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
        'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'DC' => 'District of Columbia',
        'AS' => 'American Samoa', 'GU' => 'Guam', 'MP' => 'Northern Mariana Islands',
        'PR' => 'Puerto Rico', 'VI' => 'U.S. Virgin Islands'
    );
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Validate US postal code format
     */
    public function validate_postal_code($postal_code) {
        // Remove spaces and convert to uppercase
        $postal_code = strtoupper(trim($postal_code));
        
        // US ZIP code pattern: 5 digits or 5 digits + 4 digits
        $pattern = '/^\d{5}(-\d{4})?$/';
        
        if (!preg_match($pattern, $postal_code)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate US state code
     */
    public function validate_state($state) {
        // Convert to uppercase and trim
        $state = strtoupper(trim($state));
        
        // Check if it's a valid state code
        if (in_array($state, $this->valid_states)) {
            return true;
        }
        
        // Check if it's a valid state name
        if (in_array($state, $this->state_names)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get state code from state name
     */
    public function get_state_code($state_name) {
        $state_name = ucwords(strtolower(trim($state_name)));
        
        foreach ($this->state_names as $code => $name) {
            if (strcasecmp($name, $state_name) === 0) {
                return $code;
            }
        }
        
        return $state_name; // Return as is if not found
    }
    
    /**
     * Validate US phone number
     */
    public function validate_phone($phone) {
        if (empty($phone)) {
            return true; // Phone is optional
        }
        
        // Remove all non-digit characters
        $digits = preg_replace('/[^0-9]/', '', $phone);
        
        // US phone numbers should be 10 digits (or 11 with country code)
        if (strlen($digits) === 10) {
            return true;
        }
        
        // Allow 11 digits if it starts with 1 (country code)
        if (strlen($digits) === 11 && substr($digits, 0, 1) === '1') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Format phone number for display
     */
    public function format_phone($phone) {
        if (empty($phone)) {
            return '';
        }
        
        // Remove all non-digit characters
        $digits = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove country code if present
        if (strlen($digits) === 11 && substr($digits, 0, 1) === '1') {
            $digits = substr($digits, 1);
        }
        
        // Format as (XXX) XXX-XXXX
        if (strlen($digits) === 10) {
            return '(' . substr($digits, 0, 3) . ') ' . substr($digits, 3, 3) . '-' . substr($digits, 6);
        }
        
        return $phone; // Return original if can't format
    }
    
    /**
     * Validate complete US address
     */
    public function validate_address($address_data) {
        $errors = array();
        
        // Validate postal code
        if (!empty($address_data['postal_code']) && !$this->validate_postal_code($address_data['postal_code'])) {
            $errors[] = 'Invalid postal code format. Please use format: 12345 or 12345-6789';
        }
        
        // Validate state
        if (!empty($address_data['state']) && !$this->validate_state($address_data['state'])) {
            $errors[] = 'Invalid state. Please use a valid US state code (e.g., CA, NY) or full name (e.g., California, New York)';
        }
        
        // Validate phone
        if (!empty($address_data['phone']) && !$this->validate_phone($address_data['phone'])) {
            $errors[] = 'Invalid phone number format. Please use format: (555) 123-4567 or 5551234567';
        }
        
        return $errors;
    }
    
    /**
     * Get list of valid states for dropdown
     */
    public function get_states_list() {
        return $this->state_names;
    }
    
    /**
     * Normalize address data
     */
    public function normalize_address($address_data) {
        $normalized = $address_data;
        
        // Normalize postal code
        if (!empty($normalized['postal_code'])) {
            $normalized['postal_code'] = strtoupper(trim($normalized['postal_code']));
        }
        
        // Normalize state
        if (!empty($normalized['state'])) {
            $normalized['state'] = $this->get_state_code($normalized['state']);
        }
        
        // Normalize phone
        if (!empty($normalized['phone'])) {
            $normalized['phone'] = $this->format_phone($normalized['phone']);
        }
        
        return $normalized;
    }
} 