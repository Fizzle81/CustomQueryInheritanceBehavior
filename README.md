CustomQueryInheritanceBehavior for Propel 2
==========================================

Propel 2 Behavior to customize the inheritance for query objects in a single inheritance setup

License
-------

MIT License

copyright (c) 2015 Christoph Quadt

Functionality
-------------
If there is a single inheritance set on a propel class, the current way of inheriting is:

FantasyBookQuery
    => BaseFantasyBookQuery
    => BaseBookQuery
    => ModelCriteria

This Builder provides the following setup:

FantasyBookQuery
    => BaseFantasyBookQuery
    => **BookQuery**
       or
    => **MyCustomQuery**
    => BaseBookQuery
    => ModelCriteria

Requirements
------------

This behavior requires

* [Propel2](https://github.com/propelorm/Propel2) >= 2.0@dev
* [QueryInheritanceBehaviorBuilder](https://github.com/fizzle81/QueryInheritanceBehaviorBuilder) >= 1.0.0


Installation
------------

To enable the builder, you need to 

1. reference the QueryInheritanceBehaviorBuilder as a custom builder in the propel settings:
```ini
propel.generator.objectModel.builders.queryinheritance = chq81\\CustomQueryInheritance\\Builder\\CustomQuerySingleInheritanceBuilder
```

2a. enable the behavior in the schema.xml for inheriting from the default query class (BookQuery):

```xml
<table name="book">
  <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
  <column name="title" type="VARCHAR" required="true" />
  <column name="genre" phpName="Genre" type="INTEGER" size="11" required="true" defaultValue="0" inheritance="single">
    <inheritance key="1" class="FantasyBook" extends="Book" />
    <inheritance key="2" class="HorrorBook" extends="Book" />
  </column>
  <behavior name="custom-query-inheritance" />
</table>
```

2b. enable the behavior in the schema.xml for inheriting from the custom query class (MyCustomQuery):

```xml
<table name="book">
  <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
  <column name="title" type="VARCHAR" required="true" />
  <column name="genre" phpName="Genre" type="INTEGER" size="11" required="true" defaultValue="0" inheritance="single">
    <inheritance key="1" class="FantasyBook" extends="Book" />
    <inheritance key="2" class="HorrorBook" extends="Book" />
  </column>
  <behavior name="custom-query-inheritance">
    <parameter name="base" value="[NAMESPACE]\\MyCustomQuery" />
  </behavior>
</table>
```