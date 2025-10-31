Feature: Create Project
    As a logged-in user
    I want to create a new project
    So that I can organize and manage tasks

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    Scenario: Create project page loads successfully
        When I am on "/app/project/create"
        Then the response status code should be 200
        And I should see "Projects"
        And I should see "Create a project"
        And I should see "Create a new project"

    Scenario: Successfully create a Scrum project
        Given I am on "/app/project/create"
        When I fill in "create_project[name]" with "Project Scrum Test"
        And I fill in "create_project[description]" with "Project description for Scrum"
        And I select "scrum" from "create_project[type]"
        And I fill in "create_project[startDate]" with "2025-01-01"
        And I fill in "create_project[endDate]" with "2025-02-01"
        And I press "submit-btn"
        Then the project "Project Scrum Test" should exist in database
        And the project "Project Scrum Test" should have type "scrum"
        And the project "Project Scrum Test" should have 5 columns
        And the project "Project Scrum Test" should have column "Backlog" at position 0
        And the project "Project Scrum Test" should have column "To do" at position 1
        And the project "Project Scrum Test" should have column "In progress" at position 2
        And the project "Project Scrum Test" should have column "Review" at position 3
        And the project "Project Scrum Test" should have column "Done" at position 4

    Scenario: Successfully create a Kanban project
        Given I am on "/app/project/create"
        When I fill in "create_project[name]" with "Project Kanban Test"
        And I fill in "create_project[description]" with "Project description for Kanban"
        And I select "kanban" from "create_project[type]"
        And I fill in "create_project[startDate]" with "2025-01-01"
        And I fill in "create_project[endDate]" with "2025-02-01"
        And I press "submit-btn"
        Then the project "Project Kanban Test" should exist in database
        And the project "Project Kanban Test" should have type "kanban"
        And the project "Project Kanban Test" should have 3 columns
        And the project "Project Kanban Test" should have column "To do" at position 0
        And the project "Project Kanban Test" should have column "In progress" at position 1
        And the project "Project Kanban Test" should have column "Done" at position 2

    Scenario: Successfully create a Basic project
        Given I am on "/app/project/create"
        When I fill in "create_project[name]" with "Project Basic Test"
        And I fill in "create_project[description]" with "Project description for Basic"
        And I select "basic" from "create_project[type]"
        And I fill in "create_project[startDate]" with "2025-01-01"
        And I fill in "create_project[endDate]" with "2025-02-01"
        And I press "submit-btn"
        Then the project "Project Basic Test" should exist in database
        And the project "Project Basic Test" should have type "basic"
        And the project "Project Basic Test" should have 2 columns
        And the project "Project Basic Test" should have column "Open" at position 0
        And the project "Project Basic Test" should have column "Closed" at position 1
