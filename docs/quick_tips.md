# Cerberus

## Quick Tips

### Target

The (optional) `target` component applies to `Rule`, `Policy` and `PolicySet`, and is used to
determine if the component (to which it belongs) can be applied the request.

NOTE: the `anyOf` (logical OR) and `allOf` (logical AND) components are limited
to the `target` component.

The `match` component must consist of an `AttributeValue` with an `AttributeSelector`
 OR an `AttributeDesignator`
 
 ### Rule
 
 The `apply` component inside a target takes a "functionId" and an optional "expression"