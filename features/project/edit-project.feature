Feature: Edit Project
    As a logged-in user
    I want to edit a project
    So that I can organize and manage tasks

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    Scenario: Edit project page loads successfully
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592/edit"
        Then the response status code should be 200
        And I should see "Projects"
        And I should see "E-Commerce Platform Redesign"
        And I should see "Edit project"
        And the "create_project_name" field should contain "E-Commerce Platform Redesign"

