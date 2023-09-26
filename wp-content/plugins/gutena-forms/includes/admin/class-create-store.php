<?php
/**
* create form data tables
* https://codex.wordpress.org/Creating_Tables_with_Plugins
* https://developer.wordpress.org/reference/classes/wpdb/
*
*/

 defined( 'ABSPATH' ) || exit;

 /**
 * Abort if the class is already exists.
 */
 if ( ! class_exists( 'Gutena_Forms_Create_Store' ) && class_exists( 'Gutena_Forms_Store' ) ) {

	class Gutena_Forms_Create_Store extends Gutena_Forms_Store {
        
		// The instance of this class
		private static $instance = null;

		// Returns the instance of this class.
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            parent::__construct();
			//On activate tables.
			add_action( 'gutena_forms_activation_begins', array( $this, 'create_tables' ) );
            //On activate tables.
			add_action( 'gutena_forms_activation_end', array( $this, 'initialize_tables' ) );
		}

        //Initialize tables with existing gutena forms
        public function initialize_tables() {
            global $wpdb; 
            //get already saved forms
            $gutena_form_ids = get_option( 'gutena_form_ids', false );
            if ( empty( $wpdb ) || ! $this->include_db_upgrade_file() || empty( $gutena_form_ids ) || ! is_array( $gutena_form_ids ) ) {
                return;
            }
            $table_name = $this->table_gutenaforms; 
            //check if table exist
            $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
            if ( $wpdb->get_var( $query ) === $table_name ) {
                //check if table already initialized
                if ( empty( $wpdb->get_var( "SELECT COUNT(form_id) FROM $table_name" ) ) ) {
                    $completed_ids = array();
                    foreach ($gutena_form_ids as $form_id) {
                        //continue for repeating id
                        if ( in_array( $form_id, $completed_ids ) ) {
                            continue;
                        }
                        //update completed id
                        $completed_ids[] = $form_id;

                        //get form schema
                        $form_id = sanitize_key( $form_id );
                        $form_schema = get_option( $form_id, false );

                        if ( ! empty( $form_schema ) && ! empty( $form_schema['form_attrs'] ) ) {
                            $this->save_new_form( $form_id, $form_schema );
                        }
                    }
                }
            }
        }

        //create required tables
		public function create_tables() {
            global $wpdb;
            if ( empty( $wpdb ) || ! $this->include_db_upgrade_file() || ! function_exists( 'dbDelta' ) ) {
                return;
            } 
            $charset_collate = $wpdb->get_charset_collate();
            //Gutena forms
            $this->create_table_gutenaforms( $charset_collate );
            //form entries or submission
            $this->create_table_gutenaforms_entries( $charset_collate );
            //form entries field name value
            $this->create_table_gutenaforms_field_value( $charset_collate );
            //metadata related to form and form entries
            $this->create_table_gutenaforms_meta( $charset_collate );
            
            //check if table created successfully
            $table_names =  array(
                $this->table_gutenaforms,
                $this->table_gutenaforms_entries,
                $this->table_gutenaforms_field_value,
                $this->table_gutenaforms_meta,
            );
            $all_tables_craeted = true;
            foreach ( $table_names as $table_name ) {
                //check if table exist
                $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
                if ( $wpdb->get_var( $query ) !== $table_name ) {
                    $all_tables_craeted = false;
                    break;
                }
            }

            //delete failed flag if all tables created successfully
            if ( true === $all_tables_craeted ) {
                update_option( 'gutena_forms_store_version', GUTENA_FORMS_VERSION );
            }
           
		}


        /**
         * create form table
         * form_id : primary id
         * user_id : admin id who created or modified this form
         * block_form_id : unique form id created at a time of form block insertion
         * form_schema : serialized value:  form, field attributes, and block_markup (will be used in backup )
         * added_time : time creation
         * modified_time : updation time
         * published : published or unpublished
         */
        private function create_table_gutenaforms( $charset_collate ) {
            global $wpdb;
            $table_name =  $this->table_gutenaforms; 
            $main_sql_create = "CREATE TABLE {$table_name} (
                form_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                user_id bigint(20) unsigned NOT NULL,
                block_form_id varchar(90)  NOT NULL,
                form_name varchar(256)  NOT NULL,
                form_schema longtext  NOT NULL,
                added_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                modified_time timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                published tinyint(1) NOT NULL DEFAULT '1',
                PRIMARY KEY  (form_id),
                UNIQUE KEY  block_form_id (block_form_id)
               ) {$charset_collate};";    
            //Useful for creating new tables and updating existing tables to a new structure.
            dbDelta( $main_sql_create );
        }


         /**
         * create form entries table
         * entry_id : primary id
         * form_id : form_id
         * user_id : user id who submitted this form if any
         * modified_by : user id (admin ) who edited this form
         * entry_data : form submit data with label
         * added_time : row added time 
         * modified_time : entry_data modification time 
         * entry_status : unread, read
         * trash: 1: true, 0: false
         * 
         * 
         */
        private function create_table_gutenaforms_entries( $charset_collate ) {
            global $wpdb;
            $table_name = $this->table_gutenaforms_entries; 
            $main_sql_create = "CREATE TABLE {$table_name} (
                entry_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                form_id bigint(20) unsigned NOT NULL,
                user_id bigint(20) unsigned NOT NULL DEFAULT '0',
                modified_by bigint(20) unsigned NOT NULL,
                entry_data longtext  NOT NULL,
                ip_address varchar(128) DEFAULT NULL,
                added_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                modified_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                entry_status varchar(100)  NOT NULL DEFAULT 'unread',
                trash tinyint(1) NOT NULL DEFAULT '0',
                PRIMARY KEY  (entry_id),
                KEY form_id (form_id)
               ) {$charset_collate};";
            //Useful for creating new tables and updating existing tables to a new structure.
            dbDelta( $main_sql_create );
        }

        /**
         * create form entries field name value table
         * id : primary id
         * entry_id : entry_id
         * field_name : field name
         * field_value : field value
         * 
         */
        private function create_table_gutenaforms_field_value( $charset_collate ) {
            global $wpdb;
            $table_name =  $this->table_gutenaforms_field_value; 
            $main_sql_create = "CREATE TABLE {$table_name} (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                entry_id bigint(20) unsigned NOT NULL,
                field_name varchar(90)  NOT NULL,
                field_value longtext  NOT NULL,
                PRIMARY KEY  (id),
                KEY entry_id (entry_id),
                KEY field_name (field_name)
               ) {$charset_collate};";
            //Useful for creating new tables and updating existing tables to a new structure.
            dbDelta( $main_sql_create );
        }

        /**
         * create meta data table for form and form entries 
         * id : primary id
         * form_id : form_id
         * entry_id : entry_id, it will be 0 in case of form meta
         * user_id : user id who created or modified this data
         * data_type : log, note, submit_entry_data, form_schema_backup, form_settings
         * metadata : entry meta data
         * modified_time : time creation or updation
         * 
         * Notes:
         * submit_entry_data: submitted form entry data. It will not be modified
         * note : admin notes
         * 
         * entry_id = 0 : means form related data
         * 
         */
        private function create_table_gutenaforms_meta( $charset_collate ) {
            global $wpdb;
            $table_name = $this->table_gutenaforms_meta; 
            $main_sql_create = "CREATE TABLE {$table_name} (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                form_id bigint(20) unsigned NOT NULL,
                entry_id bigint(20) unsigned NOT NULL DEFAULT '0',
                user_id bigint(20) unsigned NOT NULL DEFAULT '0',
                data_type varchar(90)  NOT NULL,
                metadata longtext  NOT NULL,
                modified_time timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY  (id),
                KEY form_id (form_id),
                KEY entry_id (entry_id),
                KEY data_type (data_type)
               ) {$charset_collate};";    
            //Useful for creating new tables and updating existing tables to a new structure.
            dbDelta( $main_sql_create );
        }
		
	}

	Gutena_Forms_Create_Store::get_instance();
 }
