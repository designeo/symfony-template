#!/usr/bin/env bash

[ -z "${ROOT}" ] && emergency "ROOT variable must be set!"

## Symfony bootstrap
# Ensure console symlink in bin

function link_console() {
    if [ -e $ROOT/bin ] ;
    then
        ln -snf $ROOT/app/console $ROOT/bin/console
    fi
}



function delete_cache() {
    rm -rf $ROOT/app/cache/* $ROOT/app/logs/*
}

function composer_install_prod() {
    composer install --no-dev --optimize-autoloader --no-interaction
}

### run hooks
link_console