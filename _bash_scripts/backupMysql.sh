#!/usr/bin/env bash

#load configuration
. backup.config

send_ses_mail(){
    SUBJECT="$1";
    #remove new lines from json
    MSG="$(echo "$2"|tr -d '\n')";
    MAIL_TIME=`date +%Y-%m-%d_%H:%M:%S`
    MSG="Date and time: ${MAIL_TIME} $MSG"

    DESTINATION='{
      "ToAddresses":  '"$SES_MAIL_TO"',
      "CcAddresses":  [],
      "BccAddresses": []
       }'

    MESSAGE='{
       "Subject": {
           "Data": "'"$SUBJECT"'",
           "Charset": "UTF-8"
       },
       "Body": {
           "Text": {
               "Data": "'"$MSG"'",
               "Charset": "UTF-8"
           },
           "Html": {
               "Data": "'"$MSG"'",
               "Charset": "UTF-8"
           }
       }
    }';

    AWS_ACCESS_KEY_ID=$SES_AWS_ACCESS_KEY_ID \
    AWS_SECRET_ACCESS_KEY=$SES_AWS_SECRET_ACCESS_KEY \
    aws ses send-email --from "$SES_MAIL_FROM" --destination "$DESTINATION" --message "$MESSAGE" --region "us-west-2"
}

#make local storage directory if do not exists
mkdir -p ${LOCAL_STORAGE_PATH};

#change directory to local storage
cd ${LOCAL_STORAGE_PATH};

#make mysql dump and collect output
MYSQL_OUTPUT=$((mysqldump --host ${MYSQL_HOST} --port ${MYSQL_PORT} -u ${MYSQL_USER} --password="${MYSQL_PASS}" ${MYSQL_DB} > ${MYSQL_DUMP_NAME} ) 2>&1);

LOG_MESSAGE=""

#check if mysql dump went ok
if [[ "$MYSQL_OUTPUT" != *"error"* ]]; then
  LOG_MESSAGE="MySql database dump is successfully saved on local storage"

  #compress sql file
  gzip ${MYSQL_DUMP_NAME}

  #encode compressed sql file
  gpg --yes --batch --passphrase=${GPG_PASSPHRASE} -c ${MYSQL_DUMP_NAME}.gz

  #upload compressed encoded sql file to AWS
  AWS_OUTPUT=$(( \
      AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID} \
      AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY} \
      AWS_DEFAULT_REGION=${AWS_DEFAULT_REGION} \
      aws s3 cp ${MYSQL_DUMP_NAME}.gz.gpg s3://${S3_BUCKET}
  ) 2>&1 );

  #remove from local compressed encoded sql file
  rm ${MYSQL_DUMP_NAME}.gz.gpg

  #check if AWS upload went ok
  if [[ "AWS_OUTPUT" != *"error"* ]]; then
    LOG_MESSAGE="$LOG_MESSAGE and uploaded to S3. ";
    echo $LOG_MESSAGE;
    send_ses_mail "Backup success" "$LOG_MESSAGE"
  else
    LOG_MESSAGE="$LOG_MESSAGE. Error occurred while uploading database dump: $AWS_OUTPUT"
    echo $LOG_MESSAGE;
    send_ses_mail "AWS upload error" "$LOG_MESSAGE"
  fi

else
  #delete empty sql file
  rm ${MYSQL_DUMP_NAME};
  LOG_MESSAGE="Error occurred while making database mysql dump: $MYSQL_OUTPUT";
  echo $LOG_MESSAGE;
  send_ses_mail "MySql dump error" "$LOG_MESSAGE"
fi


#remove compresed sql files older than days in config
find ${LOCAL_STORAGE_PATH} -type f -mtime "$REMOVE_LOCAL_BACKUP_AFTER_DAYS" -name '*.sql.gz' -execdir rm -- '{}' \;
