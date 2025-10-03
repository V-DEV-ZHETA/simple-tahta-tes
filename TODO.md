# TODO: Remove Filament Shield Plugin

## Steps to Complete
- [x] Remove "bezhansalleh/filament-shield" from composer.json
- [x] Remove FilamentShieldPlugin::make() from app/Providers/Filament/AdminPanelProvider.php
- [x] Delete config/filament-shield.php
- [x] Delete app/Policies/RolePolicy.php
- [x] Delete check_super_admin_permissions.php
- [x] Run composer update to remove the package
- [x] Clear application cache if needed
