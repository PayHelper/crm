<?php

namespace PH\PaymentHubBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\Migration\ExtendOptionsManager;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PHPaymentHubBundleInstaller implements Installation, ContainerAwareInterface
{
    protected $container;

    protected $extendOptionsManager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /* @var ExtendOptionsManager $extendOptionsManager */
        $this->extendOptionsManager = $this->container->get('oro_entity_extend.migration.options_manager');

        /* Tables generation **/
        $this->createPhCustomerTable($schema);
        $this->createPhOrderItemTable($schema);
        $this->createPhPaymentTable($schema);
        $this->createPhSubscriptionTable($schema);
        $this->createPhContactAddressTable($schema);

        /* Foreign keys generation **/
        $this->addPhCustomerForeignKeys($schema);
        $this->addPhOrderItemForeignKeys($schema);
        $this->addPhPaymentForeignKeys($schema);
        $this->addPhSubscriptionForeignKeys($schema);
        $this->addPhContactAddressForeignKeys($schema);

        $queries->addQuery('CREATE TABLE ph_contact_adr_to_adr_type (contact_address_id INT NOT NULL, type_name VARCHAR(16) NOT NULL, PRIMARY KEY(contact_address_id, type_name));');
        $queries->addQuery('CREATE INDEX IDX_E6FB3400320EF6E2 ON ph_contact_adr_to_adr_type (contact_address_id);');
        $queries->addQuery('CREATE INDEX IDX_E6FB3400892CBB0E ON ph_contact_adr_to_adr_type (type_name);');
        $queries->addQuery('ALTER TABLE ph_contact_adr_to_adr_type ADD CONSTRAINT FK_E6FB3400320EF6E2 FOREIGN KEY (contact_address_id) REFERENCES ph_contact_address (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $queries->addQuery('ALTER TABLE ph_contact_adr_to_adr_type ADD CONSTRAINT FK_E6FB3400892CBB0E FOREIGN KEY (type_name) REFERENCES oro_address_type (name) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $queries->addQuery('ALTER TABLE ph_contact_address ADD serialized_data TEXT DEFAULT NULL;');
        $queries->addQuery('ALTER TABLE ph_contact_address ADD is_primary BOOLEAN DEFAULT NULL;');
        $queries->addQuery('COMMENT ON COLUMN ph_contact_address.serialized_data IS \'(DC2Type:array)\';');
    }

    /**
     * Create ph_customer table.
     *
     * @param Schema $schema
     */
    protected function createPhCustomerTable(Schema $schema)
    {
        $table = $schema->createTable('ph_customer');
        $this->extendOptionsManager->setTableOptions('ph_customer', [
            'entity' => [
                'label' => 'PaymentHub Customer',
                'plural_label' => 'PaymentHub Customers',
            ],
        ]);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('data_channel_id', 'integer', ['notnull' => false]);
        $table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('first_name', 'string', [
            'notnull' => false,
            'length' => 255,
            'oro_options' => [
                'entity' => [
                    'label' => 'First Name',
                    'plural_label' => 'First Names',
                    'description' => 'First name',
                ],
            ],
        ]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('gender', 'string', ['notnull' => false, 'length' => 8]);
        $table->addColumn('birthday', 'datetime', ['notnull' => false, 'comment' => '(DC2Type:datetime)']);
        $table->addColumn('email', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('createdat', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updatedat', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addIndex(['data_channel_id'], 'idx_1e65785cbdc09b73', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create ph_order_item table.
     *
     * @param Schema $schema
     */
    protected function createPhOrderItemTable(Schema $schema)
    {
        $table = $schema->createTable('ph_order_item');
        $this->extendOptionsManager->setTableOptions('ph_order_item', [
            'entity' => [
                'label' => 'PaymentHub Order Item',
                'plural_label' => 'PaymentHub Order Items',
            ],
        ]);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('subscription_id', 'integer', ['notnull' => false]);
        $table->addColumn('orderitemid', 'string', ['notnull' => false]);
        $table->addColumn('quantity', 'integer', []);
        $table->addColumn('unitprice', 'float', []);
        $table->addColumn('total', 'float', []);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('createdat', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updatedat', 'datetime', ['notnull' => false, 'comment' => '(DC2Type:datetime)']);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['subscription_id'], 'idx_c1f87e6d9a1887dc', []);
    }

    /**
     * Create ph_payment table.
     *
     * @param Schema $schema
     */
    protected function createPhPaymentTable(Schema $schema)
    {
        $table = $schema->createTable('ph_payment');
        $this->extendOptionsManager->setTableOptions('ph_payment', [
            'entity' => [
                'label' => 'PaymentHub Payment',
                'plural_label' => 'PaymentHub Payments',
            ],
        ]);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('subscription_id', 'integer', ['notnull' => false]);
        $table->addColumn('paymentid', 'string', ['notnull' => false]);
        $table->addColumn('methodcode', 'string', ['length' => 255]);
        $table->addColumn('currencycode', 'string', ['length' => 255]);
        $table->addColumn('amount', 'float', []);
        $table->addColumn('state', 'string', ['length' => 255]);
        $table->addColumn('createdat', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updatedat', 'datetime', ['notnull' => false, 'comment' => '(DC2Type:datetime)']);
        $table->addIndex(['subscription_id'], 'idx_8e3a744b9a1887dc', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create ph_subscription table.
     *
     * @param Schema $schema
     */
    protected function createPhSubscriptionTable(Schema $schema)
    {
        $table = $schema->createTable('ph_subscription');
        $this->extendOptionsManager->setTableOptions('ph_subscription', [
            'entity' => [
                'label' => 'PaymentHub Subscription',
                'plural_label' => 'PaymentHub Subscriptions',
            ],
        ]);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('customer_id', 'integer', ['notnull' => false]);
        $table->addColumn('orderid', 'string', ['notnull' => false]);
        $table->addColumn('providertype', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('checkoutstate', 'string', ['length' => 255]);
        $table->addColumn('paymentstate', 'string', ['length' => 255]);
        $table->addColumn('orderstate', 'string', ['length' => 255]);
        $table->addColumn('checkoutcompletedat', 'datetime', ['notnull' => false, 'comment' => '(DC2Type:datetime)']);
        $table->addColumn('number', 'float', []);
        $table->addColumn('notes', 'text', ['notnull' => false]);
        $table->addColumn('total', 'float', []);
        $table->addColumn('createdat', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updatedat', 'datetime', ['notnull' => false, 'comment' => '(DC2Type:datetime)']);
        $table->addColumn('interval', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('token', 'string', ['length' => 255]);
        $table->addColumn('type', 'string', ['length' => 255]);
        $table->addColumn('startDate', 'date', ['notnull' => false, 'comment' => '(DC2Type:date)']);
        $table->addIndex(['customer_id'], 'idx_98e208ac9395c3f3', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create ph_contact_address table.
     *
     * @param Schema $schema
     */
    protected function createPhContactAddressTable(Schema $schema)
    {
        $table = $schema->createTable('ph_contact_address');
        $this->extendOptionsManager->setTableOptions('ph_contact_address', [
            'entity' => [
                'label' => 'PaymentHub Contact Address',
                'plural_label' => 'PaymentHub Contact Addresses',
            ],
        ]);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('country_code', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('street', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('street2', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('city', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('organization', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('region_text', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated', 'datetime', ['comment' => '(DC2Type:datetime)']);
        $table->addIndex(['region_code'], 'idx_3d87a12caeb327af', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['owner_id'], 'idx_3d87a12c7e3c61f9', []);
        $table->addIndex(['country_code'], 'idx_3d87a12cf026bb7c', []);
    }

    /**
     * Add ph_customer foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPhCustomerForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ph_customer');
        $table->addForeignKeyConstraint(
            $schema->getTable('orocrm_channel'),
            ['data_channel_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
    }

    /**
     * Add ph_order_item foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPhOrderItemForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ph_order_item');
        $table->addForeignKeyConstraint(
            $schema->getTable('ph_subscription'),
            ['subscription_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
    }

    /**
     * Add ph_payment foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPhPaymentForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ph_payment');
        $table->addForeignKeyConstraint(
            $schema->getTable('ph_subscription'),
            ['subscription_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
    }

    /**
     * Add ph_subscription foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPhSubscriptionForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ph_subscription');
        $table->addForeignKeyConstraint(
            $schema->getTable('ph_customer'),
            ['customer_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => null]
        );
    }

    /**
     * Add ph_contact_address foreign keys.
     *
     * @param Schema $schema
     */
    protected function addPhContactAddressForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ph_contact_address');
        $table->addForeignKeyConstraint(
            $schema->getTable('ph_customer'),
            ['owner_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_region'),
            ['region_code'],
            ['combined_code'],
            ['onUpdate' => null, 'onDelete' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_country'),
            ['country_code'],
            ['iso2_code'],
            ['onUpdate' => null, 'onDelete' => null]
        );
    }
}
