#!/bin/bash

curl -s --user 'api:key-somekey' https://api.mailgun.net/v3/mg.result-cloud.org/messages \
 -F from='ResultCloud Notification <notification@mg.result-cloud.org>' \
 -F to=$3 \
 -F subject="$1" \
 -F text="$2" 1> /dev/null