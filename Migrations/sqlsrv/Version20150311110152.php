<?php

namespace Claroline\CoreBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/11 11:01:54
 */
class Version20150311110152 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_widget_home_tab_config 
            ADD details VARCHAR(MAX)
        ");
        $this->addSql("
            EXEC sp_addextendedproperty N 'MS_Description', 
            N '(DC2Type:json_array)', 
            N 'SCHEMA', 
            dbo, 
            N 'TABLE', 
            claro_widget_home_tab_config, 
            N 'COLUMN', 
            details
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE claro_widget_home_tab_config 
            DROP COLUMN details
        ");
    }
}