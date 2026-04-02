<?php

/**
 * Essabu PHP SDK - CRM Pipeline Example
 *
 * This example demonstrates a complete CRM workflow using
 * the Trade module: create a contact, log activities, create
 * an opportunity, and advance it through the sales pipeline.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/crm_pipeline.php
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
    // 2. Create a new contact (lead)
    $contact = $essabu->trade->contacts->create([
        'firstName' => 'Pierre',
        'lastName'  => 'Nkongolo',
        'email'     => 'pierre.nkongolo@globalcorp.cd',
        'phone'     => '+243 991 234 567',
        'company'   => 'Global Corp Ltd',
        'title'     => 'Chief Technology Officer',
        'source'    => 'trade_show',
        'tags'      => ['enterprise', 'tech', 'high-value'],
    ]);

    echo "Contact created!\n";
    echo "  ID:      {$contact['id']}\n";
    echo "  Name:    {$contact['firstName']} {$contact['lastName']}\n";
    echo "  Company: {$contact['company']}\n";

    // 3. Log a first activity (initial call)
    $activity = $essabu->trade->activities->create([
        'contactId'   => $contact['id'],
        'type'        => 'call',
        'subject'     => 'Initial discovery call',
        'description' => 'Discussed their needs for an ERP solution. '
            . 'They are looking to digitize their accounting and HR processes. '
            . 'Budget: $50,000-100,000. Decision expected Q2 2026.',
        'date'        => '2026-03-26T10:00:00Z',
        'duration'    => 30,  // minutes
        'outcome'     => 'positive',
    ]);
    echo "\nActivity logged: {$activity['id']}\n";
    echo "  Type:    {$activity['type']}\n";
    echo "  Subject: {$activity['subject']}\n";

    // 4. Create an opportunity from this contact
    $opportunity = $essabu->trade->opportunities->create([
        'contactId'    => $contact['id'],
        'name'         => 'Global Corp - ERP Implementation',
        'stage'        => 'qualification',
        'value'        => 75000.00,
        'currency'     => 'USD',
        'probability'  => 30,
        'expectedCloseDate' => '2026-06-30',
        'description'  => 'Full ERP implementation: Accounting + HR + Asset management modules',
    ]);

    echo "\nOpportunity created!\n";
    echo "  ID:          {$opportunity['id']}\n";
    echo "  Name:        {$opportunity['name']}\n";
    echo "  Stage:       {$opportunity['stage']}\n";
    echo "  Value:       {$opportunity['value']} {$opportunity['currency']}\n";
    echo "  Probability: {$opportunity['probability']}%\n";

    // 5. Log a follow-up meeting
    $meeting = $essabu->trade->activities->create([
        'contactId'      => $contact['id'],
        'opportunityId'  => $opportunity['id'],
        'type'           => 'meeting',
        'subject'        => 'Product demo - Accounting module',
        'description'    => 'Demonstrated the accounting module with live data. '
            . 'CTO was impressed with the e-invoice integration.',
        'date'           => '2026-04-02T14:00:00Z',
        'duration'       => 60,
        'outcome'        => 'positive',
    ]);
    echo "\nMeeting logged: {$meeting['subject']}\n";

    // 6. Advance the opportunity to proposal stage
    $advanced = $essabu->trade->opportunities->update($opportunity['id'], [
        'stage'       => 'proposal',
        'probability' => 60,
        'notes'       => 'Demo went well. Sending proposal this week.',
    ]);
    echo "\nOpportunity advanced!\n";
    echo "  Stage:       {$advanced['stage']}\n";
    echo "  Probability: {$advanced['probability']}%\n";

    // 7. Create a contract draft
    $contract = $essabu->trade->contracts->create([
        'contactId'      => $contact['id'],
        'opportunityId'  => $opportunity['id'],
        'title'          => 'ERP Implementation Agreement - Global Corp',
        'value'          => 75000.00,
        'currency'       => 'USD',
        'startDate'      => '2026-07-01',
        'endDate'        => '2027-06-30',
        'terms'          => '50% upfront, 25% at mid-point, 25% on completion',
    ]);
    echo "\nContract draft created: {$contract['id']}\n";
    echo "  Title: {$contract['title']}\n";
    echo "  Value: {$contract['value']} {$contract['currency']}\n";

    // 8. Advance to negotiation and then close-won
    $negotiation = $essabu->trade->opportunities->update($opportunity['id'], [
        'stage'       => 'negotiation',
        'probability' => 80,
    ]);
    echo "\nOpportunity in negotiation (probability: {$negotiation['probability']}%)\n";

    $won = $essabu->trade->opportunities->update($opportunity['id'], [
        'stage'       => 'closed_won',
        'probability' => 100,
        'closedDate'  => '2026-04-15',
    ]);
    echo "Opportunity WON! Value: {$won['value']} {$won['currency']}\n";

    // 9. Convert contact to customer
    $customer = $essabu->trade->customers->create([
        'contactId' => $contact['id'],
        'name'      => 'Global Corp Ltd',
        'email'     => 'accounts@globalcorp.cd',
        'type'      => 'enterprise',
    ]);
    echo "\nCustomer created from contact: {$customer['id']}\n";
    echo "  Name: {$customer['name']}\n";

    // 10. View pipeline summary
    $pipeline = $essabu->trade->opportunities->list([
        'page'  => 0,
        'size'  => 20,
    ]);
    echo "\nCurrent pipeline:\n";
    foreach ($pipeline as $opp) {
        echo "  - {$opp['name']}: {$opp['stage']} ({$opp['value']} {$opp['currency']})\n";
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
