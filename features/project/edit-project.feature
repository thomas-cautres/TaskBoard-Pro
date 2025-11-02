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
        And the "edit_project_name" field should contain "E-Commerce Platform Redesign"
        And the "edit_project_description" field should contain "Complete overhaul of the customer-facing e-commerce platform with improved UX and mobile responsiveness"
        And the "edit_project_startDate" field should contain "2025-01-15"
        And the "edit_project_endDate" field should contain "2025-06-30"
        And I should see "Organize your work with the methodology that suits you"
        And I should see "Project name *"
        And I should see "Choose a unique and descriptive name"
        And I should see "Optional - You can modify it later"
        And I should see "Cannot change project type when tasks exist"
        When I fill in "edit_project_name" with "Example name"
        And I press "edit-btn"
        Then the project "Example name" should exist in database

