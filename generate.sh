#!/usr/bin/env bash

#generate entities from My Workbench SQL model into /application/entity/
php vendor/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter/bin/mysql-workbench-schema-export --config=schema/export.json schema/fit-grid.mwb application/entity/

#drop DB
vendor/bin/doctrine orm:schema-tool:drop --force

#create DB from Entities in /application/entity/
vendor/bin/doctrine orm:schema-tool:create

#generate database data
php generator.php generate