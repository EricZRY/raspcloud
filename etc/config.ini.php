<?php exit();?>
;/**
; * Created by PhpStorm.
; * User: light
; * Date: 2015/10/25
; * Time: 12:44
; */

[global]
shutdown="false"
shutdown_end_ts=0
;应用运行的时区
timezone="Asia/Shanghai"

default_db_server="mysqli://root:root@localhost/?charset=utf8"
default_db_slave="mysqli://root:root@localhost/?charset=utf8"
default_cache_server=localhost:11211
default_redis_server=localhost:6379

scribe_server_host=localhost
scribe_server_port=8081

app_name=raspcloud

; 日志的存储媒介
log_storage=file
; 日志的级别,0=ELEX_LOG_OFF,1=ELEX_LOG_DEBUG,2=ELEX_LOG_INFO,3=ELEX_LOG_ERROR,4=ELEX_LOG_FATAL
log_level_model=3
log_level_framework=3
log_level_database=3
log_level_cache=3
log_level_other=1
log_level_cron=3
log_level_api=3
log_level_amf_request=3
log_level_transaction=3

[global_config]
db_name=rc_global
deploy=0
db_server_config=ra
no_cache=0

[admin_user]
db_name=rc_global
deploy=0
db_server_config=ra
no_cache=1