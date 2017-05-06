#!/usr/bin/env bash

#generate entities from My Workbench SQL model into /application/entity/
#echo '\n\n------------------ Converting My SQLWorkbench model to Entities -------------------------\n'
php vendor/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter/bin/mysql-workbench-schema-export --config=schema/export.json schema/fit-grid.mwb application/entity/

#drop DB
#echo '\n\n------------------ Dropping old DB Schema ----------------------------\n'
vendor/bin/doctrine orm:schema-tool:drop --force

#create DB from Entities in /application/entity/
#echo '\n\n------------------ Creating new DB Schema----------------------------\n'
vendor/bin/doctrine orm:schema-tool:create

#generate database data
#echo '\n\n------------------ Generating Demo data-------------------------------\n'
php generator.php generate