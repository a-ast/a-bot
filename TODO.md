# TODO

### Plan
1. Check if algorithm faster if not building next steps array
1. Optimize for symmetric maps
1. VERY IMPORTANT: smarter avoiding in case you have no gold or iw weker than anybody
1.1 Track if avoiding wins? How for 3-4 steps and then disable it

#### New tactics
1. low coeff: find potential gold or tavern without caring about LP
1. Counter for staying and repetitive movements: disable or change winning tactic
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


