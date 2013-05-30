mva-crud
========

Base module to extend to simplify crud development

Conventions
========
- all entities using this module must implement a getName() and a getId() method
- all services using this module must implement getEntity(), getAllEntities(), deleteEntity(), upsertEntityFromArray()


To Do
=====
- add option to display a page on delete
- add option to require page confirm prior to delete
- add option to require alert confirm  prior to delete
- add option to disable listing all entities
- add function to call to check if user is allowed to view/edit/delete entity
- add events trigger on pre/post insert,delete, update
