## routes/tenant.php
## END OF routes/tenant.php
## resources/css/app.css
## END OF resources/css/app.css
## resources/views/components/layouts.blade.php
## END OF resources/views/components/layouts.blade.php
## app/Livewire/Tenants/Welcome.php
## END OF app/Livewire/Tenants/Welcome.php
## routes/web.php
## END OF routes/web.php
## tailwind.config.js
## END OF tailwind.config.js
.example
## resources/views/components/layouts/app.blade.php
## END OF resources/views/components/layouts/app.blade.php
## resources/views/livewire/tenants/manage-tenants.blade.php
## END OF resources/views/livewire/tenants/manage-tenants.blade.php
## app/Models/Tenant.php
## END OF app/Models/Tenant.php
## app/Livewire/Tenants/ManageTenants.php
## END OF app/Livewire/Tenants/ManageTenants.php
## app/Livewire/Tenants/ViewTenants.php
## END OF app/Livewire/Tenants/ViewTenants.php
## resources/views/livewire/tenants/welcome.blade.php
## END OF resources/views/livewire/tenants/welcome.blade.php
## README.md
## END OF README.md
## config/app.php
## END OF config/app.php
## app/Models/User.php
## END OF app/Models/User.php
## .env
## END OF .env
## database/migrations/2024_04_13_144321_create_permission_groups_table.php
## END OF database/migrations/2024_04_13_144321_create_permission_groups_table.php
## database/migrations/2024_04_13_144334_add_rows_to_permissions_table.php
## END OF database/migrations/2024_04_13_144334_add_rows_to_permissions_table.php
## app/Models/PermissionGroup.php
## END OF app/Models/PermissionGroup.php
## database/seeders/DatabaseSeeder.php
## END OF database/seeders/DatabaseSeeder.php
## database/seeders/PermissionsSeeder.php
## END OF database/seeders/PermissionsSeeder.php
## app/Models/Role.php
## END OF app/Models/Role.php
## database/migrations/tenant/2024_04_13_144334_add_rows_to_permissions_table.php
## END OF database/migrations/tenant/2024_04_13_144334_add_rows_to_permissions_table.php
## text.txt
## END OF text.txt
## resources/views/livewire/tenants/view-tenants.blade.php
## END OF resources/views/livewire/tenants/view-tenants.blade.php
## app/Console/Commands/UpdatePermissionsCommand.php
## END OF app/Console/Commands/UpdatePermissionsCommand.php

## app/Models/Permission.php

## END OF app/Models/Permission.php


//
under permissions 
get a way to flip through tenants before seeding!

also on the hard defined permissions loop through then detect any changes then updating the cental db on packages/modules table(to be created)
on tenants migration add a colum for active package_id
then add package_id to each permissions 

on comparing, make sure that the user can have all permissions under them  eg: if one has 3, then he/she can have 1 and 2

//
