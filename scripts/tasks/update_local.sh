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

## do the job
git pull
composer install
npm install
bower install
gulp build
console c:c
console d:m:m -n
console d:f:l --append -n
console c:c
