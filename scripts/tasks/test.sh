#!/usr/bin/env bash

## get bootstrap path
BOOTSTRAP=$(dirname $(realpath $0))/../bootstrap.sh

## help
HELP="Run tests"

## define interface
read -r -d '' usage <<-'EOF'
  -t   [arg] run only one test file
  -s   [arg] run only signle test
  -d         Enables debug mode
  -h         This page
EOF

## run bootstrap
source $BOOTSTRAP

## do the job
if [ -z "${arg_t}" ]; then
  $ROOT/bin/phpunit -c app
else
    if [ -z "${arg_s}" ]; then
        $ROOT/bin/phpunit -c app src/AppBundle/Tests/Controller/${arg_t}
    else
        $ROOT/bin/phpunit -c app src/AppBundle/Tests/Controller/${arg_t} --filter ${arg_s}
    fi

fi
