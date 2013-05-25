mva-crud
========

Base module to extend to simplify crud development

Conventions
========
- all entities using this module must implement a getName() and a getId() method
- all services using this module must implement getEntity(), getAllEntities(), deleteEntity(), upsertEntityFromArray()