Feature: Create Task
    As a logged-in user
    I want to create a task in a given project column

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    @javascript
    Scenario: Open task creation modal
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I press "Add a task"
        Then I should see "Create Task"
        And I should see "Task will be created in column : Backlog"

    @javascript
    Scenario: Submit modal creation form
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I press "Add a task"
        And I fill in "create_task[title]" with "Title task"
        And I fill in "create_task[description]" with "Description task"
        And I select "0" from "create_task[priority]"
        And I fill in "create_task[endDate]" with "2026-01-01"
        And I press "Create Task"
        Then I should be on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I should see "Task created successfully"
        And I should see "Title task" in the "#column-0" element
        And I should see "ECO-1" in the "#column-0" element
        And I should see "LOW" in the "#column-0" element
        And I should see "Jan 01, 2026" in the "#column-0" element
