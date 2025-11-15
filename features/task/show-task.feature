Feature: Show Task
    As a logged-in user
    I want to show a task in a given project column

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    @javascript
    Scenario: Open task creation modal
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        And I click the "#task-019a8131-642b-7452-a88a-90705e917678" element
        Then I should see "ECO-1" in the "#modal-show-task" element
        And I should see "Backlog" in the "#modal-show-task" element
        And I should see "Task 1" in the "#modal-show-task" element
        And I should see "Description" in the "#modal-show-task" element
        And I should see "Task 1 description" in the "#modal-show-task" element
        And I should see "Priority" in the "#modal-show-task" element
        And I should see "Medium" in the "#modal-show-task" element
        And I should see "Due Date" in the "#modal-show-task" element
        And I should see "Jan 01, 2026" in the "#modal-show-task" element
        And I should see "Column" in the "#modal-show-task" element
        And I should see "Created" in the "#modal-show-task" element
        And I should see "January 01, 2025 at 12:00 AM" in the "#modal-show-task" element
        And I should see "Last Modified" in the "#modal-show-task" element
        And I should see "Created" in the "#modal-show-task" element
        And I should see "Close" in the "#modal-show-task" element
        And I should see "Edit" in the "#modal-show-task" element
        And I should see "Delete" in the "#modal-show-task" element
