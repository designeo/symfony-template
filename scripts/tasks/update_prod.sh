#!/usr/bin/env bash

## get bootstrap path (able to follow single symlink)
BOOTSTRAP=$(cd $(dirname $0)/$(dirname $(readlink $0) 2>/dev/null) && pwd)/../bootstrap.sh

## help
HELP="Update current branch and install dependencies"

## define interface
read -r -d '' usage <<-'EOF'
  -d         Enables debug mode
  -h         This page
EOF

## run bootstrap
source $BOOTSTRAP

function report_failed_deploy () {
  deployFailed $CHANNEL $ENV
}
trap report_failed_deploy ERR

## do the job
export SYMFONY_ENV=prod

deployStart $CHANNEL $ENV

composer_install_prod
npm install
bower install
gulp build --production
delete_cache
console d:m:m -n
console d:f:l --append -n
delete_cache
sudo -u www-data -H php bin/console c:c --env=prod --no-debug

deployFinished $CHANNEL $ENV
