Feature: Build location graph

  Scenario: Get adjacent nodes in a location graph

    Given the location graph has locations:
      | 0:0 |     | 0:2 |
      | 1:0 | 1:1 | 1:2 |
      | 2:0 | 2:1 |     |
      |     |     | 3:2 |
    Then adjacent locations of "1:1" are:
      | 1:0 |
      | 1:2 |
      | 2:1 |
    Then adjacent locations of "0:2" are:
      | 1:2 |
    And there are no adjacent locations of "3:2"
