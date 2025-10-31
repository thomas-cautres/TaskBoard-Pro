Feature: Registration
    As a visitor
    I want to register for a new account
    So that I can use TaskBoard Pro

    Scenario: Registration page loads successfully
        When I am on "/registration"
        Then the response status code should be 200

    Scenario: Registration page displays all content
        Given I am on "/registration"
        Then I should see "Register"
        And I should see "Join TaskBoard Pro for free"
        And I should see "Email"
        And I should see "Password"
        And I should see "Confirm password"
        And I should see "Sign up"
        And I should see "Already have an account?"
        And I should see "Log in"
        And I should see "Back to home"

    Scenario: Successful registration with valid data
        Given I am on "/registration"
        When I fill in "registration[firstName]" with "Bob"
        And I fill in "registration[lastName]" with "Smith"
        And I fill in "registration[email]" with "newuser@test.com"
        And I fill in "registration[password][first]" with "testtest123"
        And I fill in "registration[password][second]" with "testtest123"
        And I press "signup-btn"
        Then I should be on "/confirm/newuser@test.com"

    Scenario: Registration fails with invalid email
        Given I am on "/registration"
        When I fill in "registration[firstName]" with "Bob"
        And I fill in "registration[lastName]" with "Smith"
        And I fill in "registration[email]" with "test"
        And I fill in "registration[password][first]" with "testtest123"
        And I fill in "registration[password][second]" with "testtest123"
        And I press "signup-btn"
        Then the response status code should be 422

    Scenario: Registration fails with empty first name
        Given I am on "/registration"
        When I fill in "registration[firstName]" with ""
        And I fill in "registration[lastName]" with "Smith"
        And I fill in "registration[email]" with "test@test.com"
        And I fill in "registration[password][first]" with "testtest123"
        And I fill in "registration[password][second]" with "testtest123"
        And I press "signup-btn"
        Then the response status code should be 422

    Scenario: Registration fails with empty last name
        Given I am on "/registration"
        When I fill in "registration[firstName]" with "Bob"
        And I fill in "registration[lastName]" with ""
        And I fill in "registration[email]" with "test@test.com"
        And I fill in "registration[password][first]" with "testtest123"
        And I fill in "registration[password][second]" with "testtest123"
        And I press "signup-btn"
        Then the response status code should be 422

    Scenario: Registration fails with mismatched passwords
        Given I am on "/registration"
        When I fill in "registration[firstName]" with "Bob"
        And I fill in "registration[lastName]" with "Smith"
        And I fill in "registration[email]" with "test@test.com"
        And I fill in "registration[password][first]" with "testtest123"
        And I fill in "registration[password][second]" with "testtest"
        And I press "signup-btn"
        Then the response status code should be 422
        And I should see "The password fields must match."

    Scenario: Registration fails with weak password
        Given I am on "/registration"
        When I fill in "registration[firstName]" with "Bob"
        And I fill in "registration[lastName]" with "Smith"
        And I fill in "registration[email]" with "test@test.com"
        And I fill in "registration[password][first]" with "test"
        And I fill in "registration[password][second]" with "test"
        And I press "signup-btn"
        Then the response status code should be 422
        And I should see "This value is too short. It should have 8 characters or more."

    Scenario: Registration fails with existing email
        Given I am on "/registration"
        When I fill in "registration[firstName]" with "Bob"
        And I fill in "registration[lastName]" with "Smith"
        And I fill in "registration[email]" with "user-confirmed@domain.com"
        And I fill in "registration[password][first]" with "testtest123"
        And I fill in "registration[password][second]" with "testtest123"
        And I press "signup-btn"
        Then the response status code should be 422
        And I should see "This email is already registered."

    Scenario: Log in link works
        Given I am on "/registration"
        When I follow "Log in"
        Then I should be on "/login"
        And the response status code should be 200

    Scenario: Back to home link works
        Given I am on "/registration"
        When I follow "Back to home"
        Then I should be on "/"
        And the response status code should be 200
