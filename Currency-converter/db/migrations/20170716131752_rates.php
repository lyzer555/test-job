<?php

use Phinx\Migration\AbstractMigration;

class Rates extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
		$rates = $this->table('CurrencyRates');
			$rates->addColumn('Currency_In', "string", ['limit' => 32])
				 	->addColumn('Currency_Out', "string", ['limit' => 32])
					->addColumn('Rate', "float", [])
					->create();
    }
}
