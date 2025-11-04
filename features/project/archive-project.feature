Feature: Archive Project
    As a logged-in user
    I want to archive a project

    @javascript
    Scenario: Archive and restore project successfully
        Given I am logged in as "user-confirmed@domain.com"
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I press "dropdown"
        And I follow "Archive"
        And I should see "Archive Project"
        And I should see "Are you sure you want to archive this project?"
        And I should see "What will happen:"
        And I should see "The project will be hidden from the default list"
        And I should see "You can restore it at any time"
        And I should see "Existing tasks will be preserved as read-only"
        And I should see "Cannot create new tasks"
        And I press "Archive Project"
        Then I should be on "/app/projects"
        And the project "E-Commerce Platform Redesign" should have status "archived"
        And I should see "Project archived successfully"
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I press "dropdown"
        And I follow "Restore"
        And I should see "Restore Project"
        And I should see "Are you sure you want to restore this project?"
        And I should see "What will happen:"
        And I should see "The project will be visible in the default list"
        And I should see "You will be able to create new tasks again"
        And I should see "The project status will change to Active"
        And I press "Restore Project"
        Then I should be on "/app/projects"
        And I should see "Project restored successfully"

