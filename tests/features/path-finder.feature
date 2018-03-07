Feature: Finding pathes on the map

  @ignore
  Scenario: Find shortest path on the location matrix
    Given there is a map:
      """
##      ##
##  ##  ##
##      ##
##########
      """
    Then the distance from "0:1" to "2:3" is 4


  Scenario: Find shortest path on the location matrix
    Given there is a map:
      """
##########
##  ##  ##
##  ##  ##
##  ##  ##
##      ##
##########
      """
    Then the distance from "1:1" to "1:3" is 8


  Scenario: Find shortest path on the location matrix 3
    Given there is a map:
      """
################
##  ##        ##
##    ##      ##
##      ##    ##
##        ##  ##
##            ##
################
      """
    Then the distance from "1:1" to "1:3" is 16
    And the distance from "1:3" to "1:1" is 16