#!/usr/bin/env bash

## get bootstrap path (able to follow single symlink)
BOOTSTRAP=$(cd $(dirname $0)/$(dirname $(readlink $0) 2>/dev/null) && pwd)/../bootstrap.sh

## help
HELP="Run tests"

## define interface
read -r -d '' usage <<-'EOF'
  -d         Enables debug mode
  -h         This page
EOF

## run bootstrap
source $BOOTSTRAP

## do the job
phpunit -c app