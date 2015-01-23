Moodle Direct Release Notes
------------------------------------------------------------------------------------
Date:       2014-Nov-12
Release:    v2014103102

- Fixed PHP 5.6 CURL file upload

####################################################################################
Date:       2014-Oct-31
Release:    v2014103101

- Added Czech language pack.

####################################################################################
Date:       2014-Sept-08
Release:    v2013111404

- Fixes:
    > Removed incorrect XML in install.xml
    > Changed jQuery include method to use Moodle jQuery where possible
    > 'Anonymous marking enabled' button is disabled if submission was not successful
    > Assignment type is required when creating an assignment, cannot be set to blank
    > Changed logging to use Moodle events where available
    > Fixed many depricated function warnings since the Moodle 2.7 release
    > Changed suggested API URL to api.turnitin.com
    > Added default value for $params in turnitintool_delete_records_select

####################################################################################
Date:       2014-Apr-04
Release:    v2013111403

- Anonymous marking option is locked once a submission is made to any assignment part
- Upgraded jQuery to 1.11.0
- Added cURL CA cert link to INSTALL.html
- Fixes:
    > Improved appearance of errors that appear during refresh submissions
    > Inbox sort on date now works with UK date format dd/mm/yy as well as mm/dd/yy
    > Fix for generic API errors being returned when using non-English language
    > Non-OR capable submissions now show "--" for similarity in assignment inbox
      rather than "0%"
    > Added file check to identify submissions that are missing from disk/corrupt in
      Moodle and remove the associated submission

####################################################################################
Date:       2014-Feb-26
Release:    v2013111402

- Fixes for apostrophes in Moodle 1.9

####################################################################################
Date:       2013-Nov-14
Release:    v2013111401

- In anonymous marking assignments, inbox displays in part view until all
  parts have passed the post date.
- Namespaced Turnitin JavaScript to avoid overwriting by other jQuery includes
- Polish and Russian strings added to language packs
- Help icons added for Turnitin advanced options
- Resubmission is now possible when reports generated on due date
- Fixes:
    > Submission inbox catches errors, no longer refreshes indefinitely
    > Refresh inbox row button now updates gradebook
    > Various fixes to SQL queries for different database types
    > Fixed default grade score
    > Fixed error when writing to logs

####################################################################################
Date:       2012-Dec-04
Release:    v2012120401

- Refactored Submission inbox views, jQuery filtering implemented to reduce database requests
- Refactored 'Enroll All Students' and 'Refresh Submissions' to use ajax
- Optimised database queries on inbox views and refresh submissions calls
- Added more granular logging, now logging submission add and delete, assignment add, delete and update
- Added a submission event handler (for Moodle 2+ only)

####################################################################################
Date:       2012-Nov-04
Release:    v2012110401

- Moodle Direct now requires PHP 5.0 server environments and above
- Added performance improvements, specifically in-box refresh on large assignments
- Added time-zone synchronisation improvements

####################################################################################
Date:       2012-Sept-24
Release:    v2012092401

- Added support for Translated Matching
- Update icons with current Turnitin icons, update icons to allow multi version support
- Added a config level mod_turnitintool component file browser when using Moodle 2.0+ for mod_turnitintool
- Re-factored Unlink / Relink users page to paginate user data and reduce memory usage

####################################################################################
Date:       2011-Aug-18
Release:    v2011081801

- Refactored Back up and restore to allow duplication of TII classes and assignments
- Added erater / ETS support
- Added additional email notification options in the admin config screen

####################################################################################
Date:       2011-July-29
Release:    v2011072901

- Added support Bulk Download of Submissions in PDF and Original format
- Added feature to download grade report XLS spreadsheet
- Added Multi tutor management screen

####################################################################################
Date:       2010-Nov-19
Release:    v2010111901

- Added support for Moodle groups

####################################################################################
Date:       2010-Oct-26
Release:    v2010102601

- Added pagination to the inbox
- Updated database fields and tables for Oracle support
- Added exclude small matches global assignment setting
- Added support for multi language api calls
- Added French (fr) language string file
- Fixes:
    > Fixed issue where non enrolled students were not displayed in the tutor inbox view
    > Fixed issue where user's resubmissions where incorrectly tagged as anonymous
    > Fixed issue with incorrect / incomplete ordering of anonymous inbox

####################################################################################
Date:       2010-Sept-01
Release:    v2010090101

- Added various changes to add compatibility for Moodle 2.0
    > Refactored table output to support both Moodle 1.9 - 2.0
    > Updated language pack, incorporated help into standard language strings
    > Updated Javascript and CSS file functionality
    > Added Moodle 2.0 Back Up and Restore
    > Changed file storage to use Moodle 2.0 file storage where available
    > Moved images to 'pix' directory instead of 'images'
- Added Backup and Restore for Moodle 1.9

####################################################################################
Date:       2010-June-19
Release:    v2010061901

- Added additional diagnostic logging
- Added Authenticated Proxy support

####################################################################################
Date:       2010-June-12
Release:    v2010061201

- Refactored Inbox SQL queries

####################################################################################
Date:       2010-June-2
Release:    v2010060201

- Added support for UTF-8 intepretation of API return data

####################################################################################
Date:       2010-April-23
Release:    v2010042301

- Removed redundant assignment synching cron functionality
- Now allows resubmission to the same paper ID

####################################################################################
Date:       2010-April-06
Release:    v2010040601
 
- Provides seamless integration into Turnitin using Moodle workflow 
- Uses an activity module so that we can update Turnitin independently of Moodle 
- Uses real Turnitin accounts to allow users to log directly in to Turnitin (should they need to) 
- Uses a 'pull' approach to information and has no 'call-backs' to the local VLE 
- Will run behind a fire wall 
- Will handle multi-part assignments (one assignment many files) 
- Sends GradeMark marking information to the Moodle GradeBook 
- Grades are not released until the due date 
- Course recycle will correctly copy forward Turnitin information 
- Tutors can load work on behalf of students 
- Turnitin classes can only have one owner. The class owner is set to the person that created the course in Moodle. Only the class owner will be able to see the assignments when logging in to Native Turnitin. However you can change the class owner from within Moodle if you are an instructor. 

Current Limitations
- Does not support Oracle as the Moodle database
- Will not update information changed in Turnitin by users in native Turnitin 
- Will not work with assignments created under the framed-in API 
- No support for revision assignment, master classes, GradeMark analytics, translations, Zip file upload, PeerMark, QuickSubmit