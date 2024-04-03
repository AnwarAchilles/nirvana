<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Migration_Create_user extends CI_Migration {
    /**
     * Array table fields.
     * 
     * @param array
     */
    private $fields;

    /**
     * Primary key.
     * 
     * @param array
     */
    private $primary;

    /**
     * Table name.
     * 
     * @param string
     */
    private $name;

    public function __construct() {
        parent::__construct();
        $this->name = 'user';
        $this->primary = 'id_user';
        $this->fields = [
            $this->primary => [
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'serial'=> [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'name'=> [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'code'=> [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ],
        ];
    }

    /**
     * Migration.
     * 
     * @return void
     */
    public function up() {
        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key($this->primary, TRUE);
        $this->dbforge->create_table($this->name);
    }

    /**
     * Rollback migration.
     * 
     * @return void
     */
    public function down() {
        $this->dbforge->drop_table($this->name, TRUE);
    }
}
