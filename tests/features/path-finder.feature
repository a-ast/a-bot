Feature: Finding pathes on the map

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


  Scenario: Find shortest path on the map with a goal
    Given there is a map:
      """
############
##    $-  ##
##        ##
############
      """
    And the map has the goal at "1:3"
    Then the distance from "1:1" to "1:4" is 5
    And the path from "1:1" to "1:4" is "1:2->2:2->2:3->2:4->1:4"
    And the distance from "1:1" to "1:3" is 2
    And the path from "1:1" to "1:3" is "1:2->1:3"
