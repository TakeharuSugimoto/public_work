#!/bin/bash

# Set Token.
TOKEN="xxxxx"

if [ $# -ne 3 ]; then
    echo "usage:bash $0 ROOMID"
    exit
fi

ROOMID=${1}
ENV=${2}
FROM=${3}

#Body Operation
DATE=`date "+%Y/%m/%d %H:%M:%S"`

# メッセージ送信
RESPONSE=`curl -X POST -H "X-ChatWorkToken: ${TOKEN}" -F"file=@/var/source/develop1/server/batch/diff.txt" -F"message=${ENV}と${FROM}との差分です." "https://api.chatwork.com/v2/rooms/${ROOMID}/files"`

#
#EoF
