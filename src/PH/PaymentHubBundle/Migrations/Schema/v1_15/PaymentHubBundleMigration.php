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
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE orderid order_id VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE providertype provider_type VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE purchasestate purchase_state VARCHAR(255) NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE paymentstate payment_state VARCHAR(255) NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE checkoutcompletedat checkout_completed_at DATETIME COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE updatedat updated_at DATETIME COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE activationemailsend activation_email_send DATETIME COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE enddate end_date DATETIME COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE createdat created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_subscription CHANGE startdate start_date DATE COMMENT \'(DC2Type:date)\';');

        $queries->addQuery('ALTER TABLE ph_notification_log CHANGE emailcontent email_content LONGTEXT;');
        $queries->addQuery('ALTER TABLE ph_notification_log CHANGE sendAt send_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\';');

        $queries->addQuery('ALTER TABLE ph_order_item CHANGE orderitemid order_item_id VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_order_item CHANGE unitprice unit_price DOUBLE NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_order_item CHANGE createdat created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_order_item CHANGE updatedat updated_at DATETIME COMMENT \'(DC2Type:datetime)\';');

        $queries->addQuery('ALTER TABLE ph_payment CHANGE paymentid payment_id VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_payment CHANGE methodcode method_code VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_payment CHANGE holdername holder_name VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_payment CHANGE currencycode currency_code VARCHAR(255) NOT NULL;');
        $queries->addQuery('ALTER TABLE ph_payment CHANGE createdat created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\';');
        $queries->addQuery('ALTER TABLE ph_payment CHANGE updatedat updated_at DATETIME COMMENT \'(DC2Type:datetime)\';');

        $queries->addQuery('ALTER TABLE ph_customer CHANGE newsletterallowed newsletter_allowed TINYINT(1);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE giftallowed gift_allowed TINYINT(1);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE publiccomment public_comment TINYINT(1);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE customerupdatetoken customer_update_token VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE contactforbidden contact_forbidden TINYINT(1);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE emailverificationtoken email_verification_token VARCHAR(255);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE processemailverification process_email_verification TINYINT(1);');
        $queries->addQuery('ALTER TABLE ph_customer CHANGE emailVerifiedAt email_verified_at DATETIME COMMENT \'(DC2Type:datetime)\';');
    }
}
