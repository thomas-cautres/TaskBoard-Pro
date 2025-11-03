Feature: Archive Project
    As a logged-in user
    I want to archive a project

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    Scenario: Edit project page loads successfully
        When I am on "/app/projects/list"
        And I
