# Upgrade Guide v1 to v2

### Migrations
###### Impact: High
Migrations will no longer be automatically ran by the packages service provider, the migrations will now need to be published using this command:
```
php artisan publish --tag="roles-migrations"
```
If you haven't already migrated the tables then run `php artisan migrate` to ensure the tables are created.

### Configuration
###### Impact: Low
The config file has been updated to now include the option to define what class is used for the Role model. So if you ever need to customise the role model you can make your own and reference it in the config. 

If you wish to customise the model used for roles then you must publish the config using this command:
```
php artisan publish --tag="roles-config"
```