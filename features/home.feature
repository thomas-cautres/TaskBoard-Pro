Feature: Home Page
    As a visitor
    I want to view the home page
    So that I can learn about TaskBoard Pro and access key features

    Scenario: Home page loads successfully
        When I am on "/"
        Then the response status code should be 200

    Scenario: Home page displays all content
        Given I am on "/"
        Then I should see "Home"
        And I should see "Login"
        And I should see "Register"
        And I should see "Manage your projects efficiently"
        And I should see "TaskBoard Pro helps you organize your projects and tasks with ease. Collaborate, plan and succeed together."
        And I should see "Start for free"
        And I should see "Log in"
        And I should see "Key features"
        And I should see "Project management"
        And I should see "Create and organize your projects easily with an intuitive interface."
        And I should see "Task tracking"
        And I should see "Assign and track the progress of each task in real time."
        And I should see "Collaboration"
        And I should see "Work as a team and share your projects with your collaborators."
        And I should see "© 2025 TaskBoard Pro. All rights reserved."

    Scenario: Navigation login link works
        Given I am on "/"
        When I follow "Login"
        Then I should be on "/login"
        And the response status code should be 200

    Scenario: Hero section start button works
        Given I am on "/"
        When I follow "Start for free"
        Then I should be on "/registration"
        And the response status code should be 200

