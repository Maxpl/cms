<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.08.2015
 */

use yii\db\Schema;
use yii\db\Migration;

class m210702_121000__alter_table__cms_storage_file extends Migration
{

    public function safeUp()
    {
        $tableName = 'cms_storage_file';
        $this->alterColumn($tableName, "mime_type", $this->string(32));
    }

    public function safeDown()
    {
        echo "m191227_015615__alter_table__cms_tree cannot be reverted.\n";
        return false;
    }
}