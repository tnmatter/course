#!/bin/bash
set -e

echo $PG_USER
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
    CREATE USER $PG_USER with password '$PG_PASSWORD';
    ALTER ROLE $PG_USER WITH LOGIN;
    CREATE DATABASE $PG_DB OWNER $PG_USER;
    GRANT ALL ON SCHEMA public TO $PG_USER;
    GRANT ALL PRIVILEGES ON DATABASE $PG_DB TO $PG_USER;
EOSQL