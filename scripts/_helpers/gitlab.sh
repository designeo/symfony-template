#!/usr/bin/env bash

[ -z "${CI+x}" ] 							&& export CI=true
[ -z "${CI_SERVER+x}" ] 					&& export CI_SERVER=true
[ -z "${CI_SERVER_NAME+x}" ]			 	&& export CI_SERVER_NAME=GitLab CI
[ -z "${GITLAB_CI+x}" ] 					&& export GITLAB_CI=true
[ -z "${CI_SERVER_VERSION+x}" ] 			&& export CI_SERVER_VERSION="N/A"
[ -z "${CI_SERVER_REVISION+x}" ] 			&& export CI_SERVER_REVISION="N/A"
[ -z "${CI_BUILD_REF+x}" ] 				&& export CI_BUILD_REF="N/A"
[ -z "${CI_BUILD_BEFORE_SHA+x}" ] 		&& export CI_BUILD_BEFORE_SHA="N/A"
[ -z "${CI_BUILD_REF_NAME+x}" ] 			&& export CI_BUILD_REF_NAME="N/A"
[ -z "${CI_BUILD_ID+x}" ] 				&& export CI_BUILD_ID="N/A"
[ -z "${CI_BUILD_REPO+x}" ] 				&& export CI_BUILD_REPO="N/A"
[ -z "${CI_PROJECT_DIR+x}" ] 				&& export CI_PROJECT_DIR="N/A"