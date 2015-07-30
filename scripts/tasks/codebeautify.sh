#!/usr/bin/env bash

## get bootstrap path (able to follow single symlink)
BOOTSTRAP=$(cd $(dirname $0)/$(dirname $(readlink $0) 2>/dev/null) && pwd)/../bootstrap.sh

## help
HELP="Runs code beautyfier!"

## run bootstrap
read -r -d '' usage <<-'EOF'
  -g         Run on staged files
  -f   [arg] Specify file or folder for beautification (mandatory if -g not specified)
  -d         Enables debug mode
  -h         This page
EOF

## run bootstrap
source $BOOTSTRAP

## process parameters
if [ "${arg_g}" = "1" ]; then
  arg_f=$(staged_php_files)
fi

[ -z "${arg_f}" ] && emergency "Cannot continue without specified directory or file to beautify."

## do the job ...
info "Beautifing $arg_f"
phpcbf --standard=$ROOT/vendor/leaphub/phpcs-symfony2-standard/leaphub/phpcs/Symfony2 --extensions=php $ROOT/$arg_f
