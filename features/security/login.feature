Feature: Login
    As a user
    I want to log into my account
    So that I can access my TaskBoard Pro workspace

    Scenario: Login page loads successfully
        When I am on "/login"
        Then the response status code should be 200

    Scenario: Login page displays all content
        Given I am on "/login"
        Then I should see "TaskBoard Pro"
        And I should see "Access your space"
        And I should see "Email"
        And I should see "Password"
        And I should see "Remember me"
        And I should see "Sign in"
        And I should see "Forgot password?"
        And I should see "Don't have an account yet?"
        And I should see "Sign up"
        And I should see "Back to home"

    Scenario: Successful login with valid credentials
        Given I am on "/login"
        When I fill in "_username" with "user-confirmed@domain.com"
        And I fill in "_password" with "test1234"
        And I press "login-btn"
        Then I dump the page

    Scenario: Login fails with invalid password
        Given I am on "/login"
        When I fill in "_username" with "user-confirmed@domain.com"
        And I fill in "_password" with "test"
        And I press "login-btn"
        Then I should see "Invalid credentials."

    Scenario: Login fails for unconfirmed user
        Given I am on "/login"
        When I fill in "_username" with "user-unconfirmed@domain.com"
        And I fill in "_password" with "test"
        And I press "login-btn"
        Then I should see "Your registration is not confirmed."

    Scenario: Sign up link works
        Given I am on "/login"
        When I follow "Sign up"
        Then I should be on "/registration"
        And the response status code should be 200

    Scenario: Back to home link works
        Given I am on "/login"
        When I follow "Back to home"
        Then I should be on "/"
        And the response status code should be 200
