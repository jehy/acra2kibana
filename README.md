# acra2kibana
Saves Android ACRA logs for later usage with kibana. Logstash can't process keys with dots so dots are replaced with "_" characters.

Your `/web` directory must be accessible for external application. Reporting script uses config file `acra2kibana.json`.

Possible config options are:
 + `logDir` - where your log should be stored.