
/**
 *  @file
 *  README for the IRS File Operation.
 */

This module change the Drupal behaviour. Typical Drupal behavior is to rename files on upload to <filename>_0.<ext>
This module modifies that behavior.

1.0 - Behavior is as follows:
 If the same file exist in folder. replace the new file with older one.


################################################################################
USAGE
################################################################################

1.Enable the irs_file_operation module on the modules page.
2.Your done

#######################
Required Field:
#######################

    1. field_ocms_file_public_path
    2. field_document


How its works  
1) Login as a Admin or Media User
2) Click on Content Menu
3) Click on Media Tab.
4) Click on 'Add Media'
5) Select Folder Repository
5) Select Document to Upload file
6) Choose file and Hit Save and Publish
4) By default media is stored in the private folder when the media is unpublished. If the user published the media, it will move from private to public directory.
Also instead of renaming the file it will replace any existing file with the same name in the same directory.

