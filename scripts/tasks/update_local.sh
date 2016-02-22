#!/usr/bin/env bash

## get bootstrap path (able to follow single symlink)
BOOTSTRAP=$(dirname $(realpath $0))/../bootstrap.sh

## help
HELP="Update current branch and install dependencies"

## define interface
read -r -d '' usage <<-'EOF'
  -d         Enables debug mode
  -h         This page
EOF

## run bootstrap
source $BOOTSTRAP


PARAMETERS=$ROOT/app/config/parameters.yml

## do the job
git pull

if [ ! -e $PARAMETERS ] ;
then
  composer install --no-scripts
  link_console
  info "Configure parameters.yml and create the database..."
  read -p "After finishing press any key to continue... " -n1 -s
fi

composer install
npm install
bower install
gulp build
console c:c
console d:m:m -n
console d:f:l --append -n
console c:c
