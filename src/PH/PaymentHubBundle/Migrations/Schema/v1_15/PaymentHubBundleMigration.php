<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_15;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class PaymentHubBundleMigration implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN orderid TO order_id;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN providertype TO provider_type;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN purchasestate TO purchase_state;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN paymentstate TO payment_state;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN checkoutcompletedat TO checkout_completed_at;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN updatedat TO updated_at;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN activationemailsend TO activation_email_send;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN enddate TO end_date;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN createdat TO created_at;');
        $queries->addQuery('ALTER TABLE ph_subscription RENAME COLUMN startdate TO start_date;');

        $queries->addQuery('ALTER TABLE ph_notification_log RENAME COLUMN emailcontent TO email_content;');
        $queries->addQuery('ALTER TABLE ph_notification_log RENAME COLUMN sendat TO send_at;');

        $queries->addQuery('ALTER TABLE ph_order_item RENAME COLUMN orderitemid TO order_item_id;');
        $queries->addQuery('ALTER TABLE ph_order_item RENAME COLUMN unitprice TO unit_price;');
        $queries->addQuery('ALTER TABLE ph_order_item RENAME COLUMN createdat TO created_at;');
        $queries->addQuery('ALTER TABLE ph_order_item RENAME COLUMN updatedat TO updated_at;');

        $queries->addQuery('ALTER TABLE ph_payment RENAME COLUMN paymentid TO payment_id;');
        $queries->addQuery('ALTER TABLE ph_payment RENAME COLUMN methodcode TO method_code;');
        $queries->addQuery('ALTER TABLE ph_payment RENAME COLUMN holdername TO holder_name;');
        $queries->addQuery('ALTER TABLE ph_payment RENAME COLUMN currencycode TO currency_code;');
        $queries->addQuery('ALTER TABLE ph_payment RENAME COLUMN createdat TO created_at;');
        $queries->addQuery('ALTER TABLE ph_payment RENAME COLUMN updatedat TO updated_at;');

        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN newsletterallowed TO newsletter_allowed;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN giftallowed TO gift_allowed;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN publiccomment TO public_comment;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN customerupdatetoken TO customer_update_token;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN contactforbidden TO contact_forbidden;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN emailverificationtoken TO email_verification_token;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN processemailverification TO process_email_verification;');
        $queries->addQuery('ALTER TABLE ph_customer RENAME COLUMN emailverifiedat TO email_verified_at;');
    }
}
