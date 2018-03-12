# TODO

## Ideas

Tactic returns from 0 to 1000
Tactic can't calculate from goals
Coeeficents must differ to avoid move cycling
However another mechanism avoiding cycling also must be in place 
(randomizing? or just trying to avoid place where you have been already)

## Yet another refactoring
1. Introduce game builder, ? location graph builder
2. Move all objects to Game
3. Introduce methods like isGoal, getGoal in 


## Tactics:

1. Avoid stronger enemy at distance = 2
1. Attack weaker enemy at distance = 2 with gold > 0
2. Choose tavern that has more gold around.
3. Friendly mode?
4. Not attack weaker enemy on its spawn place
