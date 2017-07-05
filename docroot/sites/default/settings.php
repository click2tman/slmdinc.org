<?php

/**
 * @file
 * Drupal site-specific configuration file.
 *
 */

 $databases = array();

 #   $databases['default']['default'] = array(
 #    'driver' => 'pgsql',
 #     'database' => 'databasename',
 #     'username' => 'sqlusername',
 #     'password' => 'sqlpassword',
 #     'host' => 'localhost',
 #     'prefix' => '',
 #  );

$config_directories = array();
$config_directories = array(
    CONFIG_SYNC_DIRECTORY => '../config',
);
$settings['hash_salt'] = '';

$settings['update_free_access'] = FALSE;

# $settings['omit_vary_cookie'] = TRUE;

# $settings['file_chmod_directory'] = 0775;
# $settings['file_chmod_file'] = 0664;

# $settings['file_public_base_url'] = 'http://downloads.example.com/files';

 $settings['file_public_path'] = 'downloads';

 $settings['file_private_path'] = '../files-private';


# $settings['session_write_interval'] = 180;


# $settings['locale_custom_strings_en'][''] = array(
#   'forum'      => 'Discussion board',
#   '@count min' => '@count minutes',
# );

# $settings['maintenance_theme'] = 'bartik';


# ini_set('pcre.backtrack_limit', 200000);
# ini_set('pcre.recursion_limit', 200000);

# $config['system.site']['name'] = 'My Drupal site';
# $config['system.theme']['default'] = 'stark';
$config['user.settings']['anonymous'] = 'Visitor';

$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];


 if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
   include $app_root . '/' . $site_path . '/settings.local.php';
 }

$settings['trusted_host_patterns'] = array(
   '^dev.ocms.lamtech\.sl',
   '^.+\.gov\.sl',
   '^lamtech\.sl',
   '^.+\.lamtech\.sl',
 );

$settings['install_profile'] = 'lightning';
