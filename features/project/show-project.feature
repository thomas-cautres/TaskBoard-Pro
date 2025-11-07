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
        Then I should see "E-Commerce Platform Redesign" in the "h1.h2" element
        And I should see a ".breadcrumb" element
        And I should see "E-Commerce Platform Redesign" in the ".breadcrumb .breadcrumb-item.active" element
        And I should see "SCRUM"
        And I should see "ACTIVE"
        And I should see a ".text-muted.mb-3.fs-6" element

    Scenario: Project page displays statistics cards
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see a ".card.border-0.shadow-sm" element
        And I should see "Total Tasks" in the ".col-md-3:nth-child(1) .card" element
        And I should see a ".bi-list-check" element
        And I should see "In Progress" in the ".col-md-3:nth-child(2) .card" element
        And I should see a ".bi-hourglass-split" element
        And I should see "Completed" in the ".col-md-3:nth-child(3) .card" element
        And I should see a ".bi-check-circle" element
        And I should see "Progress" in the ".col-md-3:nth-child(4) .card" element
        And I should see a ".bi-graph-up" element
        And I should see a ".progress" element

    Scenario: Project page displays kanban board
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see a "#board" element
        And I should see a ".d-flex.gap-3.overflow-auto" element
        And I should see "Board" in the ".nav-tabs" element

    Scenario: Project page displays tabs
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see "Board" in the ".nav-tabs .nav-link.active" element
        And I should see "Task List" in the ".nav-tabs" element
        And I should see "Activity" in the ".nav-tabs" element
        And I should see "Sprints" in the ".nav-tabs" element

    Scenario: Project page displays action buttons
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see an "a.btn.btn-outline-primary" element
        And I should see "Edit" in the "a.btn.btn-outline-primary" element
        And I should see a "button.btn.btn-outline-secondary[data-bs-toggle='dropdown']" element

    Scenario: Project page displays project metadata
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see a ".bi-calendar3" element
        And I should see "Start:" in the ".row.g-3.text-muted" element
        And I should see "End:" in the ".row.g-3.text-muted" element
        And I should see "Created on:" in the ".row.g-3.text-muted" element
        And I should see a ".bi-people" element

    Scenario: Project page displays badges
        Given I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then I should see a ".badge.bg-primary.rounded-pill" element
        And I should see a ".badge.bg-info.rounded-pill" element
        And I should see a ".badge.bg-success.rounded-pill" element

    Scenario: Accessing non-existent project returns 404
        When I am on "/app/project/undefined"
        Then the response status code should be 404

    Scenario: User without access to project is forbidden
        Given I am logged in as "user2-confirmed@domain.com"
        When I am on "/app/project/019a2646-0166-70fc-80c2-0ddbc097a592"
        Then the response status code should be 403
