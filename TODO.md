# TODO

### Super Plan
1. Implement simualtor and muatate weights to find optimum.
2. Write stats to postgres.

### Plan

1. Check if algorithm faster if not building next steps array
1. Optimize for symmetric maps
1. VERY IMPORTANT: smarter avoiding in case you have no gold or iw weker than anybody
1.1 Track if avoiding wins? How for 3-4 steps and then disable it

#### New tactics
1. IMPORTANT: do not attck heroes NEAR their spawn locations
1. low coeff: find potential gold or tavern without caring about LP
1. Counter for staying and repetitive movements: 
  * Idea 1: take not best but second rom top
  --- repetition principle: 3 unique locations in 9
  * Idea 2: disable or change winning tactic
1. Tracking traps? HOW?

### NEW Ideas

1. Introduce tactic disabler (isApplicable?)
1. AvoidStrong must also return from 0 to 1000 as a distance to another hero

## Ideas

Tactic returns from 0 to 1000
Tactic can't calculate from goals
Coefficents must differ to avoid move cycling
However another mechanism avoiding cycling also must be in place 
(randomizing? or just trying to avoid place where you have been already)


