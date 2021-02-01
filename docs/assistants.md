
## routing
Builds and returns the fully qualified route for a given action. 
When dealing with a dynamic route constructed from a specific model, a model instance should be passed as second argument.

route generating method return the generated route string or null.
calling route: each assistant route method is responsible for checking and validating the action parameter.
 It should check if the passed action applies as an action for the trait. In which case, the trait should generate the action route and return it.
 If the action not refers to one of the trait actions, it should return null.
