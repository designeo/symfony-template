#!/usr/bin/env bash

## get bootstrap path (able to follow single symlink)
BOOTSTRAP=$(cd $(dirname $0)/$(dirname $(readlink $0) 2>/dev/null) && pwd)/../bootstrap.sh

## help
HELP="This is help!!"

## run bootstrap
read -r -d '' usage <<-'EOF'
  -g         flag
  -x   [arg] Specify something mandatory
  -f   [arg] Specify something with default. Default="src"
  -d         Enables debug mode
  -h         This page
EOF

## run bootstrap
source $BOOTSTRAP

## process parameters
info $arg_f
if [ "${arg_g}" = "1" ]; then
  pass ##
fi

[ -z "${arg_x}" ] && emergency "Cannot continue without mandatory argument. "

## do the job ...