#!/usr/bin/env bash

[ -z "${ROOT}" ] && emergency "ROOT variable must be set!"

## Symfony bootstrap
# Ensure console symlink in bin
ln -snf $ROOT/app/console $ROOT/bin/console

function delete_cache() {
    rm -rf $ROOT/app/cache/* $ROOT/app/logs/*
}

function composer_install_prod() {
    composer install --no-dev --optimize-autoloader --no-interaction
}