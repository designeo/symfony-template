#!/usr/bin/env bash



if [ -z "${IS_PRIVATE_CHANNEL+x}" ]; then
    PREFIX="%23"
else
    PREFIX=""
fi


function sendDeployMessage(){
    local CHANNEL=$1
    local ACTION=$2
    local ENV=$3
    local PROJECT=$4
    local COMMIT=$5
    local STATUS=$6

    local RESPONSE=$(\curl -s "https://slack.com/api/chat.postMessage?token=${SLACK_TOKEN}&channel=${PREFIX}${CHANNEL}&as_user=true" \
         -d attachments="[{\
         \"fallback\":\"${ACTION} to ${ENV}\",\
         \"title\": \"${ACTION} to ${ENV}\",\
         \"color\": \"${STATUS}\",\
         \"fields\": [{\
            \"title\": \"Commit\",\
            \"value\": \"<http://git.designeo.cz/${PROJECT}/commit/${COMMIT}|${COMMIT}>\",\
            \"short\": true\
         }]\
       }]")


    local ESCAPED_RESPONSE=$(echo ${RESPONSE} | sed "s/\"/\\\"/g")

    LAST_MSG_CHANNEL=$(php -r '$x = $argv[1]; echo json_decode($x,true)["channel"];' "$ESCAPED_RESPONSE")
    LAST_MSG_TS=$(php -r '$x = $argv[1]; echo json_decode($x,true)["ts"];' "$ESCAPED_RESPONSE")
}

function deleteLastMessage(){
    deleteMessage $LAST_MSG_CHANNEL $LAST_MSG_TS
}

function deleteMessage(){
    local CHANNEL=$1
    local TS=$2
    \curl -s "https://slack.com/api/chat.delete?token=${SLACK_TOKEN}&channel=${CHANNEL}&ts=${TS}" > /dev/null
}


function deployStart(){
    local CHANNEL=$1
    local ENV=$2
    sendDeployMessage ${CHANNEL} "Deploying" ${ENV} ${PROJECT} ${CI_BUILD_REF} "warning"
    info "SLACK: Channel notified"
}

function deployFinished(){
    local CHANNEL=$1
    local ENV=$2
    deleteLastMessage
    sendDeployMessage ${CHANNEL} "Deploy finished" ${ENV} ${PROJECT} ${CI_BUILD_REF} "good"
    info "SLACK: Channel notified"
}

function deployFailed(){
    local CHANNEL=$1
    local ENV=$2
    deleteLastMessage
    sendDeployMessage ${CHANNEL} "Deploy failed" ${ENV} ${PROJECT} ${CI_BUILD_REF} "danger"
    info "SLACK: Channel notified"
}