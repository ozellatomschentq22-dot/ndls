<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Payment Instructions Configuration
|--------------------------------------------------------------------------
| This file contains payment instructions for different payment methods
| used in the recharge system.
|
*/

$config['payment_instructions'] = array(
    'cashapp' => array(
        'title' => 'CashApp Payment Instructions',
        'icon' => 'fas fa-dollar-sign',
        'instructions' => array(
            'Send payment to: <strong>$JIPIPayments</strong>',
            'Use the exact amount: <strong>$<span id="cashapp-amount">0.00</span></strong>',
            'In the payment note, include: "Recharge Request"',
            'After sending payment, you will receive a CashApp transaction ID.',
            'Enter that transaction ID in the form below.'
        ),
        'additional_info' => 'CashApp payments are typically instant and secure.'
    ),
    
    'venmo' => array(
        'title' => 'Venmo Payment Instructions',
        'icon' => 'fas fa-mobile-alt',
        'instructions' => array(
            'Send payment to: <strong>@JIPI-Payments</strong>',
            'Use the exact amount: <strong>$<span id="venmo-amount">0.00</span></strong>',
            'In the payment note, include: "Recharge Request"',
            'After sending payment, you will receive a Venmo transaction ID.',
            'Enter that transaction ID in the form below.'
        ),
        'additional_info' => 'Venmo payments are typically instant and secure.'
    ),
    
    'zelle' => array(
        'title' => 'Zelle Payment Instructions',
        'icon' => 'fas fa-university',
        'instructions' => array(
            'Send payment to: <strong>payments@jipi.com</strong>',
            'Use the exact amount: <strong>$<span id="zelle-amount">0.00</span></strong>',
            'In the memo field, include: "Recharge Request"',
            'After sending payment, you will receive a Zelle confirmation number.',
            'Enter that confirmation number in the form below.'
        ),
        'additional_info' => 'Zelle payments are typically instant between banks.'
    ),
    
    'bitcoin' => array(
        'title' => 'Bitcoin Payment Instructions',
        'icon' => 'fab fa-bitcoin',
        'instructions' => array(
            'Send Bitcoin to: <strong>bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh</strong>',
            'Use the exact amount: <strong>$<span id="bitcoin-amount">0.00</span></strong>',
            'Network: <strong>Bitcoin (BTC)</strong>',
            'After sending payment, you will receive a transaction hash.',
            'Enter that transaction hash in the form below.'
        ),
        'additional_info' => 'Bitcoin transactions typically take 10-30 minutes to confirm.'
    ),
    
    'monero' => array(
        'title' => 'Monero Payment Instructions',
        'icon' => 'fas fa-coins',
        'instructions' => array(
            'Send Monero to: <strong>8A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6</strong>',
            'Use the exact amount: <strong>$<span id="monero-amount">0.00</span></strong>',
            'Network: <strong>Monero (XMR)</strong>',
            'After sending payment, you will receive a transaction hash.',
            'Enter that transaction hash in the form below.'
        ),
        'additional_info' => 'Monero transactions are private and typically confirm within 2 minutes.'
    ),
    
    'usdt_trc20' => array(
        'title' => 'USDT (TRC20) Payment Instructions',
        'icon' => 'fas fa-coins',
        'instructions' => array(
            'Send USDT to: <strong>TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t</strong>',
            'Use the exact amount: <strong>$<span id="usdt_trc20-amount">0.00</span></strong>',
            'Network: <strong>TRC20 (Tron)</strong>',
            'After sending payment, you will receive a transaction hash.',
            'Enter that transaction hash in the form below.'
        ),
        'additional_info' => 'USDT TRC20 transactions are fast and have low fees.'
    ),
    
    'usdt_erc20' => array(
        'title' => 'USDT (ERC20) Payment Instructions',
        'icon' => 'fas fa-coins',
        'instructions' => array(
            'Send USDT to: <strong>0x742d35Cc6634C0532925a3b8D4C9db96C4b4d8b6</strong>',
            'Use the exact amount: <strong>$<span id="usdt_erc20-amount">0.00</span></strong>',
            'Network: <strong>ERC20 (Ethereum)</strong>',
            'After sending payment, you will receive a transaction hash.',
            'Enter that transaction hash in the form below.'
        ),
        'additional_info' => 'USDT ERC20 transactions may have higher gas fees but are widely supported.'
    )
); 