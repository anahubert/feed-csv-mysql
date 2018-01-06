#!/bin/bash
# @author: hubert aleksandra
# @package: feeds

export APP_ENV=production
export DATAPATH=/var/www/vhosts/data/feeds
export PROGPATH=/var/www/vhosts/app

PROGNAME=`basename $0 .sh`
LOCKFILE=${DATAPATH}/lock/feed-${PROGNAME}.lock
SH=`which sh`
SENDMAIL=`which sendmail`
IMPORTDATE=`date`
DAY=`date +%F`
HOUR=`date +%H`
FOR_HOUR=`date +%T`
PHP=`which php`
LOGS=${DATAPATH}/logs
LOGDIR=${LOGS}/${DAY}/${HOUR}
LOGFILE=${DAY}"-"${HOUR}"-feed.log"
LOGPATH=${LOGDIR}/${LOGFILE}

echo ${LOCKFILE}

if [ -f "${LOCKFILE}" ];
then
   echo "${PROGNAME} already running?, can't create lockfile"
   exit 2
fi

trap "rm -f ${LOCKFILE}; exit" INT TERM EXIT

touch ${LOCKFILE}

mkdir -p ${LOGDIR} && touch ${LOGPATH} && cd ${PROGPATH} && echo START > ${LOGPATH} && $PHP ./csvtomysql.php -customer="vulkan" >> ${LOGPATH} && echo DONE >> ${LOGPATH}

if [ $? -ne 0 ]
  then
    INFO="FAILED"
  else
    INFO="OK"
fi

SUBJECT="[Feed csv-mysql] "$INFO" - "$DAY
EMAIL="aleksandra.hubert@gmail.com"
MESSAGE="[Feed csv-mysql] Started at $IMPORTDATE $HOUR."

(
  echo To: $EMAIL
  echo Subject: $SUBJECT
  echo
  cat ${LOGPATH}
) | $SENDMAIL -t

# remove lock file
rm -f ${LOCKFILE}
