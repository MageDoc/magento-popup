<?php
/* @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();

$table = $installer->getTable('popup/popup');

$connection
    ->addColumn($table, 'utm_campaign', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'comment'   => 'UTM Campaign',
    ));

$connection
    ->addColumn($table, 'full_action_name', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'comment'   => 'Full Action Name',
    ));

$connection
    ->addColumn($table, 'url_expression', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'comment'   => 'URL Expression',
    ));

$connection
    ->addColumn($table, 'show_after', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => true,
        'comment'   => 'Show After',
    ));

$connection
    ->addColumn($table, 'max_show_times', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'default'   => 1,
        'comment'   => 'Max Show Times',
    ));

$connection
    ->addColumn($table, 'first_show_delay', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'default'   => 0,
        'comment'   => 'First Show Delay',
    ));

$connection
    ->addColumn($table, 'min_show_interval', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'default'   => 0,
        'comment'   => 'Min Show Interval',
    ));

$connection
    ->addColumn($table, 'min_show_scroll', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => false,
        'default'   => '',
        'comment'   => 'Min Show Scroll',
    ));

$connection
    ->addColumn($table, 'is_collapsed', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'default'   => 0,
        'comment'   => 'Is Collapsed',
    ));

$connection
    ->addColumn($table, 'can_collapse', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'default'   => 0,
        'comment'   => 'Can Collapse',
    ));

$connection
    ->addColumn($table, 'can_close', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'default'   => 1,
        'comment'   => 'Can Close',
    ));

$connection
    ->addColumn($table, 'css_classes', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
        'nullable'  => false,
        'default'   => '',
        'comment'   => 'CSS Classes',
    ));

$connection
    ->addColumn($table, 'fancybox_settings', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Fancybox Settings',
    ));


$connection->addIndex(
    $table,
    $installer->getIdxName($table, array('utm_campaign')),
    array('utm_campaign')
);

$connection->addIndex(
    $table,
    $installer->getIdxName($table, array('full_action_name')),
    array('full_action_name')
);

$connection->addIndex(
    $table,
    $installer->getIdxName($table, array('status', 'popup_date_start', 'popup_date_end', 'utm_campaign')),
    array('status', 'popup_date_start', 'popup_date_end', 'utm_campaign')
);

$installer->endSetup();