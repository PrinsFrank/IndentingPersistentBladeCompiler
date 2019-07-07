# Indenting persistent blade compiler

Have you ever looked at the HTML Laravel generates and wondered about the mess? That's what I want to solve with this package. [As this is not going to be fixed in Laravel Framework itself](https://github.com/laravel/framework/pull/28768) I decided to make it into a package, and here it is!

Different updated compilers should be added, the current status is:

- [ ] Components
- [ ] Conditionals
- [x] Includes
- [ ] Layouts
- [x] Stacks

## Setting things up

Add the service provider to ``app/config.php`` in the ``Package Service Providers...`` area in the ``providers`` array:  ``PrinsFrank\IndentingPersistentBladeCompiler\IndentedViewServiceProvider::class``
