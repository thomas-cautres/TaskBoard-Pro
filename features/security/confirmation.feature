Feature: Account Confirmation
    As a newly registered user
    I want to confirm my account with a verification code
    So that I can activate my account and log in

    Background:
        Given there is a signed confirmation URL for "user-unconfirmed@domain.com"

    Scenario: Confirmation page loads successfully with valid signed URL
        When I visit the signed confirmation URL
        Then the response status code should be 200

    Scenario: Confirmation page displays all content
        Given I visit the signed confirmation URL
        Then I should see "Verify your account"
        And I should see "Enter verification code"
        And I should see "We've sent a 6-digit verification code to your email address. Please enter it below to verify your account."
        And I should see "Verify account"
        And I should see "Back to login"

    Scenario: Successful account confirmation with valid code
        Given I visit the signed confirmation URL
        When I fill in "confirm[confirmationCode]" with "1234"
        And I press "confirm-btn"
        Then I should be on "/login"
        And I should see "Your registration was successfully confirmed."

    Scenario: Confirmation fails with invalid code
        Given I visit the signed confirmation URL
        When I fill in "confirm[confirmationCode]" with "1233"
        And I press "confirm-btn"
        Then the response status code should be 422
        And I should see "This code is not valid."

    Scenario: Accessing confirmation page with invalid signature fails
        When I visit a tampered confirmation URL
        Then the response status code should be 403

    Scenario: Accessing confirmation page without signature fails
        When I am on "/confirm/user-unconfirmed@domain.com"
        Then the response status code should be 403

    Scenario: Confirmation fails for non-existent user
        Given there is a signed confirmation URL for "nonexistent@domain.com"
        When I visit the signed confirmation URL
        Then the response status code should be 404

    Scenario: Back to login link works
        Given I visit the signed confirmation URL
        When I follow "Back to login"
        Then I should be on "/login"
        And the response status code should be 200
