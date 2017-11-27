<?php

namespace PH\PaymentHubBundle\Migrations\Schema\v1_8;

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
        $queries->addQuery('ALTER TABLE ph_subscription ADD business_unit_owner_id INT DEFAULT NULL;');
        $queries->addQuery('ALTER TABLE ph_subscription ADD organization_id INT DEFAULT NULL;');
        $queries->addQuery('ALTER TABLE ph_subscription ADD CONSTRAINT FK_98E208AC59294170 FOREIGN KEY (business_unit_owner_id) REFERENCES oro_business_unit (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $queries->addQuery('ALTER TABLE ph_subscription ADD CONSTRAINT FK_98E208AC32C8A3DE FOREIGN KEY (organization_id) REFERENCES oro_organization (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $queries->addQuery('CREATE INDEX IDX_98E208AC59294170 ON ph_subscription (business_unit_owner_id);');
        $queries->addQuery('CREATE INDEX IDX_98E208AC32C8A3DE ON ph_subscription (organization_id);');
    }
}
