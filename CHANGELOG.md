# Changelog
All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## pre-release < 0.1
For the early pre-release development phase, we only showcase the important shifts and changes.
When a release is tagged, the changelog will be honouring the expecting information.

- trait `ActingAsCollection` is now renamed to `MorphedClassInstances`. It should now contain the class reference
instead of the collection key.
- Registration of manageable entities is now done in a ProjectServiceProvider, in favor of the config file.