Feature: Create Task
    As a logged-in user
    I want to create a task in a given project column

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    Scenario: Create project page loads successfully
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I press "column-0-add-task-btn"
        Then I should see "Create Task"
