@javascript @api
Feature: Add Static Media file
  As a Site Admin
  I am able to log in
  And publish content on the site

  Scenario Outline: Test log in for 2 accounts
    Given I need to login
    And I visit "/user/login/"
    And I fill in "<name>" for "name"
    And I fill in "<pass>" for "pass"
    And I press "Login" with "Log in"
    Then I should see "vaibhavkumar.patel"
  
    Examples:
      | name | pass |
      | vaibhavkumar.patel | Manubhai291290 |

Scenario: As an Administrator I should be able to create Media content
  Given I am logged in as a user with the "administrator" role
  When I visit "media/add/document"
   And I select "ebook" from "Static File Path"
   And I select "Static File" from "Static File Type"
   And I fill in "Media name" with "My test media"
   And I select "AP" from "Organization"
   And I select "About IRS" from "Channel"
   And I select "Corporate" from "Focus Area"
   And I attach the file "puppy.jpg" to "File Upload"
   And I wait for AJAX to finish
   And I press "Save as unpublished"
  Then I should see "test media."

Scenario: As an Administrator I should be able to publish media
  Given I am logged in as a user with the "administrator" role
  When I visit "media/add/document"
   And I select "ebook" from "Static File Path"
   And I select "Static File" from "Static File Type"
   And I fill in "Media name" with "My test media"
   And I select "AP" from "Organization"
   And I select "About IRS" from "Channel"
   And I select "Corporate" from "Focus Area"
   And I attach the file "puppy.jpg" to "File Upload"
   And I wait for AJAX to finish
   And I press "Save as publish"
   Then I should see "test media."