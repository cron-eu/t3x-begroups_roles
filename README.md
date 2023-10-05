# TYPO3 Extension begroups_roles

[![Latest Stable Version](https://img.shields.io/packagist/v/cron-eu/begroups-roles.svg)](https://packagist.org/packages/cron-eu/begroups-roles)
[![StyleCI](https://styleci.io/repos/699370966/shield?branch=master)](https://styleci.io/repos/699370966)

Use backend user groups as switchable roles

Note: this is a fork from https://github.com/IchHabRecht/begroups_roles
with improvements needed for cron-eu projects, most notably TYPO3 v11
support.

![Role switcher](Documentation/Images/role_switcher.png)

## Installation

Simply install the extension with Composer or the Extension Manager.

```
composer require cron-eu/begroups-roles
```

## Usage

1. Add multiple backend groups, each for one purpose
   - Tick the checkbox `Use this group as role` 
   - Limit the modules, tables and database mount to the purpose
   
2. For convenience add the created groups to a parent group 

3. Assign the created (parent) group to backend users
   - Tick the checkbox `Use groups as roles`
   - To allow only one role group simultaneously, tick the checkbox `Restrict to one group`
