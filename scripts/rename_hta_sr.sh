#!/bin/bash

#if [ -z "$1" ]; then
#  echo "No argument supplied"
#  echo "You must pass the application root folder"
#  exit
#fi

app_root_path="`pwd`"
site_name=$(basename $app_root_path)
htaccess="${app_root_path}/.htaccess"
app_config_path="${app_root_path}/config/application.php"
documents_root="/Users/marczal/Sites;/var/www";

function change_htaccess_paths() {
  log_path="${app_root_path}/logs/application.log"

  echo "rename log path"
  sed 's/php_value error_log.*/php_value error_log '"${log_path//\//\/}"'/g' $htaccess > "${htaccess}.changed"
  mv "${htaccess}.changed" "${htaccess}"

  echo "rename RewriteBase path"
  sed 's/RewriteBase.*/RewriteBase '"${site_root_path//\//\/}\/"'/g' $htaccess > "${htaccess}.changed"
  mv "${htaccess}.changed" "${htaccess}"
}

function change_application_paths() {
  echo "rename config/application.php SITE_ROOT"

  sed "s/define('SITE_ROOT'.*);/define('SITE_ROOT', '${site_root_path//\//\/}');/g" $app_config_path > "${app_config_path}.changed"
  mv "${app_config_path}.changed" $app_config_path
}

function fixed_permissions() {
  if [ -d "${app_root_path}/logs" ]; then
    echo "Fixed log permission"
    chmod -R 777 "${app_root_path}/logs"
    chmod 777 "${app_root_path}/app/assets/photos/original"
    chmod 777 "${app_root_path}/app/assets/photos/thumb"
  fi
}

function get_site_root() {
  arr=$(echo $documents_root | tr ";" "\n")
  site_root_path=$app_root_path
  for dc in $arr
  do
    site_root_path=`echo "$site_root_path" | sed "s/${dc//\//\/}//g"`
  done
}

function rename_auto_prepend_file() {
  prepend_file="${app_config_path}"
  echo "rename auto_prepend_file"
  sed 's/php_value auto_prepend_file.*/php_value auto_prepend_file '"${app_config_path//\//\/}"'/g' $htaccess > "${htaccess}.changed"
  mv "${htaccess}.changed" "${htaccess}"
}

function run() {
  if [ -f $htaccess ] && [ -f $app_config_path ]; then
    get_site_root
    change_htaccess_paths
    change_application_paths
    fixed_permissions
  else
    echo "Error: files not found:"
    echo "\t${htaccess}"
    echo "\t${app_config_path}"
  fi
}

run $1
