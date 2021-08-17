# Change Log
All notable changes to this project will be documented in this file.

## [v4.3.0]
#### Added
- Add statistics view for 3 months

#### Fixed
- Change aggregation to deal with extra columns
- Use absolute URL instead of relative URLs on some places
- Correct dependencies

## [v4.2.2]
#### Fixed
- Allow to get SP name from UIInfo>DisplayName if isset

## [v4.2.1]
#### Fixed
- Fixed bad check for MULTI_IDP mode

## [v4.2.0]
#### Added
- Added MULTI_IDP mode

## [v4.1.0]
#### Removed
- Removed logging info about login in Statistics Process filter
    * For storing this log please use filter in module [Perun].
    
[Perun]: "https://github.com/CESNET/perun-simplesamlphp-module/blob/master/lib/Auth/Process/LoginInfo.php"

## [v4.0.0]
#### Added
- aggregated statistics (logins and unique users)

#### Changed
- new database tables
- config options for table names replaced with one (optional) option `tableNames`
- option 'config' for auth proc filter made optional
- auth proc filter renamed to Statistics (PascalCase)
- major refactoring

#### Removed
- `detailedDays` config option
- compatibility for deprecated database config options
- duplicate code

## [v3.2.1]
#### Fixed
- Fixed the bug in using double '$'

## [v3.2.0]
#### Added
- Added possibility to show statistics only after authentication

#### Changed
- Remove unnecessary is_null()
- Use SimpleSAML\Database

#### Fixed
- Log info message about successful authentication only after successful authentication to SP
- Correct log message in insertLogin()
- Update README.md
    - describe setup for modes PROXY/SP/IDP
    - change array notation from `array()` to `[]`
- Read spName from $request only if present
- Remove unused indexes
- Optimize left outer join
- Don't double queries w/o days
- Fixed the table header in detailed statistics for SP

## [v3.1.0]
#### Added
- Added configuration file for ESLint
- Module now supports running statistics as IDP/SP
- Store detailed statistics(include some user identifier) for several days 

#### Changed
- Using of short array syntax (from array() to [])
- Specify engine and default charset in tables.sql
- Removed unused include from 'templates/spDetail-tpl.php'
- Deleted useless code
- Deleted 'head' and 'body' tag in tab templates
- Use 'filter_input' to GET and VALIDATE value send as GET/POST param
- Eliminate inline javascript
    - All JS code was moved to 'index.js'
- Using 'fetch_all' instead of 'fetch_asoc' to get data from DB
- Set default values for some option in 'DatabaseConnector.php'
- Remove duplicate code from 'DatabaseConnector.php'
- Move duplicate code for timeRange to separate file
- Use import instead of unnecessary qualifier

#### Fixed
- Fixed the syntax of CHANGELOG
- Fixed SQL injection vulnerability
- Fixed list of required packages

## [v3.0.0]
#### Added
- Added file phpcs.xml

#### Fixed
- Fixed the problem with generating error, when some of attributes 'eduPersonUniqueId', 'sourceIdPEppn', 'sourceIdPEntityId' is null 

#### Changed
- Changed code style to PSR-2
- Module uses namespaces

## [v2.1.0]
#### Added
- Every successfully log in is logged with notice level 

## [v2.0.0]
#### Added
- Added details with statistics for individually SPs and IdPs
- Added script for migrate data to new version of database structure

## [v1.5.0]
#### Added
- Added legends to charts
- Instance name in header is taken from config file

#### Fixed
- set default value of lastDays and tab in index.php: no error logs when user open statistics for the first time

## [v1.4.1]
#### Fixed
- Statistics will be now full screen
- Fixed bad checks before insert translation to db

## [v1.4.0]
#### Added
- Possibility to change the time range of displayed data

#### Changed
- DB commands work with apostrophes in IdP/SP names
- New visual form of the site
- Draw tables without month

#### Fixed
- Draws tables data by selected time range

#### Removed
- Removed unused functions

## [v1.3.0]
#### Added
- Added mapping tables for mapping identifier to name

#### Changed
- Storing entityIds instead of SpName/IdPName. 

#### Fixed
- Used only tabs for indentations

## [v1.2.1]
#### Fixed
- Fixed the problem with getting utf8 chars from database

## [v1.2.0]
#### Added
- Classes SimpleSAML_Logger and SimpleSAML_Module renamed to SimpleSAML\Logger and SimpleSAML\Module
- Dictionary
- Czech translation

#### Changed
- Database commands use prepared statements
- Saving SourceIdPName instead of EntityId

## [v1.1.0]
#### Added
- Added average and maximal count of logins per day into summary table

#### Changed
- Fixed overqualified element in statisticsproxy.css

## [v1.0.0]
#### Added
- Changelog

[Unreleased]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/master
[v4.3.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v4.3.0
[v4.2.2]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v4.2.2
[v4.2.1]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v4.2.1
[v4.2.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v4.2.0
[v4.1.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v4.1.0
[v4.0.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v4.0.0
[v3.2.1]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v3.2.1
[v3.2.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v3.2.0
[v3.1.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v3.1.0
[v3.0.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v3.0.0
[v2.1.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v2.1.0
[v2.0.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v2.0.0
[v1.5.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.5.0
[v1.4.1]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.4.1
[v1.4.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.4.0
[v1.3.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.3.0
[v1.2.1]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.2.1
[v1.2.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.2.0
[v1.1.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.1.0
[v1.0.0]: https://github.com/CESNET/proxystatistics-simplesamlphp-module/tree/v1.0.0
