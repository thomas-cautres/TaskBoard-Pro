Feature: List Projects
    As a logged-in user
    I want list my projects
    So that I can organize and manage tasks

    Background:
        Given I am logged in as "user-confirmed@domain.com"
        And I am on "/app/projects"

    Scenario: List projects successfully
        Then the response status code should be 200
        And I should see "Home"
        And I should see "My Projects"
        And I should see 4 ".stat-card" elements
        And I should see "Active Projects"
        And I should see "Tasks Completed"
        And I should see "In Progress"
        And I should see "Collaborators"
        And I should see 12 ".project-card" elements
        And the ".page-title .badge-count" element should contain "30"
        And I should see "Showing 1 -12 of 30 projects"


    Scenario: Filter by name successfully
        When I fill in "filters_name" with "cloud"
        And I press "filters_submit"
        Then I should see 1 ".project-card" elements
        And I should see "Cloud Migration Initiative"
        And I should see "Showing 1 -1 of 1 projects"

    Scenario: Filter by type successfully
        When I select "scrum" from "filters_type"
        And I press "filters_submit"
        Then I should see 12 ".project-card" elements
        And I should see "Showing 1 -12 of 17 projects"

    Scenario: Filter by active successfully
        When I select "2" from "filters_active"
        And I press "filters_submit"
        Then I should see 12 ".project-card" elements
        And I should see "Showing 1 -12 of 30 projects"

    Scenario: Sort successfully
        When I select "1" from "filters_sort"
        And I press "filters_submit"
        Then I should see 12 ".project-card" elements
        And I should see "Showing 1 -12 of 30 projects"

    Scenario: Paginate successfully
        When I select "2" from "filters_active"
        And I press "filters_submit"
        And I follow "page-2"
        Then I should see "Showing 13 -24 of 30 projects"
        And I should see 12 ".project-card" elements
        When I follow "page-3"
        Then I should see "Showing 25 -30 of 30 projects"
        And I should see 6 ".project-card" elements
        When I follow "page-prev"
        Then I should see "Showing 13 -24 of 30 projects"
        When I follow "page-next"
        Then I should see "Showing 25 -30 of 30 projects"

