Feature: Show Task
    As a logged-in user
    I want to show a task in a given project column

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    @javascript
    Scenario: Open task creation modal
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I press "Add a task"
