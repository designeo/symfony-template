#!/usr/bin/env bash

function staged_files() {
    cd $ROOT && git diff --name-only --diff-filter=ACMR HEAD
}

function staged_php_files() {
    staged_files | grep \.php
}
