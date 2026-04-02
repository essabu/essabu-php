<?php

/**
 * Essabu PHP SDK - Loan Application Flow Example
 *
 * This example demonstrates the complete loan application lifecycle
 * using the Payment module: browse loan products, submit a KYC profile,
 * apply for a loan, track repayments, and manage collaterals.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/loan_application.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\Exception\EssabuException;

// 1. Initialize the client
$essabu = new Essabu(
    apiKey: getenv('ESSABU_API_KEY') ?: 'sk_test_your_api_key',
    tenantId: getenv('ESSABU_TENANT_ID') ?: 'tenant_your_id',
);

try {
    // 2. Browse available loan products
    $products = $essabu->payment->loanProducts->list(['page' => 0, 'size' => 10]);
    echo "Available loan products:\n";
    foreach ($products as $product) {
        echo "  - {$product['name']}: {$product['minAmount']}-{$product['maxAmount']} {$product['currency']}\n";
        echo "    Rate: {$product['interestRate']}%, Term: {$product['minTerm']}-{$product['maxTerm']} months\n";
    }

    $selectedProductId = $products[0]['id'] ?? 'prod_business_loan';

    // 3. Create/update KYC profile for the applicant
    $kycProfile = $essabu->payment->kycProfiles->create([
        'firstName'   => 'Marie',
        'lastName'    => 'Kabila',
        'dateOfBirth' => '1990-05-15',
        'nationalId'  => 'CD-123456789',
        'address'     => [
            'street'  => '45 Avenue de la Paix',
            'city'    => 'Lubumbashi',
            'country' => 'CD',
        ],
        'employment'  => [
            'employer'    => 'Tech Solutions SARL',
            'position'    => 'Senior Developer',
            'monthlyIncome' => 3500.00,
            'currency'    => 'USD',
        ],
    ]);
    echo "\nKYC profile created: {$kycProfile['id']}\n";
    echo "  Status: {$kycProfile['status']}\n";

    // 4. Upload KYC documents
    $kycDoc = $essabu->payment->kycDocuments->create([
        'profileId'   => $kycProfile['id'],
        'type'        => 'national_id',
        'description' => 'National ID Card - Front',
    ]);
    echo "\nKYC document registered: {$kycDoc['id']}\n";

    // 5. Submit the loan application
    $application = $essabu->payment->loanApplications->create([
        'loanProductId' => $selectedProductId,
        'kycProfileId'  => $kycProfile['id'],
        'amount'        => 10000.00,
        'currency'      => 'USD',
        'term'          => 12,    // 12 months
        'purpose'       => 'Business expansion - new equipment purchase',
    ]);
    echo "\nLoan application submitted!\n";
    echo "  Application ID: {$application['id']}\n";
    echo "  Status:         {$application['status']}\n";
    echo "  Amount:         {$application['amount']} {$application['currency']}\n";
    echo "  Monthly payment (est.): {$application['estimatedMonthlyPayment']}\n";

    // 6. Register collateral to strengthen the application
    $collateral = $essabu->payment->collaterals->create([
        'loanApplicationId' => $application['id'],
        'type'              => 'vehicle',
        'description'       => '2024 Toyota Hilux',
        'estimatedValue'    => 25000.00,
        'currency'          => 'USD',
    ]);
    echo "\nCollateral registered: {$collateral['id']}\n";
    echo "  Type:  {$collateral['type']}\n";
    echo "  Value: {$collateral['estimatedValue']} {$collateral['currency']}\n";

    // 7. Check application status (after review)
    $status = $essabu->payment->loanApplications->get($application['id']);
    echo "\nApplication status: {$status['status']}\n";

    // 8. Once approved, check repayment schedule
    if ($status['status'] === 'approved' || $status['status'] === 'disbursed') {
        $repayments = $essabu->payment->loanRepayments->list([
            'page' => 0,
            'size' => 12,
        ]);
        echo "\nRepayment schedule:\n";
        foreach ($repayments as $repayment) {
            $paidIcon = $repayment['status'] === 'paid' ? '[PAID]' : '[DUE] ';
            echo "  {$paidIcon} {$repayment['dueDate']}: {$repayment['amount']} {$repayment['currency']}\n";
        }
    }

    // 9. View financial reports
    $reports = $essabu->payment->reports->list(['page' => 0, 'size' => 5]);
    echo "\nAvailable reports:\n";
    foreach ($reports as $report) {
        echo "  - {$report['name']}: {$report['type']}\n";
    }

} catch (ValidationException $e) {
    echo "Validation error: {$e->getMessage()}\n";
    foreach ($e->fieldErrors as $field => $error) {
        echo "  Field '{$field}': {$error}\n";
    }
    exit(1);
} catch (EssabuException $e) {
    echo "API error ({$e->statusCode}): {$e->getMessage()}\n";
    exit(1);
}
