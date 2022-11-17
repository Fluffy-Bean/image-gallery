
<?php
/*
 |-------------------------------------------------------------
 | Database Sanity Check
 |-------------------------------------------------------------
 | this will check if the database table is setup correctly
 | Adding these checks hopefully will make the gallery more
 | stable as I dont trust my programming skills nor my ability
 | to write good documentation.
 |
 | Im also so sorry for the code you're about to read
 |-------------------------------------------------------------
*/

if (defined('ROOT') && $_SESSION['id'] == 1) {
    /*             Column name      Type            Null    Key     Default                 Extra                 */
    $table_templates = array(
        'images' => array(
            array('id',             'int(11)',      'NO',   'PRI',  '',                     'auto_increment'),
            array('imagename',      'varchar(255)', 'NO',   'UNI',  '',                     ''),
            array('alt',            'text',         'YES',  '',     '',                     ''),
            array('tags',           'text',         'YES',  '',     '',                     ''),
            array('author',         'int(11)',      'NO',   '',     '',                     ''),
            array('last_modified',  'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    'on update CURRENT_TIMESTAMP'),
            array('upload_date',    'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    '')
        ),
        'users' => array(
            array('id',             'int(11)',      'NO',   'PRI',  '',                     'auto_increment'),
            array('username',       'varchar(50)',  'NO',   'UNI',  '',                     ''),
            array('password',       'varchar(255)', 'NO',   '',     '',                     ''),
            array('pfp_path',       'varchar(50)',  'NO',   '',     '',                     ''),
            array('admin',          'boolean',      'NO',   '',     'FALSE',                ''),
            array('last_modified',  'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    'on update CURRENT_TIMESTAMP'),
            array('created_at',     'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    '')
        ),
        'groups' => array(
            array('id',             'int(11)',      'NO',   'PRI',  '',                     'auto_increment'),
            array('group_name',     'varchar(255)', 'NO',   'UNI',  '',                     ''),
            array('author',         'varchar(50)',  'NO',   '',     '',                     ''),
            array('image_list',     'text',         'NO',   '',     '',                     ''),
            array('last_modified',  'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    'on update CURRENT_TIMESTAMP'),
            array('created_at',     'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    '')
        ),
        'tokens' => array(
            array('id',             'int(11)',      'NO',   'PRI',  '',                     'auto_increment'),
            array('code',           'varchar(255)', 'NO',   '',     '',                     ''),
            array('used',           'boolean',      'NO',   '',     'FALSE',                ''),
            array('created_at',     'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    '')
        ),
        'logs' => array(
            array('id',             'int(11)',      'NO',   'PRI',  '',                     'auto_increment'),
            array('ipaddress',      'varchar(16)',  'NO',   '',     '',                     ''),
            array('action',         'varchar(255)', 'NO',   '',     '',                     ''),
            array('created_at',     'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    '')
        ),
        'bans' => array(
            array('id',             'int(11)',      'NO',   'PRI',  '',                     'auto_increment'),
            array('ipaddress',      'varchar(16)',  'NO',   '',     '',                     ''),
            array('reason',         'text',         'NO',   '',     '',                     ''),
            array('time',           'timestamp',    'NO',   '',     'CURRENT_TIMESTAMP',    ''),
            array('length',         'int(255)',     'NO',   '',     '',                     ''),
            array('pernament',      'boolean',      'NO',   '',     'FALSE',                '')
        )
    );

    function check_table($conn, $table) {
        $results = array();
        try {
            $query = mysqli_query($conn, "DESCRIBE ".$table);
    
            while($row = mysqli_fetch_array($query)) {
                $results[] = array(
                    $row['Field'], 
                    $row['Type'], 
                    $row['Null'], 
                    $row['Key'], 
                    $row['Default'], 
                    $row['Extra']
                );
            }
        } catch (Exception $e) {
            return false;
        }
    
        return $results;
    }

    //$table_list = array('images', 'users', 'groups', 'tokens', 'logs', 'bans', 'test');
    $table_list = array('images', 'users', 'groups', 'tokens', 'logs', 'bans');

    foreach ($table_list as $table) {
        $error_type = array();
        $get_table = check_table($conn, $table);

        if ($get_table != false) {
            foreach ($table_templates[$table] as $key => $value) {
                if (!empty(array_diff($get_table[$key], $value))) {
                    $error_type[] = $table;
                    foreach (array_diff($get_table[$key], $value) as $value) {
                        $error_type[$table][] = $value;
                    }
                }
            }

            if (!empty($error_type)) {
                $results[] = array(
                    'type'=>'warning', 
                    'message'=> 'Table "'.$table.'" is not setup correctly. Please make sure you setup the table correctly. Errors found: '.count($error_type[$table]),
                    'fix'=>'manual'
                );
            }
            
        } else {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Could not find table '.$table, 
                'fix'=>'auto'
            );
        }
    }
}
