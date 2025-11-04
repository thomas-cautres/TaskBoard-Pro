Feature: Show Project
    As a logged-in user
    I want to view a project's details
    So that I can see tasks, statistics, and project information

    Background:
        Given I am logged in as "user-confirmed@domain.com"

    Scenario: Project page loads successfully
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then the response status code should be 200

    Scenario: Project page displays all content
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see "E-Commerce Platform Redesign" in the "h1" element
        And I should see a ".breadcrumb" element
        And I should see "E-Commerce Platform Redesign" in the ".breadcrumb .breadcrumb-item.active" element
        And I should see a ".badge-type" element
        And I should see "SCRUM" in the ".badge-type" element
        And I should see a ".badge-status" element
        And I should see "ACTIVE" in the ".badge-status" element
        And I should see a ".project-header .text-muted" element

    Scenario: Project page displays statistics cards
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see a ".stat-card.primary" element
        And I should see "Total Tasks" in the ".stat-card.primary" element
        And I should see a ".stat-card.warning" element
        And I should see "In Progress" in the ".stat-card.warning" element
        And I should see a ".stat-card.success" element
        And I should see "Completed" in the ".stat-card.success" element
        And I should see a ".stat-card.info" element
        And I should see "Progress" in the ".stat-card.info" element

    Scenario: Project page displays kanban board
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see a ".kanban-board" element
        And I should see a ".kanban-column" element

    Scenario: Project page displays action buttons
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see an "a.btn.btn-outline-primary" element
        And I should see "Edit" in the "a.btn.btn-outline-primary" element

    Scenario: Accessing non-existent project returns 404
        When I am on "/app/project/undefined"
        Then the response status code should be 404

    Scenario: User without access to project is forbidden
        Given I am logged in as "user2-confirmed@domain.com"
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then the response status code should be 403
